<?php

/**
 * Sincronizador de productos INFOWORK
 * @URL - http://portal.infowork.es/export/AP3YSJBJCOOBBWK2LGJF/listado.asp?l=sergio.perez@albatronic.com&p=AyH73J8Q&type=2&zip=0
 * @Script - xml_infowork
 *
 * DESCARGA EL ARCHIVO XML DE INFOWORK EN EL LOCALHOST
 * SE CONECTA AL SERVIDOR REMOTO SEGUN LOS PARAMETROS DE CONEXION (conexion.php)
 * VACIA LAS TABLAS: articulos, familias, subfamilias y fabricantes
 * REPUEBLA LAS TABLAS CON EL ARCHIVO XML
 * PARALELAMENTE DESCARGA LAS IMAGENES DE LOS PRODUCTOS EN EL LOCALHOST
 * CARPETA "catalogo" SI ES QUE NO EXISTEN PREVIAMENTE.
 *
 * DE FORMA MANUAL HABRÁ QUE COMPRIMIR LA IMAGENES Y SUBIRLAS AL SERVIDOR REMOTO.
 * EL ARCHIVO COMPRIMIDO HA DE LLAMARSE "catalogo.zip" Y UBICARLO EN "www/catalogo/"
 * PARA DESCOMPRIMIR LAS IMAGENES EN EL SERV. REMOTO, EJECUTAR www.albatronic.com/unzipcatalogo.php
 *
 * */
/** Informacion para desarrolladores de la Importación

  <?xml version="1.0"?>
  <datos>
    <producto>
        <codigo_mayorista>116</codigo_mayorista>
        <part_number>6881A002-CHQ</part_number>
        <ean>8435350705992</ean>
        <categoria_1>Consumibles</categoria_1>
        <categoria_2>Consumible Compatible</categoria_2>
        <categoria_3>Compatible con Canon</categoria_3>
        <marca>Generica</marca>
        <precio>0.75</precio>
        <canon>0</canon>
        <pvpr>1.27</pvpr>
        <stock>2</stock>
        <peso>0.04</peso>
        <nombre>CARTUCHO COMPAT. CON CANON BCI-24 BK NEGRO HQ</nombre>
        <descripcion/>
        <caracteristicas><![CDATA[<p>Descripción:<br />
        CANON CARGA COMPATIBLE INYECCION TINTA NEGRO BCI-24BK 9ML</p>
        <p>Modelos:</p>
        <p>S 200 200X 300 330 PHOTO *I 250 320 350 450 455 470D 475D *MPC- 190 200 360 370 390 *IP       - 1000 1500 2000 *MP 110 </p>
        ]]></caracteristicas>
        <imagen><![CDATA[http://recursos.infowork.es/img/000/000/000000006.jpg]]></imagen>
    </producto>

  ...
  </datos> --> Fin del Datafeed
 * */
include "autoloader.inc";

class importar {

    static $urlXml = "https://clientes.infowork.es/exportar_xml?custid=%7F%7Ed";
    static $xml;

    /**
     * Carga el feed XML
     * 
     * @return boolean
     */
    static function getXml() {

        //El nombre del archivo donde se almacenara los datos descargados.
        $filePath = dirname(__FILE__) . '/feedInfowork' . date('Ymd_His') . ".xml";
        //Inicializa Curl.
        $ch = curl_init();

        //Pasamos la url a donde debe ir.
        curl_setopt($ch, CURLOPT_URL, self::$urlXml);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        //Cantidad de segundos de limite para ejecutar curl. 0 significa indefinido.
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        $result = curl_exec($ch);
        $ok = strlen($result);

        if ($ok) {
            $fp = fopen($filePath, "w");
            echo $fp, " ", $filePath;
            fwrite($fp, $result);
            fclose($fp);
            $result = preg_replace("/\<\!\[CDATA\[(.*?)\]\]\>/ies", "'[CDATA]'.base64_encode('$1').'[/CDATA]'", $result);
            self::$xml = new SimpleXMLElement($result);
        }

        return $ok;
    }

    /**
     * TRATAMIENTO DE FAMILIAS Y SUBFAMILIAS y FABRICANTES
     * CREO UNA TABLA BIDIMENSIONAL CON LAS FAMILIAS Y LAS SUBFAMILIAS
     * CREO UNA TABLA CON LOS FABRICANTES
     * 
     * @return array(familias, fabricantes)
     */
    static function getFamiliasFabriantes() {

        $arrayFamilias = $arrayFabricantes = array();
        $i = 0;

        foreach (self::$xml->producto as $item) {
            $familia = self::Limpia($item->categoria_1);
            if ($familia === '') {
                $familia = 'Varios';
            }
            $subfamilia = self::Limpia($item->categoria_2);
            if ($subfamilia === '') {
                $subfamilia = 'Varios';
            }
            if (!array_key_exists($familia, $arrayFamilias)) {
                //La familia es nueva
                $i++;
                $arrayFamilias[$familia] = array();
            }
            if (!array_key_exists($subfamilia, $arrayFamilias[$familia])) {
                //La subfamilia es nueva para esa familia
                $arrayFamilias[$familia][$subfamilia] = "";
            }
            //echo "[",$familia,"] [",$subfamilia,"]<br>";

            $fabricante = self::Limpia($item->marca);
            $arrayFabricantes[$fabricante] = $fabricante;
        }

        return array('familias' => $arrayFamilias, 'fabricantes' => $arrayFabricantes);
    }

