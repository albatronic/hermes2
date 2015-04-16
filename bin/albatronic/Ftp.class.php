<?php

/**
 *
 * Clase para gestionar archivos vía FTP
 *
 * Uso:
 *
 * $ftp = new Ftp('servidorFtp','usuario','password');
 *
 * Subir archivo: $ok = $ftp->upload('carpetaDestino','archivoOrigen','archivoDestino');
 *
 * Descargar archivo: $ok = $ftp->downLoad('archivoDelServidor','archivoLocal');
 *
 * Cerrar sesion: $ftp->close();
 *
 * Obtener eventuales errores: $array = $ftp->getErrores();
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 18-oct-2012 19:55:37
 */
class Ftp {

    protected $server;
    protected $port;
    protected $user;
    protected $password;
    protected $timeout;
    protected $connectId;
    protected $errores = array();
    protected $alertas = array();

    /**
     * Realiza la conexión al servidor ftp
     * @param array $parameters Array con los parametros de conexion:
     * 
     *      * server => servidor de ftp
     * 
     *      * user => usuario
     * 
     *      * password => contraseña
     * 
     *      * port => puerto (defecto 21)
     * 
     *      * timeout => tiempo de espera
     * 
     * @return boolean TRUE si la conexión ex exitosa
     */
    public function __construct(array $parameters) {

        $this->server = $parameters['server'];
        $this->port = $parameters['port'];
        $this->user = $parameters['user'];
        $this->password = $parameters['password'];
        $this->timeout = $parameters['timeout'];

        return $this->connect();

    }

    /**
     * Sube un archivo al servidor vía ftp
     *
     * @param string $targetFolder Carpeta destino
     * @param string $sourceFile Fichero origen
     * @param string $targetFile Fichero destino
     * @param integer $transferMode Tipo de transferencia (FTP_ASCII, FTP_BINARY), por defecto FTP_BINARY
     * @return boolean TRUE si el archivo se subió con éxito
     */
    public function upLoad($targetFolder, $sourceFile, $targetFile, $transferMode = FTP_BINARY) {
        
        if ($this->connectId) {
            $ok = $this->chdir($targetFolder);

            if (!$ok) {
                $this->creaCarpeta($targetFolder);
                $ok = $this->chdir($targetFolder);
            }

            if ($ok) {
                $ok = @ftp_put($this->connectId, $targetFile, $sourceFile, $transferMode);

                if (!$ok) {
                    $this->errores[] = "FTP: La carga ha fallado!";
                }
            } else {
                $this->errores[] = "FTP: No existe el directorio '{$targetFolder}'";
            }
        }

        return (count($this->errores) == 0);
    }

    /**
     * Descarga un archivo desde el servidor FTP y lo copia en
     * un archivo local
     *
     * @param string $serverFile El archivo a descargar desde el servidor
     * @param string $localFile El nombre del archivo local que se generará
     * @param integer $transferMode Tipo de transferencia (FTP_ASCII, FTP_BINARY), por defecto FTP_BINARY
     * @return boolean TRUE si la descarga se hizo con éxito
     */
    public function downLoad($serverFile, $localFile, $transferMode = FTP_BINARY) {

        if ($this->connectId) {
            $ok = @ftp_get($this->connectId, $localFile, $serverFile, $transferMode);

            if (!$ok) {
                $this->errores[] = "FTP: No se ha podido descargar el archivo '{$serverFile} a '{$localFile}'";
            }
        }

        return (count($this->errores) == 0);
    }

    /**
     * Crear de forma recursiva las carpetas indicadas en $path
     * 
     * @param string $path
     */
    public function creaCarpeta($path) {

        $rutaActual = $this->pwd();
        
        $carpetas = explode("/", $path);
        foreach ($carpetas as $key => $carpeta) {
            if (!$this->chdir($carpeta)) {
                $this->mkdir($carpeta);
                //chmod($carpeta, 0775);
            }
        }
        
        $this->chdir($rutaActual);
    }
    
    /**
     * Comprueba si el archivo $fileName existe
     * en el servidor. El archivo se busca a partir
     * de FtpFolder indicado en los datos de conexión ftp
     * de la configuración del proyecto.
     * 
     * @param string $fileName
     * @return boolean
     */
    public function fileExists($fileName) {
        $ok = false;

        if ($this->connectId) {
            $ok = (ftp_size($this->connectId, $_SESSION['project']['ftp']['folder'] . "/" . $fileName) != -1);
        }

        return $ok;
    }
    
