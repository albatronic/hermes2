<?php

/**
 * CLASE QUE IMPLEMENTA LA CAPA DE ACCESO A DATOS.
 * LOS PARAMETROS NECESARIOS PARA LA CONEXION SE DEFINEN EN EL ARCHIVO
 * config/config.xml O EN EL INDICADO EN EL SEGUNDO PARAMETRO DEL CONSTRUCTOR
 *
 * SE ADMITEN DIFERENTES MOTORES DE BASE DE DATOS. ACTUALMENTE ESTAN
 * IMPLEMENTADOS PARA MYSQL, MSSQL E INTERBASE.
 *
 * SI LA CONEXION ES EXITOSA self::$dbLinkInstance TENDRA VALOR,
 * EN CASO CONTRARIO ALMACENA EL MENSAJE DE ERROR PRODUCIDO QUE SE
 * PUEDE CONOCER CON EL METODO getError()
 */
class EntityManager {

    /**
     * Fichero de configuracion de conexiones por defecto
     * Es el fichero que se utilizará si no se indica otro en la
     * llamada al constructor.
     * @var string
     */
    private $file = "config/config.yml";
    public static $dbLinkInstance = null;
    public static $dbEngine = null;
    public static $host = null;
    public static $user = null;
    public static $password = null;
    public static $dataBase = null;
    public static $conection = array();
    private $result = null;
    private $affectedRows = null;
    private $logErrorQueryFile;
    private $logQueryFile;

    /**
     * Guardar el eventual error producido en la conexión
     * @var array
     */
    private $error = array();

    /**
     * Estable la conexion a la base de datos.
     * Abre el fichero de configuracion '$fileConfig', o en su defecto config/config.yml
     * y lee el nodo $conection donde se definen los parametros de conexion.
     * 
     * Si no se indica valor para el parámetro $conection, se tomarán los valores
     * de la primera conexión definida en el archivo de configuración. De esta forma, y en el caso
     * de trabajar con una sola base de datos, no es necesario indicar el nombre de conexión para
     * cada tabla en el modelo de datos.
     *
     * En entorno de desarrollo los parámetros de conexión se fuerzan a:
     *
     *      user    =   $conection
     *      password=   $conection
     *      dataBase=   $conection
     *
     * 
     * Si la conexion es exitosa, getDblink() devolvera valor y si no getError() nos indica
     * el error producido.
     *
     * @param string $conection Nombre de la conexion, opcional
     * @param string $fileConfig Nombre del fichero de configuracion, opcional
     */
    public function __construct($conection, $fileConfig = '') {

        $this->logErrorQueryFile = str_replace("bin/albatronic", "", __DIR__) . "log/error_query.log";
        $this->logQueryFile = str_replace("bin/albatronic", "", __DIR__) . "log/query.log";

        if (is_array($conection)) {
            if (is_null(self::$dbLinkInstance) || (self::$host !== $conection['host']) || (self::$dataBase !== $conection['database'])) {
                self::$dbEngine = $conection['dbEngine'];
                self::$host = $conection['host'];
                self::$user = $conection['user'];
                self::$password = $conection['password'];
                self::$dataBase = $conection['database'];
                $this->conecta();
            }
        } else {
            if (!isset(self::$conection[$conection])) {
                if ($fileConfig == '') {
                    //$fileConfig = $_SERVER['DOCUMENT_ROOT'] . $_SESSION['appPath'] . "/" . $this->file;
                    $fileConfig = str_replace("bin/albatronic", "", __DIR__) . $this->file;
                }
                if (file_exists($fileConfig)) {
                    //echo "busco config {$conection}<br/>";
                    $yaml = sfYaml::load($fileConfig);
                    // Si no se ha indicado el nombre de la conexión, se tomara la primera
                    //if ($conection == '')
                    //    list($conection, $nada) = each($yaml['config']['conections']);
                    self::$conection[$conection] = $yaml['config']['conections'][$conection];
                } else {
                    die("EntityManager []: ERROR AL LEER EL ARCHIVO DE CONFIGURACION. " . $fileConfig . " NO EXISTE\n");
                }
            }
            self::$dbEngine = self::$conection[$conection]['dbEngine'];
            self::$host = self::$conection[$conection]['host'];
            self::$user = self::$conection[$conection]['user'];
            self::$password = self::$conection[$conection]['password'];
            self::$dataBase = self::$conection[$conection]['database'];
            if (is_null(self::$dbLinkInstance)) {
                $this->conecta();
            }
        }
    }

