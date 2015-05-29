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

        /**
          $filePath = "../tmp/feedInfowork.xml";
          $result = file_get_contents($filePath);
          $result = preg_replace("/\<\!\[CDATA\[(.*?)\]\]\>/ies", "'[CDATA]'.base64_encode('$1').'[/CDATA]'", $result);
          self::$xml = new SimpleXMLElement($result);
          return true;
         * 
         */
        //El nombre del archivo donde se almacenara los datos descargados.
        $filePath = '../tmp/feedInfowork' . date('Ymd_His') . ".xml";
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
            $slug = Textos::limpia($familia);
            $fam = new Familias();
            $fam->setFamilia($familia);
            $fam->setMargenWeb($margenweb);
            $fam->setPublish(1);
            $fam->setNivelJerarquico(1);
            $fam->setSlug($slug);
            $fam->setUrlFriendly("/" . $slug);
            $id = $fam->create();

            if ($id > 0) {

                $urls = new CpanUrlAmigables();
                $urls->setUrlFriendly("/" . $slug);
                $urls->setController("Familias");
                $urls->setAction("Index");
                $urls->setTemplate("index.html.twig");
                $urls->setEntity("Familias");
                $urls->setIdEntity($id);
                $urls->create();

                foreach ($value as $subfamilia => $value) {
                    $slug = Textos::limpia($subfamilia);
                    $sub = new Familias();
                    $sub->setFamilia($subfamilia);
                    $sub->setMargenWeb($margenweb);
                    $sub->setBelongsTo($id);
                    $sub->setPublish(1);
                    $sub->setNivelJerarquico(2);
                    $sub->setSlug($slug);
                    $sub->setUrlFriendly("/" . $slug);
                    $idsub = $sub->create();
                    if ($idsub > 0) {
                        $urls = new CpanUrlAmigables();
                        $urls->setUrlFriendly("/" . $slug);
                        $urls->setController("Familias");
                        $urls->setAction("Index");
                        $urls->setTemplate("index.html.twig");
                        $urls->setEntity("Familias");
                        $urls->setIdEntity($idsub);
                        $urls->create();
                    }
                }
                unset($sub);
            }
            unset($fam);
        }
    }

    static function CrearFabricantes($fabricantes) {

        foreach ($fabricantes as $fabricante) {
            $slug = Textos::limpia($fabricante);
            $fab = new Fabricantes();
            $fab->setTitulo($fabricante);
            $fab->setPublish(1);
            $fab->setSlug($slug);
            $fab->setUrlFriendly("/" . $slug);
            $id = $fab->create();
            if ($id) {
                $urls = new CpanUrlAmigables();
                $urls->setUrlFriendly("/" . $slug);
                $urls->setController("Fabricantes");
                $urls->setAction("Index");
                $urls->setTemplate("index.html.twig");
                $urls->setEntity("Fabricantes");
                $urls->setIdEntity($id);
                $urls->create();
            }
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
    static function DescargaImagen($origen, $destino) {

        $copiado = 0;

        $imagendescargada = file_get_contents($origen);

        if ($imagendescargada) {
            $copiado = file_put_contents($destino, $imagendescargada);
            if (!$copiado) {
                echo "FALLO al descargar {$origen} => {$destino}\n";
            } else {
                echo "DESCARGA {$origen} => {$destino}\n";
            }
        }

        return ($copiado);
    }

    static function DescargaImagenes() {
        foreach (self::$xml->producto as $item) {
            $slug = Textos::limpia($item->nombre);
            $origen = str_replace("Albatronic", "infowork", self::Limpia($item->imagen));
            $carpeta = "/Users/sergio/www/tienda/themes/theme3/docs/Articulos/" . substr($slug, 0, 3);
            $pathName = "docs/Articulos/" . substr($slug, 0, 3) . "/" . $slug . ".jpg";
            @mkdir($carpeta, 0755, true);
            $urlDestino = $carpeta . "/" . $slug . ".jpg";

            if (!file_exists($urlDestino)) {
                $ok = self::DescargaImagen($origen, $urlDestino);
            } else {
                $ok = true;
            }

            if ($ok) {
                $art = new Articulos();
                $rows = $art->querySelect("IDArticulo", "Codigo='{$item->part_number}'");
                $docs = new CpanDocs();
                $docs->setEntity('Articulos');
                $docs->setIdEntity($rows[0]['IDArticulo']);
                $docs->setType('image1');
                $docs->setPathName($pathName);
                $docs->setName($slug);
                $docs->setExtension("jpg");
                $docs->setTitle(self::Limpia($item->nombre));
                $docs->setMimeType("image/jpeg");
                $docs->setPublish(1);
                $docs->create();
            }
        }
    }

    /**
     * CARGA LA TABLA DE ARTICULOS EN BASE AL OBJETO XML CONSTRUIDO PREVIAMENTE
     * DEVUELVE UN ENTERO CON EL NUMEROS DE ERRORES PRODUCIDOS EN LA CARGA
     * A TODOS LOS ARTICULOS SE LE PONE UN 20% DE MARGEN COMERCIAL.
     * @param OBJETOXML $xml
     * @return int
     */
    static function CrearArticulos() {

        $fallos = 0;

        foreach (self::$xml->producto as $item) {
            $codigo = str_replace("/", "_", trim($item->part_number));
            $categoria = self::Limpia($item->categoria_1);
            if ($categoria == '') {
                $categoria = 'varios';
            }
            $familia = self::Limpia($item->categoria_2);
            if ($familia == '') {
                $familia = 'varios';
            }
            $subfamilia = self::Limpia($item->categoria_3);

            $tmp = $item->nombre;
            $nombre = self::Limpia($tmp);
            $tmp = $item->descripcion;
            $descripcion = self::Limpia($tmp);
            $pvd = str_replace(",", ".", $item->precio);
            $pvp = str_replace(",", ".", $item->pvpr); //$pvd * (1 + $margen / 100);
            $pvp = $pvp / 1.21;
            $margen = 0;
            if ($pvd > 0) {
                $margen = $pvp / $pvd * 100 - 100;
            }

            $peso = str_replace(",", ".", $item->peso);
//Le quito los espacios en blanco al principio y al final
//Los espacios en blanco intermedios los sustituyo por %20 para que la url sea correcta.
            $imagen = self::Limpia($item->imagen);

            $fab = new Fabricantes();
            $fab = $fab->find("Titulo", self::Limpia($item->marca));
            $idFabricante = $fab->getIDFabricante();

//Buscar el id de categoria
            $fam = new Familias();
            $fam = $fam->find("Familia", $categoria);
            $idCategoria = $fam->getIDFamilia();

//Buscar el id de familia
            $fam = $fam->find("Familia", $familia);
            $idFamilia = $fam->getIDFamilia();

//Buscar el id de subfamilia
            $idSubfamilia = 0;
            if ($subfamilia != '') {
                $fam = $fam->find("Familia", $subfamilia);
                $idSubfamilia = $fam->getIDFamilia();
            }

            $slug = Textos::limpia($nombre);

            $art = new Articulos();
            $art->setCodigo($codigo);
            $art->setDescripcion($nombre);
            $art->setSubtitulo($nombre);
            $art->setResumen($descripcion);
            $art->setIDCategoria($idCategoria);
            $art->setIDFamilia($idFamilia);
            $art->setIDSubfamilia($idSubfamilia);
            $art->setIDFabricante($idFabricante);
            $art->setPvd($pvd);
            $art->setMargen($margen);
            $art->setPvp($pvp);
            $art->setIDIva(1);
            $art->setCodigoEAN($item->ean);
            $art->setPeso($peso);
            $art->setCaracteristicas(self::Limpia($item->caracteristicas));
            $art->setPublish(1);
            $art->setSlug($slug);
            $art->setUrlFriendly("/" . $slug);
            $id = $art->create();
//print_r($art->getErrores());
            if (!$id) {
                $fallos++;
                echo "<tr><td>", $codigo, "</td><td>", $nombre, "</td><td>", $categoria, "</td><td>", $familia, "</td></tr>";
            } else {
                $urls = new CpanUrlAmigables();
                $urls->setUrlFriendly("/" . $slug);
                $urls->setController("Producto");
                $urls->setAction("Index");
                $urls->setTemplate("index.html.twig");
                $urls->setEntity("Articulos");
                $urls->setIdEntity($id);
                $urls->create();

//COPIAR IMAGEN EN LOCALHOST SI NO EXISTE PREVIAMENTE.
                if (false) {
//if ($imagen !== '') {
                    $carpeta = "/Users/sergio/www/tienda/themes/theme3/docs/Articulos/" . substr($slug, 0, 3);
                    mkdir($carpeta, 0755, true);
                    $urlDestino = $carpeta . "/" . $slug . ".jpg";
                    if (!file_exists($urlDestino)) {
                        self::DescargaImagen($imagen, $urlDestino);
                    }
                }
            }
        }

        return $fallos;
    }

    /**
     * Vacia las tablas de familias,subfamilias,fabricantes, articulos
     */
    static function VaciarTablas() {

        $tabla = new CpanUrlAmigables();
        $tabla->queryDelete("Controller='Familias' or Controller='Producto' or Controller='Fabricantes'");
        $tabla = new CpanDocs();
        $tabla->queryDelete("Entity='Articulos'");
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
//print_r($familiasFabricantes);
//Creo las familias y subfamilias en las tablas de la BD en base al array bidemensional
importar::CrearFamilias($familiasFabricantes['familias']);

// Creo los fabricantes
importar::CrearFabricantes($familiasFabricantes['fabricantes']);


//Cargo los articulos en la BD.
$fallos = importar::CrearArticulos();
echo "Articulos Fallidos: ", $fallos;

// Descargar las imágenes. Las que ya existen no se descargan
importar::DescargaImagenes();

