<?php

/**
 * Description of Fab
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @date 12-08-2014
 */
class Fab {

    static $conectionDB = array();
    static $comandos = array(
        'help' => 'Show this help (lo que viene siendo la ayuda)',
        'db_showconection' => 'Show the conection to be use for all the operations',
        'db_create' => 'Create an empty data base',
        'db_drop' => 'Drop the data base',
        'db_showdatabases' => 'List the data bases',
        'db_showtables' => 'List the tables in the data base',
        'db_createuser' => 'Create an user on the data base',
        'db_dropuser' => 'Drop an user',
        'db_showusers' => 'List the users',
        'db_make' => 'Drop and Create data base and its tables [schema.yml]',
        'db_populate' => 'Truncate and populate tables with the fixtures [fixtures.yml]',
        'db_makeall' => 'Drop, create and populate data base with schema.yml and fixtures.yml',
        'app_builder' => 'Create all entities and modules',
        'app_createmodule' => 'Create a full module (entities, controller and templates)',
        'app_createentities' => 'Create all entities (methods and models)',
        'app_createentity' => 'Create a data entity'
    );

    static function version() {
        echo "\n";
        echo "AppBuilder v1.0\n";
        echo "ALBATRONIC\n";
        echo "\n";
    }

    static function usage() {
        self::version();
        echo "Use: fab.sh <comand> <parameters>\n\n";
        echo "For help: fab.sh ?\n\n";
    }

    static function help($comando = '', $explicacion = '') {

        self::version();

        if (isset(self::$comandos[$comando])) {
            echo self::$comandos[$comando], "\n";
            echo $explicacion . "\n";
        } else {
            foreach (self::$comandos as $key => $value) {
                echo str_pad($key, 20) . "\t{$value}\n";
            }
            echo "\n";
        }
    }

    static function interpreta($argv) {

        switch ($argv[1]) {
            case 'version':
            case 'v':
            case '-v':
                self::version();
                break;

            case 'help':
            case '?':
            case '-help':
            case '-h':
                $comando = (isset($argv[2])) ? $argv[2] : "";
                self::help($comando);
                break;

            case 'db_showconection':
                $result = self::showConection();
                self::printResult($result);
                break;

            case 'db_create':
                $result = self::createDb();
                self::printResult($result);
                break;

            case 'db_drop':
                $result = self::dropDb();
                self::printResult($result);
                break;

            case 'db_showdatabases':
                $result = self::showDataBases();
                self::printResult($result);
                break;

            case 'db_showtables':
                $result = self::showTables();
                self::printResult($result);
                break;

            case 'db_createuser':
                if (!isset($argv[2]) || !isset($argv[3])) {
                    self::help($argv[1], "User or password missing. Use: fab.php {$argv[1]} <user> <password>");
                } else {
                    $result = self::createUser($argv[2], $argv[3]);
                    self::printResult($result);
                }
                break;

            case 'db_dropuser':
                if (!isset($argv[2])) {
                    self::help($argv[1], "User missing. Use: fab.php {$argv[1]} <user>");
                } else {
                    $result = self::dropUser($argv[2]);
                    self::printResult($result);
                }
                break;

            case 'db_showusers':
                $result = self::showUsers();
                self::printResult($result);
                break;

            case 'db_make':
                if (!isset($argv[2])) {
                    //self::help($argv[1], "Yml schema missing. Use: fab.php {$argv[1]} <schema.yml>");
                    $argv[2] = "schema.yml";
                }
                $result = self::dropDb();
                self::printResult($result);                
                $result1 = self::makeDb($argv[2]);
                self::printResult($result1);
                break;

            case 'db_populate':
                if (!isset($argv[2])) {
                    //self::help($argv[1], "Fixtures missing. Use: fab.php {$argv[1]} <fixtures.yml>");
                    $argv[2] = "fixtures.yml";
                }
                $result = self::populateDb($argv[2]);
                self::printResult($result);
                break;

            case 'db_makeall':
                if (!isset($argv[2])) {
                    $argv[2] = "schema.yml";
                }
                if (!isset($argv[3])) {
                    $argv[3] = "fixtures.yml";
                }
                $result = self::dropDb();
                self::printResult($result);                 
                $result1 = self::makeDb($argv[2]);
                self::printResult($result1);
                $result2 = self::populateDb($argv[3]);
                self::printResult($result2);
                break;
                
            case 'app_builder':
                $prefijo = isset($argv[2]) ? $argv[2] : "";
                $result = self::appBuilder($prefijo);
                self::printResult($result);
                break;

            case 'app_createmodule':
                if ($argv[2] == '') {
                    self::help($argv[1], "Module name missing. Use: fab.php {$argv[1]} <moduleName> [<prefijo>]");
                } else {
                    $prefijo = isset($argv[3]) ? $argv[3] : "";
                    $result = self::appCreateModule($argv[2], $prefijo);
                    self::printResult($result);
                }
                break;

            case 'app_createentities':
                $prefijo = isset($argv[2]) ? $argv[2] : "";
                $result = self::appCreateEntities($prefijo);
                self::printResult($result);
                break;

            case 'app_createentity':
                if ($argv[2] == '') {
                    self::help($argv[1], "Entity name missing. Use: fab.php {$argv[1]} <entityName> [<prefijo>]");
                } else {
                    $prefijo = isset($argv[3]) ? $argv[3] : "";
                    $result = self::appCreateEntity($argv[2], $prefijo);
                    self::printResult($result);
                }
                break;

            default:
                self::help();
        }
    }

