<?php

session_start();

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

  <?xml version="1.0" encoding="iso-8859-1" ?>
  <ListadoProductosINFOWORK>
  <producto>
  <nombre>CONECTOR RJ45-H PARA EMPOTRAR</nombre>
  <referencia>C50200</referencia>
  <fabricante>Generica</fabricante>
  <descripcion />
  <precio>1,01</precio>
  <peso>0,01</peso>
  <urlimagen>http://img.infowork.es/imagenes/productos/C50200_2.JPG</urlimagen>
  <categoria1>Conectividad y Redes</categoria1>
  <categoria2>CONECTORES UTP / FTP</categoria2>
  <categoria3 />
  <fechaultimarevision>2008-11-25 09:43:01</fechaultimarevision>
  <stock>No</stock>
  </producto>

  ...
  </ListadoProductosINFOWORK> --> Fin del Datafeed
 * */
class cargaDatosDemo {

    static $xml;

    static function LeeArchivo($archivo) {
        // Crear el objeto XML con la fuente de datos
        //$url = "http://portal.infowork.es/export/AP3YSJBJCOOBBWK2LGJF/listado.asp?l=sergio.perez@albatronic.com&p=AyH73J8Q&type=2&zip=0";
        //$filename = "infowork.xml";
        $str = file_get_contents($archivo);
        //$str=file_get_contents($url);
        //file_put_contents($filename, $str);

        $str = preg_replace("/\<\!\[CDATA\[(.*?)\]\]\>/ies", "'[CDATA]'.base64_encode('$1').'[/CDATA]'", $str);
        self::$xml = new SimpleXMLElement($str);
    }

