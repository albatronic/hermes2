<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 13.07.2014 17:39:52
 */

/**
 * @orm:Entity(ErpAfiliados)
 */
class AfiliadosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $IDAfiliado;

    /**
     * @var string
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $RazonSocial;

    /**
     * @var string
     */
    protected $NombreComercial;

    /**
     * @var string
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $Cif;

    /**
     * @var string
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $Direccion;

    /**
     * @var entities\Paises
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $IDPais = '0';

    /**
     * @var entities\Provincias
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $IDProvincia = '0';

    /**
     * @var entities\Municipios
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $IDPoblacion = '0';

    /**
     * @var string
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $CodigoPostal;

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
    protected $EMail;

    /**
     * @var string
     */
    protected $Web;

    /**
     * @var integer
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $Comision = '0.00';

    /**
     * @var string
     */
    protected $Iban;

    /**
     * @var string
     */
    protected $Bic;

    /**
     * @var string
     */
    protected $Mandato;

    /**
     * @var date
     * @assert NotBlank(groups="ErpAfiliados")
     */
    protected $FechaMandato = '0000-00-00';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpAfiliados';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDAfiliado';

    /**
     * Array con las columnas de la primarykey
     * @var array
     */
    protected $_arrayPrimaryKeys = array('IDAfiliado');

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
        'Paises',
        'Provincias',
        'Municipios',
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
    public function setIDAfiliado($IDAfiliado) {
        $this->IDAfiliado = $IDAfiliado;
    }

    public function getIDAfiliado() {
        return $this->IDAfiliado;
    }

    public function setRazonSocial($RazonSocial) {
        $this->RazonSocial = trim($RazonSocial);
    }

    public function getRazonSocial() {
        return $this->RazonSocial;
    }

    public function setNombreComercial($NombreComercial) {
        $this->NombreComercial = trim($NombreComercial);
    }

    public function getNombreComercial() {
        return $this->NombreComercial;
    }

    public function setCif($Cif) {
        $this->Cif = trim($Cif);
    }

    public function getCif() {
        return $this->Cif;
    }

    public function setDireccion($Direccion) {
        $this->Direccion = trim($Direccion);
    }

    public function getDireccion() {
        return $this->Direccion;
    }

    public function setIDPais($IDPais) {
        $this->IDPais = $IDPais;
    }

    public function getIDPais() {
        if (!($this->IDPais instanceof Paises))
            $this->IDPais = new Paises($this->IDPais);
        return $this->IDPais;
    }

    public function setIDProvincia($IDProvincia) {
        $this->IDProvincia = $IDProvincia;
    }

    public function getIDProvincia() {
        if (!($this->IDProvincia instanceof Provincias))
            $this->IDProvincia = new Provincias($this->IDProvincia);
        return $this->IDProvincia;
    }

    public function setIDPoblacion($IDPoblacion) {
        $this->IDPoblacion = $IDPoblacion;
    }

    public function getIDPoblacion() {
        if (!($this->IDPoblacion instanceof Municipios))
            $this->IDPoblacion = new Municipios($this->IDPoblacion);
        return $this->IDPoblacion;
    }

    public function setCodigoPostal($CodigoPostal) {
        $this->CodigoPostal = trim($CodigoPostal);
    }

    public function getCodigoPostal() {
        return $this->CodigoPostal;
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

    public function setEMail($EMail) {
        $this->EMail = trim($EMail);
    }

    public function getEMail() {
        return $this->EMail;
    }

    public function setWeb($Web) {
        $this->Web = trim($Web);
    }

    public function getWeb() {
        return $this->Web;
    }

    public function setComision($Comision) {
        $this->Comision = $Comision;
    }

    public function getComision() {
        return $this->Comision;
    }

    public function setIban($Iban) {
        $this->Iban = trim($Iban);
    }

    public function getIban() {
        return $this->Iban;
    }

    public function setBic($Bic) {
        $this->Bic = trim($Bic);
    }

    public function getBic() {
        return $this->Bic;
    }

    public function setMandato($Mandato) {
        $this->Mandato = trim($Mandato);
    }

    public function getMandato() {
        return $this->Mandato;
    }

    public function setFechaMandato($FechaMandato) {
        $date = new Fecha($FechaMandato);
        $this->FechaMandato = $date->getFecha();
        unset($date);
    }

    public function getFechaMandato() {
        $date = new Fecha($this->FechaMandato);
        $ddmmaaaa = $date->getddmmaaaa();
        unset($date);
        return $ddmmaaaa;
    }

}
