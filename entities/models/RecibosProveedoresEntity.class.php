<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:48
 */

/**
 * @orm:Entity(RecibosProveedores)
 */
class RecibosProveedoresEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $IDRecibo;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $Recibo;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="recibos_clientes")
     */
    protected $IDSucursal;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $IDFactura;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $IDProveedor;

    /**
     * @orm:Column(type="date")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $Fecha;

    /**
     * @orm:Column(type="date")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $Vencimiento;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $Importe = '0.00';

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
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $Asiento = '0';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $Concepto = '';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $IDEstado = '0';

    /**
     * @orm:Column(type="string")
     */
    protected $IDRemesa;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="recibos_clientes")
     */
    protected $Remesar = '1';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosProveedores")
     */
    protected $CContable = '0000000000';

    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpRecibosProveedores';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDRecibo';

    /**
     * GETTERS Y SETTERS
     */
    public function setIDRecibo($IDRecibo) {
        $this->IDRecibo = $IDRecibo;
    }

    public function getIDRecibo() {
        return $this->IDRecibo;
    }

    public function setRecibo($Recibo) {
        $this->Recibo = $Recibo;
    }

    public function getRecibo() {
        return $this->Recibo;
    }

    public function setIDSucursal($IDSucursal) {
        $this->IDSucursal = $IDSucursal;
    }

    public function getIDSucursal() {
        if (!($this->IDSucursal instanceof Sucursales))
            $this->IDSucursal = new Sucursales($this->IDSucursal);
        return $this->IDSucursal;
    }

    public function setIDFactura($IDFactura) {
        $this->IDFactura = $IDFactura;
    }

    public function getIDFactura() {
        if (!$this->IDFactura instanceof FrecibidasCab)
            $this->IDFactura = new FrecibidasCab($this->IDFactura);
        return $this->IDFactura;
    }

    public function setIDProveedor($IDProveedor) {
        $this->IDProveedor = $IDProveedor;
    }

    public function getIDProveedor() {
        if (!($this->IDProveedor instanceof Proveedores))
            $this->IDProveedor = new Proveedores($this->IDProveedor);
        return $this->IDProveedor;
    }

    public function setFecha($Fecha) {
        $date = new Fecha($Fecha);
        $this->Fecha = $date->getFecha();
        unset($date);
    }

    public function getFecha() {
        $date = new Fecha($this->Fecha);
        $ddmmaaaa = $date->getddmmaaaa();
        unset($date);
        return $ddmmaaaa;
    }

    public function setVencimiento($Vencimiento) {
        $date = new Fecha($Vencimiento);
        $this->Vencimiento = $date->getFecha();
        unset($date);
    }

    public function getVencimiento() {
        $date = new Fecha($this->Vencimiento);
        $ddmmaaaa = $date->getddmmaaaa();
        unset($date);
        return $ddmmaaaa;
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function getImporte() {
        return $this->Importe;
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

    public function setAsiento($Asiento) {
        $this->Asiento = $Asiento;
    }

    public function getAsiento() {
        return $this->Asiento;
    }

    public function setConcepto($Concepto) {
        $this->Concepto = trim($Concepto);
    }

    public function getConcepto() {
        return $this->Concepto;
    }

    public function setIDEstado($IDEstado) {
        $this->IDEstado = $IDEstado;
    }

    public function getIDEstado() {
        if (!($this->IDEstado instanceof EstadosRecibos))
            $this->IDEstado = new EstadosRecibos($this->IDEstado);
        return $this->IDEstado;
    }

    public function setIDRemesa($IDRemesa) {
        $this->IDRemesa = trim($IDRemesa);
    }

    public function getIDRemesa() {
        return $this->IDRemesa;
    }

    public function setRemesar($Remesar) {
        $this->Remesar = $Remesar;
    }

    public function getRemesar() {
        if (!($this->Remesar instanceof ValoresSN))
            $this->Remesar = new ValoresSN($this->Remesar);
        return $this->Remesar;
    }

    public function setCContable($CContable) {
        $this->CContable = trim($CContable);
    }

    public function getCContable() {
        return $this->CContable;
    }

}

// END class RecibosProveedores
?>