    /**
     * CREAR LAS FAMILIAS Y LAS SUBFAMILIAS EN LA BD
     * TODAS LAS FAMILIAS Y SUBFAMILIAS SE CREAN PUBLICABLES EN LA WEB
     * EL MARGEN COMERCIAL PARA LA WEB SE ESTABLE AL 10% PARA TODAS LAS FAMILIAS.
     * @param array $familias
     */
    static function CrearFamilias($familias) {

        $margenweb = 10;

        foreach ($familias as $familia => $value) {
            $fam = new Familias();
            $fam->setFamilia($familia);
            $fam->setMargenWeb($margenweb);
            $fam->setPublish(1);
            $idfam = $fam->create();
            if ($idfam > 0) {
                foreach ($value as $subfamilia => $value) {
                    $sub = new Familias();
                    $sub->setFamilia($subfamilia);
                    $sub->setMargenWeb($margenweb);
                    $sub->setBelongsTo($idfam);
                    $sub->setPublish(1);
                    $sub->create();
                }
                unset($sub);
            }
            unset($fam);
        }
    }

    static function CrearFabricantes($fabricantes) {

        foreach ($fabricantes as $fabricante) {
            $fab = new Fabricantes();
            $fab->setTitulo($fabricante);
            $fab->create();
        }
    }

    /**
     * HACE CIERTAS FUNCIONES DE LIMPIEZA DE UN STRING
     * @param string $str
     * @return string
     */
    static function Limpia($str) {
        $tmp = preg_replace("/\[CDATA\](.*?)\[\/CDATA\]/ies", "base64_decode('$1')", $str);
        $tmp = str_replace("\"", "", $tmp);
        $tmp = str_replace("'", "", $tmp);
        $tmp = str_replace("`", "", $tmp);
        $tmp = str_replace("´", "", $tmp);
        $tmp = str_replace("Á", "A", $tmp);
        $tmp = str_replace("á", "a", $tmp);
        $tmp = str_replace("é", "e", $tmp);
        $tmp = str_replace("í", "i", $tmp);
        $tmp = str_replace("ó", "o", $tmp);
        $tmp = str_replace("Ó", "O", $tmp);
        $tmp = str_replace("ñ", "&ntilde;", $tmp);
        $tmp = str_replace("Infowork", "Albatronic", $tmp);
        $tmp = str_replace("infowork", "Albatronic", $tmp);
        $tmp = str_replace("INFOWORK", "ALBATRONIC", $tmp);
        return $tmp;
    }

    /**
     * Descarga la imagen del producto de la url origen ($imagen) y la pone en la
     * carpeta destinada al catálogo de productos del localhost
     * A la imagen se le asigna como nombre el código ($referencia) del producto
     *
     * @param string $referencia
     * @param url $imagen
     * @return int $copiado Flag true o false según el éxito de la copia
     */
    static function DescargaImagen($referencia, $imagen) {

        $copiado = 0;

        //$aux=str_replace("http://","",$imagen);
        //$url=split("/",$aux);
        $imagenname = "catalogo/" . $referencia . ".jpg";
        $imagendescargada = file_get_contents($imagen);

        if ($imagendescargada):
            $copiado = file_put_contents($imagenname, $imagendescargada);
            if (!$copiado):
                echo "<tr><td>$referencia</td><td>$categoria</td><td>$nombre</td><td>$pvd</td><td>$pvp</td><td><a href='$imagen' target='_blank'>$imagen</a></td><td>$imagenname</td></tr>";
            endif;
        endif;

        return ($copiado);
    }

