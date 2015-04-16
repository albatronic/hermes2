<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 14.08.2013 07:54:06
 */

/**
 * @orm:Entity(WebUsuarios)
 */
class WebUsuariosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $Nombre;

    /**
     * @var string
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $Apellidos;

    /**
     * @var string
     */
    protected $Empresa;

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
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $IdProvincia = '0';

    /**
     * @var entities\CommPaises
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $IdPais = '0';

    /**
     * @var string
     */
    protected $Telefono;

    /**
     * @var string
     */
    protected $Fax;

    /**
     * @var string
     */
    protected $Movil;

    /**
     * @var string
     */
    protected $Web;

    /**
     * @var string
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $EMail;

    /**
     * @var string
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $Password;

    /**
     * @var integer
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $NLogin = '0';

    /**
     * @var datetime
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $UltimoLogin = '0000-00-00 00:00:00';

    /**
     * @var entities\WebPerfiles
     * @assert NotBlank(groups="WebUsuarios")
     */
    protected $IdPerfil = '1';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'WebUsuarios';

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
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'CommMunicipios',
        'CommProvincias',
        'CommPaises',
        'WebPerfiles',
        'ValoresSN',
        'ValoresPrivacy',
        'ValoresDchaIzq',
        'ValoresChangeFreq',
        'RequestMethods',
        'RequestOrigins',
        'CpanAplicaciones',
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

    public function setEmpresa($Empresa) {
        $this->Empresa = trim($Empresa);
    }

    public function getEmpresa() {
        return $this->Empresa;
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

    public function setFax($Fax) {
        $this->Fax = trim($Fax);
    }

    public function getFax() {
        return $this->Fax;
    }

    public function setMovil($Movil) {
        $this->Movil = trim($Movil);
    }

    public function getMovil() {
        return $this->Movil;
    }

    public function setWeb($Web) {
        $this->Web = trim($Web);
    }

    public function getWeb() {
        return $this->Web;
    }

    public function setEMail($EMail) {
        $this->EMail = trim($EMail);
    }

    public function getEMail() {
        return $this->EMail;
    }

    public function setPassword($Password) {
        $this->Password = trim($Password);

        if (($this->Id == '') and ($this->Password != '')){         
            $config = sfYaml::load('config/config.yml');           
            $this->Password = md5($this->Password . $config['config']['semillaMD5']);
        }
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

    public function setIdPerfil($IdPerfil) {
        $this->IdPerfil = $IdPerfil;
    }

    public function getIdPerfil() {
        if (!($this->IdPerfil instanceof WebPerfiles))
            $this->IdPerfil = new WebPerfiles($this->IdPerfil);
        return $this->IdPerfil;
    }

}

// END class WebUsuarios
?>