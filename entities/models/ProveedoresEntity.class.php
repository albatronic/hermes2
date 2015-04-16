<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:47
 */

/**
 * @orm:Entity(proveedores)
 */
class ProveedoresEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $IDProveedor;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $RazonSocial;

    /**
     * @orm:Column(type="string")
     */
    protected $NombreComercial;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $Cif;

    /**
     * @orm:Column(type="string")
     */
    protected $Direccion;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $IDPais = '73';

    /**
     * @orm:Column(type="string")
     */
    protected $IDPoblacion = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $IDProvincia = '18';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $CodigoPostal = '0000000000';

    /**
     * @orm:Column(type="string")
     */
    protected $Telefono;

    /**
     * @orm:Column(type="string")
     */
    protected $Fax;

    /**
     * @orm:Column(type="string")
     */
    protected $Movil;

    /**
     * @orm:Column(type="string")
     */
    protected $EMail;

    /**
     * @orm:Column(type="string")
     */
    protected $Web;

    /**
     * @orm:Column(type="string")
     */
    protected $CContable = '0000000000';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $Banco = '0000';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $Oficina = '0000';

    /**
     * @orm:Column(type="")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $Digito = '00';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $Cuenta = '0000000000';

    /**
     * @orm:Column(type="string")
     */
    protected $Observaciones = '';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $IDFP = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="proveedores")
     */
    protected $Iva = '1';

    /**
     * @orm:Column(type="string")
     */
    protected $Iban = null;

    /**
     * @orm:Column(type="string")
     */
    protected $Bic = null;

    /**
     * @orm:Column(type="string")
     */
    protected $Mandato;

    /**
     * @orm:Column(type="date")
     * @assert:NotBlank(groups="clientes")
     */
    protected $FechaMandato = '0000-00-00';

    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpProveedores';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDProveedor';

    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDProveedor', 'ParentEntity' => 'PedidosCab', 'ParentColumn' => 'IDProveedor'),
        array('SourceColumn' => 'IDProveedor', 'ParentEntity' => 'FrecibidasCab', 'ParentColumn' => 'IDProveedor'),
        array('SourceColumn' => 'IDProveedor', 'ParentEntity' => 'RecibosProveedores', 'ParentColumn' => 'IDProveedor'),
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDProveedor($IDProveedor) {
        $this->IDProveedor = $IDProveedor;
    }

    public function getIDProveedor() {
        return $this->IDProveedor;
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

    public function setCContable($CContable) {
        $this->CContable = trim($CContable);
    }

    public function getCContable() {
        return $this->CContable;
    }

    public function setBanco($Banco) {
        $this->Banco = str_pad(trim($Banco), 4, "0");
    }

    public function getBanco() {
        return $this->Banco;
    }

    public function setOficina($Oficina) {
        $this->Oficina = str_pad(trim($Oficina), 4, "0");
    }

    public function getOficina() {
        return $this->Oficina;
    }

    public function setDigito($Digito) {
        $this->Digito = str_pad(trim($Digito), 2, "0");
    }

    public function getDigito() {
        return $this->Digito;
    }

    public function setCuenta($Cuenta) {
        $this->Cuenta = str_pad(trim($Cuenta), 10, "0");
    }

    public function getCuenta() {
        return $this->Cuenta;
    }

    public function setObservaciones($Observaciones) {
        $this->Observaciones = trim($Observaciones);
    }

    public function getObservaciones() {
        return $this->Observaciones;
    }

    public function setIDFP($IDFP) {
        $this->IDFP = $IDFP;
    }

    public function getIDFP() {
        if (!($this->IDFP instanceof FormasPago))
            $this->IDFP = new FormasPago($this->IDFP);
        return $this->IDFP;
    }

    public function setIva($Iva) {
        $this->Iva = $Iva;
    }

    public function getIva() {
        if (!($this->Iva instanceof ValoresSN))
            $this->Iva = new ValoresSN($this->Iva);
        return $this->Iva;
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
        $fecha = new Fecha($FechaMandato);
        $this->FechaMandato = $fecha->getFecha();
        unset($fecha);
    }

    public function getFechaMandato() {
        $fecha = new Fecha($this->FechaMandato);
        $ddmmaaaa = $fecha->getddmmaaaa();
        unset($fecha);
        return $ddmmaaaa;
    }

}

// END class proveedores
?>