    /**
     * Conecta a la base de datos con los parametros de conexión indicados
     * en config/config.yml.
     * Si la conexion es exitosa self::$dbLinkInstance tendrá valor en caso contrario,
     * $this->error tendra el mensaje de error.
     */
    private function conecta() {

        switch (self::$dbEngine) {
            case 'mysql':
                //echo "me conecto ".self::$host."<br/>";
                self::$dbLinkInstance = mysql_connect(self::$host, self::$user, self::$password);
                if (is_resource(self::$dbLinkInstance)) {
                    mysql_select_db(self::$dataBase, self::$dbLinkInstance);
                }
                break;

            case 'mssql':
                self::$dbLinkInstance = mssql_connect(self::$host, self::$user, self::$password);
                if (is_resource(self::$dbLinkInstance)) {
                    mssql_select_db(self::$dataBase, self::$dbLinkInstance);
                }
                break;

            case 'interbase':
                self::$dbLinkInstance = ibase_connect(self::$host, self::$user, self::$password);
                break;

            case 'pgsql':
                self::$dbLinkInstance = pg_connect("host=" . self::$host . " dbname=" . self::$dataBase . " user=" . self::$user . " password=" . self::$password);
                break;
            default:
                $this->error[] = "EntityManager [conecta]: Conexión no realizada. No se ha indicado el tipo de base de datos. " . mysql_errno() . " " . mysql_error();
        }

        if (is_null(self::$dbLinkInstance)) {
            $this->error[] = "EntityManager [conecta]: No se pudo conectar " . self::$host . ":" . self::$dataBase . "Error: " . mysql_error();
        }
    }

    /**
     * Cierra la conexión con la base de datos
     */
    public function desConecta() {
        /**
          if (is_resource(self::$dbLinkInstance)) {
          switch (self::$dbEngine) {
          case 'mysql':
          mysql_close(self::$dbLinkInstance);
          break;
          case 'mssql':
          mssql_close(self::$dbLinkInstance);
          break;
          case 'interbase':
          ibase_free_result($this->result);
          ibase_close(self::$dbLinkInstance);
          break;
          default:
          $this->error[] = "EntityManager [desConecta]: Desconexión no realizada. No se ha indicado el tipo de base de datos";
          }
          }
         */
    }

    /**
     * Ejecuta un query sobre la conexion existente. Si se produce algun error
     * se puede consultar con getError().
     * @param string Sentencia sql
     * @return result
     */
    public function query($query) {
        $this->result = null;

        if ($_SESSION['VARIABLES']['EnvPro']['log'] === '1') {
            $fp = fopen($this->logQueryFile, "a");
            fwrite($fp, date("Y-m-d H:i:s") . "\t" . $query . "\n");
            fclose($fp);
        }

        switch (self::$dbEngine) {
            case 'mysql':
                //mysql_select_db($this->getdataBase());
                $this->result = mysql_query($query, self::$dbLinkInstance);
                if (!$this->result)
                    $this->setError("query", $query);
                else
                    $this->affectedRows = mysql_affected_rows(self::$dbLinkInstance);
                break;

            case 'mssql':
                //mssql_select_db($this->dataBase);
                $query = str_replace("`", "", $query);
                $this->result = mssql_query($query, self::$dbLinkInstance);
                if (!$this->result)
                    $this->setError("query", $query);
                else
                    $this->affectedRows = mysql_affected_rows(self::$dbLinkInstance);
                break;

            case 'interbase':
                $query = str_replace("`", "", $query);
                $this->result = ibase_query(self::$dbLinkInstance, $query);
                if (!$this->result)
                    $this->setError("query", $query);
                else
                    $this->affectedRows = ibase_affected_rows(self::$dbLinkInstance);
                break;

            case 'pgsql':
                $query = str_replace("`", "", $query);
                $this->result = pg_query(self::$dbLinkInstance, $query);
                if (!$this->result)
                    $this->setError("query", $query);
                else
                    $this->affectedRows = pg_affected_rows(self::$dbLinkInstance);
                break;

            default:
                $this->setError("query", "No se ha indicado el tipo de base de datos");
        }
        return $this->result;
    }