    /**
     * CARGA LA TABLA DE ARTICULOS EN BASE AL OBJETO XML CONSTRUIDO PREVIAMENTE
     * DEVUELVE UN ENTERO CON EL NUMEROS DE ERRORES PRODUCIDOS EN LA CARGA
     * A TODOS LOS ARTICULOS SE LE PONE UN 20% DE MARGEN COMERCIAL.
     * @param OBJETOXML $xml
     * @return int
     */
    static function CargaArticulos($xml) {
        global $DB;

        $columnas = "IDArticulo,IDFabricante,IDFamilia,IDSubfamilia,Descripcion,Pvd,Margen,Pvp,IDIva,Peso,Caracteristicas";
        $margen = '20';

        $fallos = 0;
        echo "<table>";
        foreach ($xml->producto as $item) {
            $referencia = str_replace("/", "_", trim($item->referencia));
            $familia = Limpia($item->categoria_1);
            if ($familia == '') {
                $familia = 'varios';
            }
            $subfamilia = Limpia($item->categoria_2);
            if ($subfamilia == '') {
                $subfamilia = 'varios';
            }
            $tmp = $item->nombre;
            $nombre = Limpia($tmp);
            $tmp = $item->descripcion;
            $descripcion = Limpia($tmp);
            $pvd = str_replace(",", ".", $item->precio);
            $pvp = $pvd * (1 + $margen / 100);
            $peso = str_replace(",", ".", $item->peso);
            //Le quito los espacios en blanco al principio y al final
            //Los espacios en blanco intermedios los sustituyo por %20 para que la url sea correcta.
            $imagen = str_replace(" ", "%20", trim($item->urlimagen));

            $idfabricante = Fabricante(Limpia($item->fabricante));

            //Buscar el id de familia
            $sql = "select IDFamilia from $DB.familias where Familia='$familia';";
            $res = mysql_query($sql);
            $row = mysql_fetch_array($res);
            $idfamilia = $row[0];

            //Buscar el id de subfamilia
            $sql = "select IDSubfamilia from $DB.subfamilias where IDFamilia='$idfamilia' and Subfamilia='$subfamilia';";
            $res = mysql_query($sql);
            $row = mysql_fetch_array($res);
            $idsubfamilia = $row[0];

            //CREAR ARTICULO EN LA TABLA TEMPORAL
            $valores = "'$referencia','$idfabricante','$idfamilia','$idsubfamilia','$nombre','$pvd','$margen','$pvp','1','$peso','$descripcion'";
            $sql = "insert into $DB.articulos ($columnas) values ($valores);";
            $res = mysql_query($sql);
            if (!$res) {
                $fallos++;
                echo "<tr><td>", $referencia, "</td><td>", $nombre, "</td><td>", $familia, "</td><td>", $subfamilia, "</td></tr>";
                echo "<tr><td colspan=4>", $sql, "</td></tr>";
            } else {
                //COPIAR IMAGEN EN LOCALHOST SI NO EXISTE PREVIAMENTE.
                if ($imagen !== '') {
                    //if(!file_exists("catalogo/".$referencia.".jpg")) DescargaImagen($referencia,$imagen);
                }
            }
        }
        echo "</table>";
        return $fallos;
    }

    /**
     * Vacia las tablas de familias,subfamilias,fabricantes y articulos
     * @global <type> $DB
     */
    static function VaciarTablas() {

        $tabla = new CpanUrlAmigables();
        $tabla->queryDelete("Controller='Familias' or Controller='Articulos' or Controller='Fabricantes'");
        $tabla = new Familias();
        $tabla->truncate();
        $tabla = new Fabricantes();
        $tabla->truncate();
        $tabla = new Articulos();
        $tabla->truncate();
        unset($tabla);
    }

    /**
     * SUBE UNA IMAGEN AL SERVIDOR REMOTO UTILIZANDO FTP
     * @param <type> $source_file
     * @param <type> $destination_file
     * @param <type> $carpeta_destino
     * @return <type>
     */
    static function SubeImagenServidor($source_file, $destination_file, $carpeta_destino) {
        // set up basic connection
        $ftp_server = "www.albatronic.com";
        $ftp_user_name = "albatro";
        $ftp_user_pass = "p17s26a26";
        $resultado = "";

        // login with username and password
        $conn_id = ftp_connect($ftp_server);
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        // check connection
        if ((!$conn_id) || (!$login_result)) {
            return("La conexi&oacute;n FTP ha fallado!");
            exit;
        }

        // upload the file
        ftp_chdir($conn_id, $carpeta_destino);
        $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_IMAGE);

        // check upload status
        if (!$upload) {
            $resultado = "FTP upload has failed!";
        }

        // close the FTP stream
        ftp_close($conn_id);
        return ($resultado);
    }

}

//ESTE SCRIPT NO BORRAR PREVIAMENTE LAS TABLAS DE FAMILIAS, SUBFAMILIAS Y ARTICULOS

$_SESSION['usuarioPortal']['Id'] = 1;
$_SESSION['usuarioPortal']['IdPerfil'] = 1;
$_SESSION['idiomas']['actual'] = 0;
$_SESSION['conections'] = $config['config']['conections'];
$_SESSION['project']['conection'] = null;
$_SESSION['VARIABLES']['EnvPro']['activeFrom'] = '0000-00-00 00:00:00';
$_SESSION['VARIABLES']['EnvPro']['activeTo'] = '0000-00-00 00:00:00';
$_SESSION['VARIABLES']['EnvPro']['log'] = false;

// LEER EL FEED
importar::getXml();

//VACIAR LAS TABLAS
importar::VaciarTablas();

//Creo un array bidimensional con las familias y subfamilias que viene en el XML
$familiasFabricantes = importar::getFamiliasFabriantes();
print_r($familiasFabricantes);

//Creo las familias y subfamilias en las tablas de la BD en base al array bidemensional
importar::CrearFamilias($familiasFabricantes['familias']);

// Creo los fabricantes
importar::CrearFabricantes($familiasFabricantes['fabricantes']);


//Cargo los articulos en la BD.
//$fallos=CargaArticulos($xml);
echo "Articulos Fallidos: ", $fallos;

