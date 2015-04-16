<?php

/**
 * Oficinas de Entidades Bancarias
 * 
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 19:20:36
 */

/**
 * @orm:Entity(bancos_oficinas)
 */
class BancosOficinasEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $ID;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $IDBanco;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $IDOficina;

    /**
     * @orm:Column(type="")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $Digito = '0';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $Direccion;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDPais = '68';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDProvincia = '18';

    /**
     * @orm:Column(type="string")
     */
    protected $IDPoblacion = '0';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $CodigoPostal = '0000000000';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $Telefono;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $Fax;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="bancos_oficinas")
     */
    protected $EMail;

    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpBancosOficinas';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'ID';

    /**
     * GETTERS Y SETTERS
     */
    public function setID($ID) {
        $this->ID = $ID;
    }

    public function getID() {
        return $this->ID;
    }

    public function setIDBanco($IDBanco) {
        $this->IDBanco = $IDBanco;
    }

    public function getIDBanco() {
        if (!($this->IDBanco instanceof Bancos))
            $this->IDBanco = new Bancos($this->IDBanco);
        return $this->IDBanco;
    }

    public function setIDOficina($IDOficina) {
        $this->IDOficina = $IDOficina;
    }

    public function getIDOficina() {
        return $this->IDOficina;
    }

    public function setDigito($Digito) {
        $this->Digito = $Digito;
    }

    public function getDigito() {
        return $this->Digito;
    }

    public function setDireccion($Direccion) {
        $this->Direccion = $Direccion;
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

    public function setIDPoblacion($IDPoblacion) {
        $this->IDPoblacion = $IDPoblacion;
    }

    public function getIDPoblacion() {
        if (!($this->IDPoblacion instanceof Municipios))
            $this->IDPoblacion = new Municipios($this->IDPoblacion);
        return $this->IDPoblacion;
    }

    public function setIDProvincia($IDProvincia) {
        $this->IDProvincia = $IDProvincia;
    }

    public function getIDProvincia() {
        if (!($this->IDProvincia instanceof Provincias))
            $this->IDProvincia = new Provincias($this->IDProvincia);
        return $this->IDProvincia;
    }

    public function setCodigoPostal($CodigoPostal) {
        $this->CodigoPostal = $CodigoPostal;
    }

    public function getCodigoPostal() {
        return $this->CodigoPostal;
    }

    public function setTelefono($Telefono) {
        $this->Telefono = $Telefono;
    }

    public function getTelefono() {
        return $this->Telefono;
    }

    public function setFax($Fax) {
        $this->Fax = $Fax;
    }

    public function getFax() {
        return $this->Fax;
    }

    public function setEMail($EMail) {
        $this->EMail = $EMail;
    }

    public function getEMail() {
        return $this->EMail;
    }

}

// END class bancos_oficinas
?>