    /**
     * Carga datos en un array desde la BD.
     * El primer elemento del array tiene el indice 0. Cada elemento es a su vez
     * otro array ASOCIATIVO.
     * Este metodo debe ser llamado despues del metodo query().
     * Si se produce algun error se puede consultar con getError().
     * @return array Las filas encontradas
     */
    public function fetchResult() {
        $rows = array();

        switch (self::$dbEngine) {
            case 'mysql':
                while ($row = mysql_fetch_array($this->result, MYSQL_ASSOC))
                    $rows[] = $row;
                break;

            case 'mssql':
                while ($row = mssql_fetch_array($this->result, MYSQL_ASSOC))
                    $rows[] = $row;
                break;

            case 'interbase':
                while ($row = ibase_fetch_assoc($this->result))
                    $rows[] = $row;
                break;

            case 'pgsql':
                while ($row = pg_fetch_assoc($this->result))
                    $rows[] = $row;
                break;

            default:
                $this->setError("fetchResult", "No se ha indicado el tipo de base de datos");
                break;
        }
        return $rows;
    }

    /**
     * Devuelve un array de registros
     * 
     * @param string $tp Nombre o alias de la tabla principal 
     * @param string $select La sentencia select sin el where
     * @param string $where La parte WHERE
     * @param string $orderBy La parte ORDER By
     * @param string $limit La parte LIMIT
     * @return array Array de registros obtenidos
     */
    public function getResult($tp, $select, $where = '', $orderBy = '', $limit = '') {

        // Criterio de ordenación
        $orderBy = ($orderBy != '') ? $orderBy : "{$tp}.SortOrder";

        // Condición de vigencia
        $filtro = "({$tp}.Deleted='0')";

        if ($where != '') {
            $filtro .= " AND {$where}";
        }

        // Limit
        if ($limit != '') {
            switch (self::$dbEngine) {
                case 'mysql':
                    $limit = "LIMIT {$limit}";
                    $query = "{$select} WHERE {$filtro} ORDER BY {$orderBy} {$limit}";
                    break;

                case 'mssql':
                    $limit = "TOP {$limit}";
                    $select = str_replace("SELECT", "SELECT {$limit}", $select);
                    $query = "{$select} WHERE {$filtro} ORDER BY {$orderBy}";
                    break;

                case 'interbase':
                    break;

                case 'pgsql':
                    $valores = explode(",", $limit);
                    if (count($valores) == 1) {
                        $limit = "LIMIT {$valores[0]}";
                    } elseif (count($valores) == 2) {
                        $limit = "LIMIT {$valores[1]} OFFSET {$valores[0]}";
                    }
                    $query = "{$select} WHERE {$filtro} ORDER BY {$orderBy} {$limit}";
                    break;
            }
        }


        //echo $query;
        $this->query($query);

        return $this->fetchResult();
    }

    /**
     * Devuelve $limit filas encontradas a partir de la fila $offset
     * Este metodo es similar a fetchResult.
     * @param integer $limit
     * @param integer $offset
     * @return array Las filas encontradas
     */
    public function fetchResultLimit($limit, $rowNumber = '') {
        $rows = array();
        $nfilas = 0;
        if ($rowNumber < 0)
            $rowNumber = 1;

        switch (self::$dbEngine) {
            case 'mysql':
                @mysql_data_seek($this->result, $rowNumber);
                while (($row = mysql_fetch_array($this->result, MYSQL_ASSOC)) and ( $nfilas < $limit)) {
                    $nfilas++;
                    $rows[] = $row;
                }
                break;

            case 'mssql':
                //NO ESTA BIEN IMPLEMENTADO
                while ($row = mssql_fetch_array($this->result, MYSQL_ASSOC))
                    $rows[] = $row;
                break;

            case 'interbase':
                //NO ESTA BIEN IMPLEMENTADO
                while ($row = ibase_fetch_assoc($this->result))
                    $rows[] = $row;
                break;

            case 'pgsql':
                $valores = explode(",", $limit);
                if (count($valores) == 1) {
                    $limit = "LIMIT {$valores[0]}";
                } elseif (count($valores) == 2) {
                    $limit = "LIMIT {$valores[1]} OFFSET {$valores[0]}";
                }
                $query = "{$select} WHERE {$filtro} ORDER BY {$orderBy} {$limit}";
                break;
            default:
                $this->setError("fetchResultLimit", "No se ha indicado el tipo de base de datos");
                break;
        }
        return $rows;
    }

    /**
     * Devuelve el numero de columnas de la consulta
     * @return integer El numero de columnas de la consulta
     */
    public function numFields() {
        switch (self::$dbEngine) {
            case 'mysql':
                return mysql_num_fields($this->result);
            case 'mssql':
                return mssql_num_fields($this->result);
            case 'interbase':
                return ibase_num_fields($this->result);
            case 'pgsql':
                return pg_num_fields($this->result);
            default:
                $this->setError("numFields", "No se ha indicado el tipo de base de datos");
                break;
        }
    }