    /**
     * TRATAMIENTO DE FAMILIAS Y SUBFAMILIAS.
     * CREO UN TABLA BIDIMENSIONAL CON LAS FAMILIAS Y LAS SUBFAMILIAS
     * @return array
     */
    static function getFamilias() {
        $tabla = array();
        $i = 0;
        foreach (self::$xml->producto as $item) {
            $familia = self::Limpia($item->categoria1);
            if ($familia == '')
                $familia = 'varios';
            $subfamilia = self::Limpia($item->categoria2);
            if ($subfamilia == '')
                $subfamilia = 'varios';
            if (!array_key_exists($familia, $tabla)) {//La familia es nueva
                $i++;
                $tabla[$familia] = array();
            }
            if (!array_key_exists($subfamilia, $tabla[$familia])) {//La subfamilia es nueva para esa familia
                $tabla[$familia][$subfamilia] = "";
            }
        }

        return $tabla;
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
            $fam->setNivelJerarquico(1);
            $idfam = $fam->create();
            if ($idfam) {
                self::actualizaUrlAmigable('Familias', 'Familias', $idfam, $familia);
                foreach ($value as $subfamilia => $value) {
                    $sub = new Familias();
                    $sub->setFamilia($subfamilia);
                    $sub->setMargenWeb($margenweb);
                    $sub->setBelongsTo($idfam);
                    $sub->setPublish(1);
                    $sub->setNivelJerarquico(2);
                    $idsub = $sub->create();
                    if ($idsub) {
                        self::actualizaUrlAmigable('Familias', 'Familias', $idsub, $subfamilia);
                    }
                }
                unset($sub);
            }
            unset($fam);
        }
    }

    /**
     * Busca la existencia del fabricante $fabricante por descripcion
     * si existe devuelve el id
     * si no existe lo crea y devuelve el id asignado
     * @param <type> $fabricante
     * @return <type> $id
     */
    static function CreaFabricante($fabricante) {

        $fab = new Fabricantes();
        $rows = $fab->cargaCondicion("IDFabricante", "Titulo='{$fabricante}'");
        $id = $rows[0]['IDFabricante'];
        unset($fab);

        if (!$id) {
            $fab = new Fabricantes();
            $fab->setTitulo($fabricante);
            $fab->setPublish(1);
            $fab->setMostrarPortada(1);
            $id = $fab->create();
            if ($id) {
                self::actualizaUrlAmigable('Fabricantes', 'Fabricantes', $id, $fabricante);
            }
            unset($fab);
        }

        return $id;
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

        if ($imagendescargada) {
            $copiado = file_put_contents($imagenname, $imagendescargada);
            if (!$copiado) {
                echo "<tr><td>$referencia</td><td>$categoria</td><td>$nombre</td><td>$pvd</td><td>$pvp</td><td><a href='$imagen' target='_blank'>$imagen</a></td><td>$imagenname</td></tr>";
            }
        }

        return ($copiado);
    }

    /**
     * CARGA LA TABLA DE ARTICULOS EN BASE AL OBJETO XML CONSTRUIDO PREVIAMENTE
     * DEVUELVE UN ENTERO CON EL NUMEROS DE ERRORES PRODUCIDOS EN LA CARGA
     * A TODOS LOS ARTICULOS SE LE PONE UN 20% DE MARGEN COMERCIAL.
     * @return int
     */
    static function CargaArticulos() {
        $margen = '20';

        $i = 0;
        $fallos = 0;
        echo "<table>";
        foreach (self::$xml->producto as $item) {
            $referencia = str_replace("/", "_", trim($item->referencia));
            $familia = self::Limpia($item->categoria1);
            if ($familia == '') {
                $familia = 'varios';
            }
            $subfamilia = self::Limpia($item->categoria2);
            if ($subfamilia == '') {
                $subfamilia = 'varios';
            }
            $tmp = $item->nombre;
            $nombre = self::Limpia($tmp);
            $tmp = $item->descripcion;
            $descripcion = self::Limpia($tmp);
            $pvd = str_replace(",", ".", $item->precio);
            $pvp = $pvd * (1 + $margen / 100);
            $peso = str_replace(",", ".", $item->peso);
            //Le quito los espacios en blanco al principio y al final
            //Los espacios en blanco intermedios los sustituyo por %20 para que la url sea correcta.
            $imagen = str_replace(" ", "%20", trim($item->urlimagen));

            $idfabricante = self::CreaFabricante(self::Limpia($item->fabricante));

            //Buscar el id de familia
            $fam = new Familias();
            $rows = $fam->cargaCondicion("IDFamilia", "Familia='{$familia}'");
            $idfamilia = $rows[0]['IDFamilia'];

            //Buscar el id de subfamilia            
            $fam = new Familias();
            $rows = $fam->cargaCondicion("IDFamilia", "Familia='{$subfamilia}'");
            $idsubfamilia = $rows[0]['IDFamilia'];

            //CREAR ARTICULO EN LA TABLA TEMPORAL
            $arti = new Articulos();
            $arti->setCodigo($referencia);
            $arti->setDescripcion($nombre);
            $arti->setIDFabricante($idfabricante);
            $arti->setIDCategoria($idfamilia);
            $arti->setIDFamilia($idsubfamilia);
            $arti->setPvd($pvd);
            $arti->setPvp($pvp);
            $arti->setMargen($margen);
            $arti->setPeso($peso);
            $arti->setCaracteristicas($descripcion);
            $arti->setGarantia("S/F");
            $arti->setPublish(1);
            $arti->setFechaUltimoPrecio($item->fechaultimarevision);
            $idarti = $arti->create();

            if (!$idarti) {
                $fallos++;
                echo "<tr><td>", $referencia, "</td><td>", $nombre, "</td><td>", $familia, "</td><td>", $subfamilia, "</td></tr>";
                echo "<tr><td colspan=4>", serialize($arti->getErrores()), "</td></tr>";
            } else {
                if ($idarti) {
                    $slug = self::actualizaUrlAmigable('Producto', 'Articulos', $idarti, $nombre);
                }
                //COPIAR IMAGEN EN LOCALHOST SI NO EXISTE PREVIAMENTE.
                if ($imagen != '') {
                    self::copiaImagen();

                    //if(!file_exists("catalogo/".$referencia.".jpg")) DescargaImagen($referencia,$imagen);
                }
            }

            $i += 1;
            //if ($i>1000) break;
        }
        echo "</table>";
        return $fallos;
    }

    static function copiaImagen() {
        
    }

    static function actualizaUrlAmigable($controller, $entidad, $idEntidad, $slug) {

        $slug = Textos::limpia($slug);

        $objeto = new $entidad($idEntidad);
        $objeto->setSlug($slug);
        $objeto->setUrlFriendly("/" . $slug);
        $objeto->setUrlHeritable(0);
        $objeto->save();

        $url = new CpanUrlAmigables();
        $url->setIdioma(0);
        $url->setUrlFriendly("/" . $slug);
        $url->setEntity($entidad);
        $url->setIdEntity($idEntidad);
        $url->setController($controller);
        $url->setAction('Index');
        $url->setTemplate('Index');
        $url->create();

        return $slug;
    }

    /**
     * Vacia las tablas de familias,fabricantes y articulos
     */
    static function VaciarTablas() {

        $tabla = new CpanUrlAmigables();
        $tabla->queryDelete("Controller='Familias' or Controller='Articulos' or Controller='Producto' or Controller='Fabricantes'");
        $tabla = new Familias();
        $tabla->queryDelete("1");
        $tabla = new Fabricantes();
        $tabla->queryDelete("1");
        $tabla = new Articulos();
        $tabla->queryDelete("1");
        $tabla = new CpanRelaciones();
        $tabla->queryDelete("EntidadOrigen='Familias' or EntidadOrigen='Fabricantes' or EntidadOrigen='Articulos'");
        $tabla->queryDelete("EntidadDestino='Familias' or EntidadDestino='Fabricantes' or EntidadDestino='Articulos'");
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
