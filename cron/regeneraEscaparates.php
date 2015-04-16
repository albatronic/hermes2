<?php

/*
 * REGENERA LOS ESCAPARATES DE LA EMPRESA INDICADA
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 26.06.2014
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
define("APP_PATH", str_replace("cron", "", __DIR__));
include_once "../" . $app['framework'] . "Autoloader.class.php";
Autoloader::setCacheFilePath(APP_PATH . 'tmp/class_path_cache.txt');
Autoloader::excludeFolderNamesMatchingRegex('/^CVS|\..*$/');
Autoloader::setClassPaths(array(
    '../' . $app['framework'],
    '../entities/',
    '../lib/',
));
spl_autoload_register(array('Autoloader', 'loadClass'));

class regeneraEscaparate {

    static $fpLog = null;
    
    static function regenera($idApp) {
        // Guardo la eventual sesion
        $sesion = self::getSession();
        
        foreach (self::getProyectos($idApp) as $proyecto) {
            self::setSession($proyecto);

            $escaparates = self::getEscaparates();
            self::escribeLog($proyecto['Database'],count($escaparates));
            
            foreach ($escaparates as $row) {
                self::regeneraOrdenes($row['Id']);
            }
        }
        
        // Restablezco la sesion que hubiese
        self::setSession($sesion);
    }

    static function getProyectos($idApp) {

        $proyectos = new PcaeProyectosApps();
        $rows = $proyectos->cargaCondicion("*", "IdApp={$idApp}", "Id ASC");
        unset($proyectos);

        return $rows;
    }

    static function getEscaparates() {

        $escaparate = new Escaparates();
        $rows = $escaparate->cargaCondicion("Id");
        unset($escaparate);

        return $rows;
    }

    static function regeneraOrdenes($idEscaparate) {
        $escaparate = new Escaparates($idEscaparate);
        $ordenes = new OrdenesArticulos();
        $ordenes->borraOrdenesRegla($idEscaparate);
        unset($ordenes);
        $escaparate->aplicaRegla();
        unset($escaparate);
    }

    static function setSession($proyecto) {
        $_SESSION['project']['conection'] = array(
            'dbEngine' => $proyecto['DbEngine'],
            'host' => $proyecto['Host'],
            'user' => $proyecto['User'],
            'password' => $proyecto['Password'],
            'database' => $proyecto['Database'],
        );
    }

    static function getSession() {
        return $_SESSION['project']['conection'];
    }
    
    static function escribeLog($dataBase,$nEscaparates) {
        
        if (self::$fpLog == NULL) {
            $fp = fopen("../log/escaparates.log", "a");
        }
        if ($fp) {
            fwrite($fp, date('Y-m-d H:i:s')."\t".$dataBase."\t".$nEscaparates."\n");
        }
    }
}

$idAppHermes = 2;

regeneraEscaparate::regenera($idAppHermes);
