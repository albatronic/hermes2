<?php

/**
 * Genera una base de datos y usuario en base al esquema
 * indicado en un archivo YAML
 *
 * Carga carga datos en las tablas creadas
 *
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 02-sep-2012 3:48:47
 */
class SchemaBuilder {

    protected $host;
    protected $user;
    protected $password;
    protected $dbEngine;
    protected $database;
    protected $charset;
    protected $collate;
    protected $defaultEngine = 'MyISAM';
    protected $defaultCharSet = 'utf8';
    protected $dropTablesIfExists;
    protected $dbLink = FALSE;
    protected $indices = '';
    protected $sql;
    protected $lastInsertId;
    protected $errores = array();
    protected $log = array();
    protected $nTables = 0;

    public function __construct(array $conection) {
        //print_r($conection);
        $this->host = $conection['host'];
        $this->user = $conection ['user'];
        $this->password = $conection['password'];
        $this->dbEngine = $conection['dbEngine'];
        $this->database = $conection['database'];
        $this->charset = $conection['charset'];
        $this->collate = $conection['collate'];
        $this->dropTablesIfExists = $conection['dropTablesIfExists'];

        define('DB_HOST', $this->host);
        define('DB_USER', $this->user);
        define('DB_PASS', $this->password);
        define('DB_BASE', $this->database);
    }

    /**
     * Crea una base de datos
     *
     * @return boolean TRUE si se ha creado la base de datos
     */
    public function createDataBase() {
        $result = $this->doQuery("CREATE DATABASE {$this->database} CHARACTER SET = {$this->charset} COLLATE = {$this->collate};");
        if (!$result) {
            $result = $this->getErrores();
        } else {
            $result = array("Ok, Data base {$this->database} created");
        }

        return $result;
    }

    /**
     * Borra una base de datos
     *
     * @return boolean TRUE si se ha borrado la base de datos
     */
    public function deleteDataBase() {
        $result = $this->doQuery("DROP DATABASE {$this->database};");
        if (!$result) {
            $result = $this->getErrores();
        } else {
            $result = array("Ok, Data base {$this->database} deleted");
        }

        return $result;
    }