    static function showConection() {

        $result = array("Conection Parametres", "----------------------------------");

        self::getConection();
        foreach (self::$conectionDB as $key => $value) {
            array_push($result, str_pad($key, 20) . "\t{$value}");
        }

        return $result;
    }

    static function createDb() {

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);
        $result = $sb->createDataBase();

        return $result;
    }

    static function dropDb() {

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);
        $result = $sb->deleteDataBase();

        return $result;
    }

    static function showDataBases() {

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);
        $result = $sb->showDataBases();

        return $result;
    }

    static function showTables() {

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);
        $rows = array("Tables in data base " . self::$conectionDB['database'], "-----------------------------");
        $result = $sb->showTables();

        return $result;
    }

    static function createUser($user, $password) {

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);
        $result = $sb->createUser(array('user' => $user, 'password' => $password));

        return $result;
    }

    static function dropUser($user) {

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);
        $result = $sb->dropUser($user);

        return $result;
    }

    static function showUsers() {

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);
        $result = $sb->showUsers();

        return $result;
    }

    static function makeDb($schemaFile) {

        $schema = sfYaml::load("../../entities/schema/{$schemaFile}");

        if (is_array($schema)) {
            self::getConection();
            $sb = new SchemaBuilder(self::$conectionDB);
            $result = $sb->createDataBase();
            if (substr($result[0], 0, 2) == 'Ok') {
                $result1 = $sb->buildTables($schema);
                foreach ($result1 as $item) {
                    array_push($result, $item);
                }
            }
        } else {
            $result = array("Schema file do not exists or is empty");
        }

        return $result;
    }

    static function populateDb($fixturesFile, $truncateTables = true) {

        $fixtures = sfYaml::load("../../entities/schema/{$fixturesFile}");

        if (is_array($fixtures)) {
            self::getConection();
            $sb = new SchemaBuilder(self::$conectionDB);
            $result = $sb->loadFixtures($fixtures, $truncateTables);
        } else {
            $result = array("Fixtures file do not exists or is empty");
        }

        return $result;
    }

    static function appBuilder($prefijo = '') {

        $result = array();
        $prefijo = strtolower($prefijo);

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);

        // Crear el modelo, el controlador, config y los templates para todas las tablas
        $tables = $sb->getTableNames();
        foreach ($tables as $key => $tableName) {
            // Crear módulo completo
            $tableName = strtolower($tableName);
            echo "Creating Module " . ucfirst(str_replace($prefijo, "", $tableName)) . " ...\n";
            $resultado = self::appCreateModule($tableName, $prefijo);
            foreach ($resultado as $key1 => $value) {
                array_push($result, $value);
            }
        }

        return $result;
    }

    static function appCreateEntities($prefijo) {

        $result = array();
        $prefijo = strtolower($prefijo);

        self::getConection();

        $sb = new SchemaBuilder(self::$conectionDB);

        // Crear el modelo, el controlador, config y los templates para todas las tablas
        $tables = $sb->getTableNames();
        foreach ($tables as $key => $tableName) {
            $tableName = strtolower($tableName);
            $resultado = self::appCreateEntity($tableName, $prefijo);
            foreach ($resultado as $key => $value) {
                array_push($result, $value);
            }
        }

        return $result;
    }

    static function appCreateModule($tableName, $prefijo) {

        $result = array();

        // Modelo
        $resultado = self::appCreateEntity($tableName, $prefijo);
        foreach ($resultado as $key => $value) {
            array_push($result, $value);
        }
        // Controller
        $resultado = self::appCreateController($tableName, $prefijo);
        foreach ($resultado as $key => $value) {
            array_push($result, $value);
        }
        // Config
        $resultado = self::appCreateConfig($tableName, $prefijo);
        foreach ($resultado as $key => $value) {
            array_push($result, $value);
        }
        // Listado
        $resultado = self::appCreateListado($tableName, $prefijo);
        foreach ($resultado as $key => $value) {
            array_push($result, $value);
        }
        // Templates 
        $resultado = self::appCreateTemplates($tableName, $prefijo);
        foreach ($resultado as $key => $value) {
            array_push($result, $value);
        }

        return $result;
    }

    /**
     * Crear los archivos de clases para el modelo de datos
     * 
     * @param type $entityName
     * @param type $prefijo
     * @return array
     */
    static function appCreateEntity($entityName, $prefijo = '') {

        self::getConection();

        $entityBuilder = new EntityBuilder(self::$conectionDB, $entityName, $prefijo);

        $entityFile = ucfirst(str_replace($prefijo, "", $entityName));

        $model = $entityBuilder->GetModel();
        $fileModel = "../../entities/models/{$entityFile}Entity.class.php";

        $method = $entityBuilder->GetMethod();
        $fileMethod = "../../entities/methods/{$entityFile}.class.php";

        $okModel = self::createArchive($fileModel, $model);
        $okMethod = self::createArchive($fileMethod, $method);

        $result = array();
        ($okModel) ? array_push($result, "Ok, {$fileModel} created") : array_push($result, "ERROR creating {$fileModel}");
        ($okMethod) ? array_push($result, "Ok, {$fileMethod} created") : array_push($result, "ERROR creating {$fileMethod}");

        return $result;
    }

    /**
     * Crea el archivo del controlador
     * 
     * @param type $entityName
     * @param type $prefijo
     * @return array
     */
    static function appCreateController($entityName, $prefijo = '') {

        $controller = ControllerBuilder::getController($entityName, $prefijo);
        $entityFile = ucfirst(str_replace($prefijo, "", $entityName));
        $fileController = "../../modules/{$entityFile}/{$entityFile}Controller.class.php";

        $result = array();
        $ok = self::createArchive($fileController, $controller);
        ($ok) ? array_push($result, "Ok, {$fileController} created") : array_push($result, "ERROR creating {$fileController}");

        return $result;
    }

    /**
     * Crea el archivo de configuracion del módulo
     * 
     * @param type $entityName
     * @param type $prefijo
     * @return array
     */
    static function appCreateConfig($entityName, $prefijo = '') {

        $config = new ConfigYmlBuilder(self::$conectionDB, $entityName, $prefijo);
        $configFile = ucfirst(str_replace($prefijo, "", $entityName));
        $fileConfig = "../../modules/{$configFile}/config.yml";

        $result = array();
        $ok = self::createArchive($fileConfig, $config->getConfigYml());
        ($ok) ? array_push($result, "Ok, {$fileConfig} created") : array_push($result, "ERROR creating {$fileConfig}");

        return $result;
    }

    /**
     * Crea el archivo de configuracion del listado
     * 
     * @param type $entityName
     * @param type $prefijo
     * @return array
     */
    static function appCreateListado($entityName, $prefijo = '') {

        $config = new ListadoYmlBuilder(self::$conectionDB, $entityName, $prefijo);
        $configFile = ucfirst(str_replace($prefijo, "", $entityName));
        $fileConfig = "../../modules/{$configFile}/listados.yml";

        $result = array();
        $ok = self::createArchive($fileConfig, $config->getListadoYml());
        ($ok) ? array_push($result, "Ok, {$fileConfig} created") : array_push($result, "ERROR creating {$fileConfig}");

        return $result;
    }

    /**
     * Crea los archivos html con los templates
     * 
     * @param type $entityName
     * @param type $prefijo
     * @return array
     */
    static function appCreateTemplates($entityName, $prefijo = '') {

        $template = new TemplateBuilder(self::$conectionDB, $entityName, $prefijo);
        $configFile = ucfirst(str_replace($prefijo, "", $entityName));
        $folder = "../../modules/{$configFile}";

        $result = array();
        foreach ($template->GetTemplates() as $key => $html) {
            $fileTemplate = "{$folder}/{$key}.html.twig";
            $ok = self::createArchive($fileTemplate, $html);
            ($ok) ? array_push($result, "Ok, {$fileTemplate} created") : array_push($result, "ERROR creating {$fileTemplate}");
        }

        return $result;
    }

    static function printResult($result) {
        foreach ($result as $item) {
            echo $item . "\n";
        }
    }

    static function createArchive($filename, $content) {

        $pathInfo = pathinfo($filename);

        if (file_exists($filename)) {
            rename($filename, $filename . "_");
        } else {
            if (!file_exists($pathInfo['dirname'])) {
                mkdir($pathInfo['dirname']);
            }
        }

        $fp = @fopen($filename, "w");
        if ($fp) {
            fwrite($fp, $content);
            fclose($fp);
            $ok = true;
        } else {
            $ok = false;
        }

        return $ok;
    }

    static function getConection() {

        $config = sfYaml::load("../../config/config.yml");
        $conections = $config['config']['conections'];
        list($key, $conection) = each($conections);

        if ($conection['charset'] == '') {
            $conection['charset'] = 'utf8';
        }

        if ($conection['collate'] == '') {
            $conection['collate'] = 'utf8_general_ci';
        }

        //print_r($conection);
        self::$conectionDB = $conection;
        self::$conectionDB['dropTablesIfExists'] = true;
    }

}
