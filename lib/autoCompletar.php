<?php

/**
 * UTILIDADES DE AUTOCOMPLETAR.
 *
 * ESTE SCRIPT ES LLAMADO POR LAS FUNCIONES AJAX.
 * DEVUELVE UN STRING CON CODIGO HTML QUE SERÁ UTILIZADO
 * PARA REPOBLAR EL TAG HTML QUE CORRESPONDA
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
 */
session_start();

if (!file_exists('../config/config.yml')) {
    echo "NO EXISTE EL FICHERO DE CONFIGURACION";
    exit;
}

if (file_exists("../bin/yaml/lib/sfYaml.php")) {
    include "../bin/yaml/lib/sfYaml.php";
} else {
    echo "NO EXISTE LA CLASE PARA LEER ARCHIVOS YAML";
    exit;
}

// ---------------------------------------------------------------
// CARGO LOS PARAMETROS DE CONFIGURACION.
// ---------------------------------------------------------------
$config = sfYaml::load('../config/config.yml');
$app = $config['config']['app'];

// ---------------------------------------------------------------
// ACTIVAR EL AUTOLOADER DE CLASES Y FICHEROS A INCLUIR
// ---------------------------------------------------------------
define("APP_PATH", $_SERVER['DOCUMENT_ROOT'] . $app['path'] . "/");
include_once "../" . $app['framework'] . "Autoloader.class.php";
Autoloader::setCacheFilePath(APP_PATH . 'tmp/class_path_cache.txt');
Autoloader::excludeFolderNamesMatchingRegex('/^CVS|\..*$/');
Autoloader::setClassPaths(array(
    '../' . $app['framework'],
    '../entities/',
    '../lib/',
));
spl_autoload_register(array('Autoloader', 'loadClass'));

