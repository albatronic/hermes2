<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 25.10.2012 19:17:27
 */

/**
 * @orm:Entity(PcaeProyectosApps)
 */
class PcaeProyectosAppsEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $Id;

    /**
     * @var entities\PcaeProyectos
     */
    protected $IdProyecto;

    /**
     * @var entities\PcaeApps
     */
    protected $IdApp;

    /**
     * @var integer
     */
    protected $NumeroLicencias = '1';

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $FtpServer;

    /**
     * @var integer
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $FtpPort = '21';

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $FtpFolder;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $FtpUser;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $FtpPassword;

    /**
     * @var integer
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $FtpTimeout;
    
    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $Url;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $DbEngine;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $Host;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $User;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $Password;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectosApps")
     */
    protected $Database;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = 'pcae';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'PcaeProyectosApps';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'Id';

    /**
     * Relacion de entidades que dependen de esta
     * @var string
     */
    protected $_parentEntities = array(
        array('SourceColumn' => ' Id', 'ParentEntity' => 'PcaeVariables', 'ParentColumn' => 'IdProyectosApps'),
        array('SourceColumn' => ' Id', 'ParentEntity' => 'PcaeControlAcceso', 'ParentColumn' => 'IdProyectoApp'),
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'PcaeProyectos',
        'PcaeApps',
        'ValoresSN',
        'ValoresPrivacy',
        'ValoresDchaIzq',
        'ValoresChangeFreq',
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setId($Id) {
        $this->Id = $Id;
    }

    public function getId() {
        return $this->Id;
    }

    public function setIdProyecto($IdProyecto) {
        $this->IdProyecto = $IdProyecto;
    }

    public function getIdProyecto() {
        if (!($this->IdProyecto instanceof PcaeProyectos))
            $this->IdProyecto = new PcaeProyectos($this->IdProyecto);
        return $this->IdProyecto;
    }

    public function setIdApp($IdApp) {
        $this->IdApp = $IdApp;
    }

    public function getIdApp() {
        if (!($this->IdApp instanceof PcaeApps))
            $this->IdApp = new PcaeApps($this->IdApp);
        return $this->IdApp;
    }

    public function setNumeroLicencias($NumeroLicencias) {
        $this->NumeroLicencias = $NumeroLicencias;
    }

    public function getNumeroLicencias() {
        return $this->NumeroLicencias;
    }

    public function setFtpServer($FtpServer) {
        $this->FtpServer = trim($FtpServer);
    }

    public function getFtpServer() {
        return $this->FtpServer;
    }

    public function setFtpPort($FtpPort) {
        $this->FtpPort = $FtpPort;
    }

    public function getFtpPort() {
        return $this->FtpPort;
    }

    public function setFtpFolder($FtpFolder) {
        $this->FtpFolder = trim($FtpFolder);
    }

    public function getFtpFolder() {
        return $this->FtpFolder;
    }

    public function setFtpUser($FtpUser) {
        $this->FtpUser = trim($FtpUser);
    }

    public function getFtpUser() {
        return $this->FtpUser;
    }

    public function setFtpPassword($FtpPassword) {
        $this->FtpPassword = trim($FtpPassword);
    }

    public function getFtpPassword() {
        return $this->FtpPassword;
    }

    public function setFtpTimeout($FtpTimeout) {
        $this->FtpTimeout = trim($FtpTimeout);
    }

    public function getFtpTimeout() {
        return $this->FtpTimeout;
    }
    
    public function setUrl($Url) {
        $this->Url = trim($Url);
    }

    public function getUrl() {
        return $this->Url;
    }

    public function setDbEngine($DbEngine) {
        $this->DbEngine = trim($DbEngine);
    }

    public function getDbEngine() {
        return $this->DbEngine;
    }

    public function setHost($Host) {
        $this->Host = trim($Host);
    }

    public function getHost() {
        return $this->Host;
    }

    public function setUser($User) {
        $this->User = trim($User);
    }

    public function getUser() {
        return $this->User;
    }

    public function setPassword($Password) {
        $this->Password = trim($Password);
    }

    public function getPassword() {
        return $this->Password;
    }

    public function setDatabase($Database) {
        $this->Database = trim($Database);
    }

    public function getDatabase() {
        return $this->Database;
    }

}