    /**
     * Devuelve array con las bases de datos que
     * hay en el servidor
     * 
     * @return array
     */
    public function showDataBases() {
        $rows = array("Data Bases", "----------------");
        if ($this->connect()) {
            $result = mysql_query("SHOW DATABASES;");
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                array_push($rows, $row['Database']);
            }
        }
        return $rows;
    }

    /**
     * Devuelve array con las tablas que
     * hay en la base de datos
     * 
     * @return array
     */
    public function showTables() {
        $rows = array("Tables in data base {$this->database}", "-----------------------------");
        if ($this->connect()) {
            $result = mysql_query("SHOW TABLES FROM {$this->database};");
            if ($result) {
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    array_push($rows, $row["Tables_in_{$this->database}"]);
                }
            } else {
                $rows = array("Data base {$this->database} not found");
            }
        }
        return $rows;
    }

    /**
     * Crea un usuario en la base de datos
     *
     * Los datos deben venir en el array $newUser('user' => 'el ususario', 'password' => 'la password')
     *
     *
     * @param  array   $newUser Array con el usuario y contraseña
     * @return string 
     */
    public function createUser(array $newUser) {

        $ok = $this->doQuery("CREATE USER '{$newUser['user']}'@'%' IDENTIFIED BY '{$newUser['password']}';");
        if ($ok) {
            $ok = $this->doQuery("GRANT SELECT, INSERT, UPDATE, DELETE ON `{$this->database}`.* TO '{$newUser['user']}'@'%';");
        }
        $result = ($ok) ? array("Ok, user {$newUser['user']} with password {$newUser['password']} created") : $this->getErrores();

        return $result;
    }

    /**
     * Borrar el usuario $user de la base de datos
     * @param string $user
     * @return string
     */
    public function dropUser($user) {

        $ok = $this->doQuery("DROP USER {$user}");
        $result = ($ok) ? array("Ok, User {$user} dropped") : $this->getErrores();

        return $result;
    }

    /**
     * Devuelve array con los usuarios que
     * hay en el servidor
     * @return array
     */
    public function showUsers() {
        $rows = array("User\tHost", "----\t----");
        if ($this->connect()) {
            $result = mysql_query("SELECT user,host FROM mysql.user;");
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                array_push($rows, $row['user'] . "\t" . $row['host']);
            }
        }
        return $rows;
    }

    /**
     * Crea la tabla $name en base al esquema $schema
     *
     * @param  type    $name   El nombre de la tabla
     * @param  array   $schema El array con el esquema de la tabla
     * @return boolean TRUE si la table se creó correctamente
     */
    public function createTable($name, array $schema) {
        $hayColumnaId = FALSE;

        $this->indices = "";
        $columnas = "";

        $engine = isset($schema['engine']) ? $schema['engine'] : $this->defaultEngine;
        $charSet = isset($schema['charSet']) ? $schema['charSet'] : $this->defaultCharSet;
        $comment = isset($schema['comment']) ? "COMMENT '{$schema['comment']}'" : "";

        // Crear sintaxis de las columnas
        if (is_array($schema['columns'])) {
            foreach ($schema['columns'] as $columnName => $attributes) {
                $columnas .= "  " . $this->buildColumn($columnName, $attributes) . ",\n";
                if (isset($attributes['index'])) {
                    $esPrimary = ($attributes['index'] == 'PRIMARY');
                } else {
                    $esPrimary = false;
                }
                $hayColumnaId = ($hayColumnaId OR $columnName == 'Id' OR $esPrimary);
            }

            if (!$hayColumnaId) {
                $columnas = "  `Id` bigint(11) NOT NULL AUTO_INCREMENT,\n" . $columnas;
                $this->indices = "\n  PRIMARY KEY (`Id`)," . $this->indices;
            }
        }
        // Crear índices adicionales
        if (isset($schema['index'])) {
            foreach ($schema['index'] as $indexName => $indexAttributes) {
                $this->indices .= "\n  {$indexAttributes['type']} `{$indexName}` ({$indexAttributes['columns']}),";
            }
        }

        // Quito las coma final de la relación de índices
        $this->indices = substr($this->indices, 0, -1);

        // Crear sintaxis de las relaciones extranjeras
        if (isset($schema['relations'])) {
            foreach ($schema['relations'] as $foreignTable => $attributes) {
                
            }
        }

        if ($this->dropTablesIfExists) {
            $query = "DROP TABLE IF EXISTS `{$this->database}`.`{$name}`;";
            $this->doQuery($query);
        }
        $query = "CREATE TABLE `{$this->database}`.`{$name}` (\n{$columnas}{$this->indices}\n) ENGINE={$engine} DEFAULT CHARSET={$charSet} {$comment};";

        return $this->doQuery($query);
    }

    /**
     * Borra una tabla
     *
     * @return boolean TRUE si se ha borrado la tabla
     */
    public function deleteTable($name) {
        return $this->doQuery("DROP TABLE `{$this->database}`.`{$name}`;");
    }

    /**
     * Vacia una tabla
     *
     * @return boolean TRUE si se ha vaciado la tabla
     */
    public function truncateTable($name) {
        return $this->doQuery("TRUNCATE TABLE `{$this->database}`.`{$name}`;");
    }

    /**
     * Crea las tablas en base al array $schema
     *
     * @param  array   $schema Array con el esquema de la base de datos
     * @return boolean TRUE si se ha construido el esquema
     */
    public function buildTables(array $schema) {

        $array = array("Tables built", "-------------------------");

        if (is_array($schema)) {
            foreach ($schema as $tableName => $tableSchema) {
                if ($this->createTable($tableName, $tableSchema)) {
                    array_push($array, $tableName);
                    $this->nTables ++;
                } else {
                    array_push($array, "ERROR: {$tableName} " . $this->errores[0]);
                }
            }
            array_push($array, "-------------------------");
            array_push($array, "{$this->nTables} tables built");
        } else {
            $this->errores[] = "NO HAY ESQUEMA";
        }

        return $array;
    }

    /**
     * Lee la tablas existentes y genera un archivo yml
     * con el esquema de la base de datos.
     *
     * Este método solo funciona con BDs mysql
     *
     * El archivo generado tiene el nombre de la base de datos
     *
     * @return boolean TRUE si se construyo con éxito
     */
    public function buildSchema() {

        $arrayTablas = array();

        $dblink = mysql_connect($this->host, $this->user, $this->password);
        mysql_select_db($this->database, $dblink);

        $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='" . $this->database . "'";
        $result = mysql_query($query, $dblink);
        while ($row = mysql_fetch_array($result)) {
            $entity = new EntityBuilder($row['TABLE_NAME']);
            $arrayTabla = $entity->getSchema();
            $arrayTablas[$row['TABLE_NAME']] = $arrayTabla[$row['TABLE_NAME']];
        }
        unset($entity);

        $yml = "# ESQUEMA DE LA BD " . $this->database . "\n\n";
        $yml .= sfYaml::dump($arrayTablas, 2);

        $archivo = new Archivo($this->database . ".yml");
        $ok = $archivo->write($yml);
        unset($archivo);

        return $ok;
    }

    /**
     * Borra físicamente los registros de todas las tablas
     * que estén marcados como Deleted='1'
     */
    public function clearTables() {
        $dblink = mysql_connect($this->host, $this->user, $this->password);
        mysql_select_db($this->database, $dblink);

        $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='" . $this->database . "'";
        $result = mysql_query($query, $dblink);
        while ($row = mysql_fetch_array($result)) {
            $query = "DELETE FROM {$row['TABLE_NAME']} WHERE Deleted='1';";
            $this->log[] = $query;
            $this->doQuery($query);
        }
    }

    /**
     * Carga datos en las tablas en base al array $fixtures
     *
     * La tabla puede ser vaciada o no antes de la carga, dependiendo
     * del valor del parámetro $truncateTables.
     *
     * Pone en $this->errores[] los posibles errores y además
     * la estadística de la tablas creadas y filas insertadas
     *
     * @param  array   $fixtures       Array con los datos a cargar
     * @param  boolean $truncateTables Si TRUE, vacía la tabla antes de cargarle datos
     * @return boolean TRUE si la carga se hizo correctamente
     */
    public function loadFixtures(array $fixtures, $truncateTables = FALSE) {

        $array = array();

        if (is_array($fixtures)) {
            foreach ($fixtures as $entidad => $rows) {
                // Cada Tabla
                $obj = new $entidad();

                $nRowsOk = $nRowsKo = 0;
                if ($truncateTables) {
                    $obj->truncate();
                }

                foreach ($rows as $row) {
                    // Cada Fila
                    $obj = new $entidad();
                    $obj->bind($row);//∫if($entidad=='Modulos') print_r($obj);
                    $obj->create();
                    ($obj->getPrimaryKeyValue() > 0) ? $nRowsOk ++ : $nRowsKo ++;
                }

                array_push($array, "Entidad {$entidad} ({$obj->getDataBaseName()}.{$obj->getTableName()}), {$nRowsOk} rows inserted Ok, {$nRowsKo} failed");
            }
        } else {
            array_push($array, "Fixtures empty!!!");
        }

        return $array;
    }

    /**
     * Inserta el registro  $row en la tabla $table
     * y pone valores por defecto
     *
     * @param  string  $table El nombre de la tabla
     * @param  array   $row   Array correspondiente a una fila ('columnName' => 'Value')
     * @return boolean TRUE si se insertó correctamente
     */
    private function insertRow($table, array $row) {
        $columns = '';
        $values = '';

        foreach ($row as $column => $value) {
            $columns .= "`{$column}`,";
            $values .= "'" . addslashes($value) . "',";
            //$values .= "'" . mysql_real_escape_string($value) . "',";
        }
        // Añado valores por defecto
        $columns .= "`CreatedBy`,`CreatedAt`,`PrimaryKeyMD5`";
        $values .= "'1','" . date('Y-m-d H:i:s') . "','" . uniqid("", true) . "'";

        $query = "INSERT INTO `{$this->database}`.`{$table}` ({$columns}) VALUES ({$values});";
        //echo $query,"\n";
        $ok = $this->doQuery($query);

        /**
         * Despues de insertar actualizo algunas columnas
         * Se si ha indicado 'SortOrder' lo respeto
          if ($ok) {
          $orden = (isset($row['SortOrder'])) ? $row['SortOrder'] : $this->lastInsertId;
          $updates = "`PrimaryKeyMD5` = '" . md5($this->lastInsertId) . "', `SortOrder` = '" . $orden . "'";
          $query = "UPDATE `{$this->database}`.`{$table}` SET {$updates} WHERE `{$primaryKeyColumn}` = '{$this->lastInsertId}';";
          $ok = $this->doQuery($query);
          }
         */
        return $ok;
    }

    /**
     * Devuelve la sintaxis SQL de definición de una columna
     *
     * Hace conversiones de tipos de datos
     *
     * @param  string $name       Nombre de la columna
     * @param  array  $attributes Array con los atributos de la columna
     * @return string La sintaxis de una columna
     */
    private function buildColumn($name, array $attributes) {
        // VALIDAR LOS TIPOS DE DATOS. AQUI HAY QUE ACTUAR
        // DE UNA FORMA U OTRA DEPENDIENDO DEL TIPO DE BASE DE DATOS
        $arrayTipo = explode("(", $attributes['type']);
        $tipo = strtoupper($arrayTipo[0]);
        $precision = (isset($arrayTipo[1])) ? str_replace(")", "", $arrayTipo[1]) : 0;

        switch ($tipo) {
            case 'TEXT':
                $tipo = 'TEXT';
                break;
            case 'INT':
            case 'INTEGER':
                if (!$precision)
                    $precision = '4';
                $tipo = "INTEGER({$precision})";
                break;
            case 'BIGINT':
                if (!$precision)
                    $precision = '4';
                $tipo = "BIGINT({$precision})";
                break;
            case 'DECIMAL':
                if (!$precision)
                    $precision = '2';
                $tipo = "DECIMAL(10,{$precision})";
                break;
            case 'CHAR':
                if (!$precision)
                    $precision = '1';
                $tipo = "CHAR({$precision})";
                break;
            case 'VARCHAR':
            case 'STRING':
                if (!$precision)
                    $precision = '255';
                $tipo = "VARCHAR({$precision})";
                break;
            case 'TINYINT':
                if (!$precision)
                    $precision = '1';
                $tipo = "TINYINT({$precision})";
                break;
            case 'FLOAT':
                $tipo = "FLOAT";
                break;
            case 'BOOLEAN':
                $tipo = "TINYINT(1)";
                break;
            case 'TIMESTAMP':
                $tipo = "TIMESTAMP";
                break;
            case 'DATETIME':
                $tipo = 'DATETIME';
                break;
            case 'DATE':
                $tipo = "DATE";
                break;
            case 'TIME':
                $tipo = "TIME";
                break;
            default:
                $tipo = "** TIPO NO RECONOCIDO EN LA COLUMNA {$name}: " . $tipo . " " . $precision;
        }

        $column = "`{$name}` {$tipo}";

        if (isset($attributes['notnull'])) {
            $column .= " NOT NULL";
        }

        if (isset($attributes['default'])) {
            $column .= " DEFAULT \"{$attributes['default']}\"";
        }

        if (isset($attributes['autoIncrement'])) {
            $column .= " AUTO_INCREMENT";
        }

        if (isset($attributes['comment'])) {
            $column .= " COMMENT '{$attributes['comment']}'";
        }

        if (isset($attributes['index'])) {
            if (strtoupper($attributes['index']) == 'PRIMARY')
                $this->indices .= "\n  PRIMARY KEY (`{$name}`),";
            else
                $this->indices .= "\n  {$attributes['index']} `{$name}` (`{$name}`),";
        }

        return $column;
    }

    /**
     * Realiza la conexión a la BD y activa $this->dbLink
     *
     * @return boolean TRUE si la conexión se ha realizado
     */
    private function connect() {

        switch ($this->dbEngine) {
            case 'mysql':
                $this->dbLink = @mysql_connect($this->host, $this->user, $this->password);
                if (!$this->dbLink)
                    $this->errores[] = "ERROR DE CONEXION: " . mysql_errno() . " " . mysql_error();
                break;
            case 'postgres':
                $conexion = "host={$this->host} dbname={$this->database} user={$this->user} password={$this->password}";
                $this->dbLink = pg_connect($conexion);
                if (!$this->dbLink)
                    $this->errores[] = "ERROR DE CONEXION: " . pg_errormessage();
                break;
        }

        return $this->dbLink;
    }

    /**
     * Cierra la conexión a la BD
     */
    private function close() {
        if ($this->dbLink) {
            switch ($this->dbEngine) {
                case 'mysql':
                    mysql_close($this->dbLink);
                    break;
                case 'postgres':
                    pg_close($this->dbLink);
                    break;
            }
        }
    }

    /**
     * Ejecuta la sentencia SQL $query
     *
     * Pone en $this->sql la sentencia ejecutada
     *
     * @param  string  $query Sentencia SQL
     * @return boolean TRUE si se ejecutró con éxito
     */
    private function doQuery($query) {
        $ok = false;

        $this->sql .= $query . "\n\n";

        if ($this->connect()) {
            $ok = mysql_query($query, $this->dbLink);

            if ($ok)
                $this->lastInsertId = mysql_insert_id($this->dbLink);
            else
                $this->errores[] = "ERROR QUERY: " . mysql_errno($this->dbLink) . " " . mysql_error($this->dbLink) . " " . $query;

            $this->close();
        }

        return $ok;
    }

    /**
     * Devuelve array con los nombres de las tablas
     * 
     * @return array
     */
    public function getTableNames() {

        $rows = array();

        if ($this->connect()) {
            $result = mysql_query("SHOW TABLES FROM {$this->database};");
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                array_push($rows, $row["Tables_in_{$this->database}"]);
            }
            $this->close();
        }

        return $rows;
    }

    /**
     * Devuelve el array con los mensajes de error
     *
     * @return array Array con los errores
     */
    public function getErrores() {
        return $this->errores;
    }

    /**
     * Devuelve el array con los mensajes log
     *
     * @return array Array con los texto logs
     */
    public function getLog() {
        return $this->log;
    }

    /**
     * Devuelve las sentencias SQL generadas durante todo el proceso
     *
     * @return string Sentencia SQL
     */
    public function getSql() {
        return $this->sql;
    }

}