    /**
     * Devuelve el numero de filas de la consulta
     * @return integer El numero de filas de la consulta
     */
    public function numRows() {
        switch (self::$dbEngine) {
            case 'mysql':
                return mysql_num_rows($this->result);
            case 'mssql':
                return mssql_num_rows($this->result);
            case 'interbase': //NO IMPLEMENTADO
                return false;
            case 'pgsql':
                return pg_num_rows($this->result);
            default:
                $this->setError("numRows", "No se ha indicado el tipo de base de datos");
                break;
        }
    }

    /**
     * Se posiciona en el numero de registro indicado
     * @param integer $rowNumber El numero de registro a donde posicionarse
     * @return boolean
     */
    public function dataSeek($rowNumber) {
        switch (self::$dbEngine) {
            case 'mysql':
                return mysql_data_seek($this->result, $rowNumber);
            case 'mssql':
                //No implementado
                return false;
            case 'interbase':
                //No implementado
                return false;
            case 'pgsql':
                //No implementado
                return false;

            default:
                $this->setError("dataSeek", "No se ha indicado el tipo de base de datos");
                return false;
                break;
        }
    }

    /**
     * Devuelve el ID del ultimo insert
     * @return inter
     */
    public function getInsertId() {
        switch (self::$dbEngine) {
            case 'mysql':
                //return mysql_insert_id(self::$dbLinkInstance);
                $result = mysql_query("SELECT LAST_INSERT_ID()", self::$dbLinkInstance);
                $row = mysql_fetch_row($result);
                return $row[0];
            case 'mssql':
                //No implementado
                return 0;
            case 'interbase':
                //No implementado
                return 0;
            case 'pgsql':
                //No implementado
                return 0;
            default:
                $this->setError("getInsertId", "No se ha indicado el tipo de base de datos");
                return 0;
        }
    }

    /**
     * Devuelve el numero de filas afectadas en la ultima consulta
     * @return integer
     */
    public function getAffectedRows() {
        return $this->affectedRows;
    }

    /**
     * Devuelve el nombre del servidor de datos
     * @return string El nombre del servidor
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * Devuelve el usuario de conexion a la base de datos
     * @return string Usuario
     */
    public function getUser() {
        return self::$user;
    }

    /**
     * Devuelve el password de conexion a la base de datos
     * @return string Password
     */
    public function getPassword() {
        return self::$password;
    }

    /**
     * Devuelve el nombre de la base de datos
     * @return string Nombre de la base de datos
     */
    public function getDataBase() {
        return self::$dataBase;
    }

    public function getDbLink() {
        return self::$dbLinkInstance;
    }

    /**
     * Genera el mensaje de error haciendo una gestión individualizada
     * de algunos errores dependiendo del código de error y del motor de base de datos
     *
     * @param string $method El nombre del método que capturó el error
     * @param string $error Mensaje de error personalizado (opcional)
     */
    public function setError($method, $error = '') {

        $mensaje = "EntityManager [{$method}]: ";

        if ($error != '') {
            $mensaje .= $error;
        } else {
            switch (self::$dbEngine) {
                case 'mysql':
                    switch (mysql_errno()) {
                        case '1062':
                            $mensaje .= "Datos duplicados. " . mysql_error();
                            break;
                        default:
                            $mensaje .= mysql_errno() . " " . mysql_error();
                            break;
                    }
                    break;

                default:
                    $mensaje .= mysql_errno() . " " . mysql_error();
                    break;
            }
        }

        // ESCRIBE EL ERROR EN EL LOG
        $fp = fopen($this->logErrorQueryFile, "a");
        if ($fp) {
            fwrite($fp, date('Y-m-d H:i:s') . "\t" . $_SERVER['PHP_SELF'] . "\t" . $mensaje . "\n");
            fclose($fp);
        }

        // ENVIA CORREO AL SUPER ADMINISTRADOR
        $email = trim($_SESSION['VARIABLES']['EnvPro']['eMailSuperAdministrator']);
        if ($email != '') {
            mail($email, "Error query", $mensaje);
        }
        $this->error[] = $mensaje;
    }

    /**
     * Devuelve un array con los errores producidos
     * @return array Eventuales errores
     */
    public function getError() {
        return $this->error;
    }

}