switch ($_GET['entidad']) {

    // BUSCA CLIENTES DE LA SUCURSAL EN CURSO POR %RAZONSOCIAL% y $NOMBRECOMERCIAL%
    case 'clientes':
        $cliente = new Clientes();
        $rows = $cliente->fetchClientesSucursal($_GET['idSucursal'], $_GET['term']);
        unset($cliente);
        break;

    // BUSCA PROVEEDORES POR %RAZONSOCIAL%
    case 'proveedores':
        $proveedor = new Proveedores();
        $rows = $proveedor->cargaCondicion("IDProveedor as Id, RazonSocial as Value", "RazonSocial LIKE '%{$_GET['term']}%'", "RazonSocial");
        unset($proveedor);
        break;

    // BUSCA ARTICULOS POR %CODIGO%, %DESCRIPCION% Y %CODIGOEAN%
    case 'articulos':
        $articulo = new Articulos();
        
        if ($_GET['idSucursal'] == 'simples')
            // Muestra solo los artículos simples (no compuestos)
            $filtro = "(AllowsChildren='0') and ";
        
        $filtro .= "(Vigente='1') and ((Codigo LIKE '%{$_GET['term']}%') or (Descripcion LIKE '%{$_GET['term']}%') or (CodigoEAN LIKE '%{$_GET['term']}%'))";
        $rows = $articulo->cargaCondicion("IDArticulo as Id, Descripcion as Value", $filtro, "Descripcion");
        unset($articulo);
        break;

    // BUSCA ARTICULOS DE LA FAMILIA INDICADA POR %CODIGO%, %DESCRIPCION% Y %CODIGOEAN%
    // EN ESTE CASO UTILIZA LA VARIABLE 'idSucursal' COMO LA FAMILIA
    case 'articulosFamilia':
        $articulo = new Articulos();
        if ($_GET['idSucursal'] == '0')
            $filtro = '1';
        else
            $filtro = "IDFamilia='{$_GET['idSucursal']}'";

        $filtro .= " and (Vigente='1') and ((Codigo LIKE '%{$_GET['term']}%') or (Descripcion LIKE '%{$_GET['term']}%') or (CodigoEAN LIKE '%{$_GET['term']}%'))";
        $rows = $articulo->cargaCondicion("IDArticulo as Id, Codigo, Descripcion as Value", $filtro, "Descripcion");
        unset($articulo);
        break;

    // BUSCA LAS UBICACIONES DE UN LOTE EN UN ALMACEN
    // EN $_GET['idSucursal'] VIENE SEPARADOS POR @ EL ID DEL ALMACEN Y EL ID DEL LOTE RESPECTIVAMENTE.
    case 'ubicacionesLote':
        $valores = explode("@", $_GET['idSucursal']);
        $idAlmacen = $valores[0];
        $idLote = $valores[1];

        $lote = new Lotes($idLote);
        $rows = $lote->getUbicaciones($idAlmacen, "%{$_GET['term']}%");
        unset($lote);
        break;

    // BUSCA LAS UBICACIONES DE UN ARTICULO EN UN ALMACEN
    // MUESTRA PRIMERO AQUELLAS DONDE HAY STOCK Y DESPUES
    // MUESTRA EL RESTO DE UBICACIONES DEL ALMACEN
    // EN $_GET['idSucursal'] VIENE SEPARADOS POR @ EL ID DEL ALMACEN Y EL ID DEL ARTICULO RESPECTIVAMENTE.
    case 'ubicacionesAlmacenArticulo':

        $valores = explode("@", $_GET['idSucursal']);
        $idAlmacen = $valores[0];
        $idArticulo = $valores[1];

        // Busca las ubicaciones con existencias
        $articulo = new Articulos($idArticulo);
        $rows = $articulo->getUbicaciones($idAlmacen, "%{$_GET['term']}%");
        unset($articulo);
        break;

    // DEVUELVE LAS UBICACIONES DE UN ALMACEN
    // EN ESTE CASO UTILIZA LA VARIABLE 'idSucursal' COMO EL ID DE ALMACEN
    case 'ubicacionesAlmacen':
        $idAlmacen = $_GET['idSucursal'];

        $almacen = new Almacenes($idAlmacen);
        $rows = $almacen->getUbicaciones("%{$_GET['term']}%");
        unset($almacen);
        break;

    // DEVUELVE LOS LOTES DEL ARTICULO INDEPENDIENTEMENTE
    // DEL ALMACEN Y DE SUS EXISTENCIAS. O SEA, TODOS LOS
    // LOTES QUE SE HAN DEFINIDO PARA EL ARTÍCULO.
    case 'lotesArticulo':
        $idArticulo = $_GET['idSucursal'];

        $lotes = new Lotes();
        $rows = $lotes->fetchAll($idArticulo, 'Lote', "%{$_GET['term']}%");
        unset($lotes);
        break;

    // BUSCA PAISES POR %Pais%
    case 'paises':
        $pais = new Paises();
        $rows = $pais->cargaCondicion("IDPais as Id, Pais as Value", "Pais LIKE '%{$_GET['term']}%'", "Pais");
        unset($pais);
        break;

    // BUSCA PROVINCIAS POR %Provincia%
    case 'provincias':
        $filtro = "Provincia LIKE '%{$_GET['term']}%'";
        if ($_GET['filtroAdicional'])
            $filtro .= " and IDPais='{$_GET['filtroAdicional']}'";

        $provincia = new Provincias();
        $rows = $provincia->cargaCondicion("IDProvincia as Id, Provincia as Value", $filtro, "Provincia");
        unset($provincia);
        break;

    // BUSCA MUNICIPIOS POR %Municipio%
    case 'municipios':
        $filtro = "Municipio LIKE '%{$_GET['term']}%'";
        if ($_GET['filtroAdicional'])
            $filtro .= " and IDProvincia='{$_GET['filtroAdicional']}'";
        $municipio = new Municipios();
        $rows = $municipio->cargaCondicion("IDMunicipio as Id, Municipio as Value", $filtro, "Municipio");
        unset($municipio);
        break;

    // BUSCA MONEDAS POR %Moneda%
    case 'monedas':
        $filtro = "Moneda LIKE '%{$_GET['term']}%'";
        $moneda = new CommMonedas();
        $rows = $moneda->cargaCondicion("Id as Id, Moneda as Value", $filtro, "Moneda");
        unset($moneda);
        break;

    // BUSCA ZONAS HORARIAS POR %zonaHoraria%
    case 'zonasHorarias':
        $filtro = "Zona LIKE '%{$_GET['term']}%'";
        $zona = new CommZonasHorarias();
        $rows = $zona->cargaCondicion("Id as Id, Zona as Value", $filtro, "Zona");
        unset($zona);
        break;

    // BUSCA CNAES POR %actividad% Y %codigo%
    // Devuelve solo las que su código es de 5 digitos
    case 'cnae':
        $filtro = "(Actividad LIKE '%{$_GET['term']}%' or Codigo LIKE '%{$_GET['term']}%') AND LENGTH(Codigo)=5";
        $cnae = new CommCnae();
        $rows = $cnae->cargaCondicion("Id as Id, concat(Actividad,' (',Codigo,')') as Value", $filtro, "Actividad");
        unset($cnae);
        break;

    // BUSCA OFICINAS BANCARIAS POR %codigo% y %direccion%
    case 'oficinasBancarias':
        $filtro = "(Codigo LIKE '%{$_GET['term']}%' OR Direccion LIKE '%{$_GET['term']}%')";
        if ($_GET['filtroAdicional'])
            $filtro .= " and IdBanco='{$_GET['filtroAdicional']}'";
        $oficina = new CommBancosOficinas();
        $rows = $oficina->cargaCondicion("Id as Id, CONCAT(Codigo,'-',Direccion) as Value", $filtro, "Codigo,Direccion");
        unset($oficina);
        break;
        
    case 'categorias':
        $familia = new Familias();
        $filtro = "(NivelJerarquico='1') and Familia like '%{$_GET['term']}%'";
        $rows = $familia->cargaCondicion("IDFamilia as Id, Familia as Value", $filtro, "Familia ASC");
        unset($familia);
        break;
    
    case 'familias':
        $familia = new Familias();
        $filtro = "(NivelJerarquico='2') and BelongsTo='{$_GET['filtroAdicional']}' and Familia like '%{$_GET['term']}%'";
        $rows = $familia->cargaCondicion("IDFamilia as Id, Familia as Value", $filtro, "Familia ASC");
        unset($familia);      
        break;

    case 'subfamilias':
        $familia = new Familias();
        $filtro = "(NivelJerarquico='3') and BelongsTo='{$_GET['filtroAdicional']}' and Familia like '%{$_GET['term']}%'";
        $rows = $familia->cargaCondicion("IDFamilia as Id, Familia as Value", $filtro, "Familia ASC");
        unset($familia);
        break;    
}

// Creo el array de obetos que se va a devolver
// El compo value se codifica en utf8 porque se supone que van caracteres
$arrayElementos = array();
foreach ($rows as $value) {
    array_push($arrayElementos, array('id'=>$value["Id"], 'value'=>$value["Value"]));
}

// El array creado se devuelve en formato JSON, requerido asi
// por el autocomplete de jQuery
print_r(json_encode($arrayElementos));
?>
