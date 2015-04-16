<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 14:24:31
 */

/**
 * @orm:Entity(PcaeUsuarios)
 */
class PcaeUsuariosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $Nombre;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $Apellidos;

    /**
     * @var string
     */
    protected $DNI;

    /**
     * @var string
     */
    protected $Direccion;

    /**
     * @var entities\CommMunicipios
     */
    protected $IdMunicipio = '0';

    /**
     * @var string
     */
    protected $CodigoPostal;

    /**
     * @var entities\CommProvincias
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $IdProvincia = '0';

    /**
     * @var entities\CommPaises
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $IdPais = '0';

    /**
     * @var string
     */
    protected $Telefono;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $EMail;

    /**
     * DATOS SERVIDOR DE CORREO
     */
    protected $EMailSocket = "smtp";
    protected $EMailHost;
    protected $EMailPort = 25;
    protected $EMailSmtpAuth = true;
    protected $EMailPassword = '';

    /**
     * @var string Contraseña de acceso al portal
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $Password;

    /**
     * @var integer
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $NLogin = '0';

    /**
     * @var datetime
     * @assert NotBlank(groups="PcaeUsuarios")
     */
    protected $UltimoLogin = '0000-00-00 00:00:00';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = 'pcae';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'PcaeUsuarios';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PcaeControlAcceso', 'ParentColumn' => 'IdUsuario'),
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PcaePermisos', 'ParentColumn' => 'IdUsuario'),
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PcaeEmpresasUsuarios', 'ParentColumn' => 'IdUsuario'),
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'CommMunicipios',
        'CommProvincias',
        'CommPaises',
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

    public function setNombre($Nombre) {
        $this->Nombre = trim($Nombre);
    }

    public function getNombre() {
        return $this->Nombre;
    }

    public function setApellidos($Apellidos) {
        $this->Apellidos = trim($Apellidos);
    }

    public function getApellidos() {
        return $this->Apellidos;
    }

    public function setDNI($DNI) {
        $this->DNI = trim($DNI);
    }

    public function getDNI() {
        return $this->DNI;
    }

    public function setDireccion($Direccion) {
        $this->Direccion = trim($Direccion);
    }

    public function getDireccion() {
        return $this->Direccion;
    }

    public function setIdMunicipio($IdMunicipio) {
        $this->IdMunicipio = $IdMunicipio;
    }

    public function getIdMunicipio() {
        if (!($this->IdMunicipio instanceof CommMunicipios))
            $this->IdMunicipio = new CommMunicipios($this->IdMunicipio);
        return $this->IdMunicipio;
    }

    public function setCodigoPostal($CodigoPostal) {
        $this->CodigoPostal = trim($CodigoPostal);
    }

    public function getCodigoPostal() {
        return $this->CodigoPostal;
    }

    public function setIdProvincia($IdProvincia) {
        $this->IdProvincia = $IdProvincia;
    }

    public function getIdProvincia() {
        if (!($this->IdProvincia instanceof CommProvincias))
            $this->IdProvincia = new CommProvincias($this->IdProvincia);
        return $this->IdProvincia;
    }

    public function setIdPais($IdPais) {
        $this->IdPais = $IdPais;
    }

    public function getIdPais() {
        if (!($this->IdPais instanceof CommPaises))
            $this->IdPais = new CommPaises($this->IdPais);
        return $this->IdPais;
    }

    public function setTelefono($Telefono) {
        $this->Telefono = trim($Telefono);
    }

    public function getTelefono() {
        return $this->Telefono;
    }

    public function setEMail($EMail) {
        $this->EMail = trim($EMail);
    }

    public function getEMail() {
        return $this->EMail;
    }

    public function setEMailSocket($EMailSocket) {
        $this->EMailSocket = trim($EMailSocket);
    }

    public function getEMailSocket() {
        return $this->EMailSocket;
    }

    public function setEMailHost($EMailHost) {
        $this->EMailHost = trim($EMailHost);
    }

    public function getEMailHost() {
        return $this->EMailHost;
    }

    public function setEMailPort($EMailPort) {
        $this->EMailPort = trim($EMailPort);
    }

    public function getEMailPort() {
        return $this->EMailPort;
    }

    public function setEMailSmtpAuth($EMailSmtpAuth) {
        $this->EMailSmtpAuth = trim($EMailSmtpAuth);
    }

    public function getEMailSmtpAuth() {
        return $this->EMailSmtpAuth;
    }

    public function setEMailPassword($EMailPassword) {
        $this->EMailPassword = trim($EMailPassword);
    }

    public function getEMailPassword() {
        return $this->EMailPassword;
    }

    public function setPassword($Password) {
        $this->Password = trim($Password);
    }

    public function getPassword() {
        return $this->Password;
    }

    public function setNLogin($NLogin) {
        $this->NLogin = $NLogin;
    }

    public function getNLogin() {
        return $this->NLogin;
    }

    public function setUltimoLogin($UltimoLogin) {
        $this->UltimoLogin = $UltimoLogin;
    }

    public function getUltimoLogin() {
        return $this->UltimoLogin;
    }

}

// END class PcaeUsuarios
?>