    /**
     * Borrar un archivo del servidor vía FTP
     *
     * @param string $folder Carpeta donde está el fichero a borrar
     * @param string $file Fichero a borrar
     * @return boolean TRUE si el archivo se subió con éxito
     */
    public function delete($folder, $file) {

        if ($this->connectId) {
            ftp_chdir($this->connectId, $folder);
            $ok = @ftp_delete($this->connectId, $file);

            if (!$ok) {
                $this->errores[] = "FTP: El borrado ha fallado!";
            }
        }

        return (count($this->errores) == 0);
    }

    /**
     * Cambia de nombre a un archivo vía FTP
     *
     * @param string $folder Carpeta donde está el archivo a cambiar
     * @param string $oldName El nombre actual del archivo
     * @param string $newName El nombre nuevo
     * @return boolean TRUE si el cambio de nombre se hizo con éxito
     */
    public function rename($folder, $oldName, $newName) {

        if ($this->connectId) {
            ftp_chdir($this->connectId, $folder);
            $ok = @ftp_rename($this->connectId, $oldName, $newName);

            if (!$ok) {
                $this->errores[] = "FTP: El cambio de nombre ha fallado!";
            }
        }

        return (count($this->errores) == 0);
    }

    /**
     * Crear un directorio en el servidor vía FTP.
     *
     * @param string $directory Directorio a crear
     * @return boolean TRUE si se creó el directorio
     */
    public function mkdir($directory) {

        if ($this->connectId) {
            $ok = ftp_mkdir($this->connectId, $directory);

            if (!$ok) {
                $this->errores[] = "FTP: No se ha podido crear la carpeta '{$directory}'";
            }
        }

        return (count($this->errores) == 0);
    }

    /**
     * Borra un directorio en el servidor vía FTP.
     *
     * El directorio debe estar vacio.
     *
     * @param string $directory Directorio a borrar
     * @return boolean TRUE si se borró el directorio
     */
    public function rmdir($directory) {

        if ($this->connectId) {
            $ok = @ftp_rmdir($this->connectId, $directory);

            if (!$ok) {
                $this->errores[] = "FTP: No se ha podido borrar la carpeta '{$directory}'";
            }
        }

        return (count($this->errores) == 0);
    }

    /**
     * Devuelve el nombre del directorio actual
     * @return string
     */
    public function pwd() {
        return ftp_pwd($this->connectId);
    }
    
    /**
     * Cambia de directorio en el servidor vía FTP.
     *
     * Si no puedo, pone el warning en el array $this->alertas
     *
     * @param string $directory Directorio a donde cambiar
     * @return boolean TRUE si se cambió al directorio
     */
    public function chdir($directory) {

        $this->alertas = array();

        if ($this->connectId) {
            $ok = @ftp_chdir($this->connectId, $directory);

            if (!$ok) {
                $this->alertas[] = "FTP: No se ha podido cambiar al directorio '{$directory}'";
            }
        }

        return (count($this->alertas) == 0);
    }

    /**
     * Ejecuta el comando FTP LIST, y devuelve el resultado como una matriz.
     *
     * @param string $directory El directorio a listar
     * @param boolean $recursive TRUE para
     * @return array Array con el listado del directorio
     */
    public function listDir($directory, $recursive = FALSE) {

        if ($this->connectId) {
            $array = @ftp_rawlist($this->connectId, $directory, $recursive);

            if (!is_array($array)) {
                $this->errores[] = "FTP: Ha fallado el listado de la carpeta '{$directory}'";
            }
        }

        return $array;
    }

    /**
     * Lee el contenido de un archivo vía CURL
     *
     * Devuelve un array con dos elementos:
     *
     * 'result' => El contenido del archivo
     * 'info' => array con la información de la operación realizada
     *
     * @param string $urlFile Url del archivo a leer
     * @return array Array con el resultado
     */
    public function getFileContent($urlFile) {

        $options = array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE,
        );

        $ch = curl_init($urlFile);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($result['info']['http_code'] != 200) {
            $this->errores[] = "Error " . $result['info']['http_code'] . ". Se produjo un error al leer el archivo " . $urlFile;
        }

        return array(
            'result' => $result,
            'info' => $info,
        );
    }
    
    /**
     * Se conecta al servidor por FTP
     * 
     * @return boolean TRUE si la conexión es exitosa
     */
    public function connect() {

        $this->connectId = ftp_connect($this->server,$this->port, $this->timeout);
        $ok = ftp_login($this->connectId, $this->user, $this->password);

        if (!$ok) {
            $this->errores[] = "FTP: La conexión ha fallado!";
        }
        
        return $ok;
    }

    public function close() {
        ftp_close($this->connectId);
    }

    /**
     * Devuelve un array con los eventuales errores producidos
     *
     * @return array Array de errores
     */
    public function getErrores() {
        return $this->errores;
    }

}

