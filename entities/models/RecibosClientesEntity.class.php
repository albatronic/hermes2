<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:48
 */

/**
 * @orm:Entity(RecibosClientes)
 */
class RecibosClientesEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $IDRecibo;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $Recibo;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $IDSucursal;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $IDFactura;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $IDCliente;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $IDComercial;

    /**
     * @orm:Column(type="date")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $Fecha;

    /**
     * @orm:Column(type="date")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $Vencimiento;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosClientes")
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
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $Asiento = 0;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $Concepto = '';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $IDEstado;

    /**
     * @orm:Column(type="string")
     */
    protected $IDRemesa;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosClientes")
     */
    protected $Remesar = '1';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="RecibosClientes")
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
    protected $_tableName = 'ErpRecibosClientes';

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
        if (!$this->IDFactura instanceof FemitidasCab)
            $this->IDFactura = new FemitidasCab($this->IDFactura);
        return $this->IDFactura;
    }

    public function setIDCliente($IDCliente) {
        $this->IDCliente = $IDCliente;
    }

    public function getIDCliente() {
        if (!($this->IDCliente instanceof Clientes))
            $this->IDCliente = new Clientes($this->IDCliente);
        return $this->IDCliente;
    }

    public function setIDComercial($IDComercial) {
        $this->IDComercial = $IDComercial;
    }

    public function getIDComercial() {
        if (!($this->IDComercial instanceof Agentes))
            $this->IDComercial = new Agentes($this->IDComercial);
        return $this->IDComercial;
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

    public function getFechaMandato($formato = "ddmmaaaa") {
        $fecha = new Fecha($this->FechaMandato);
        if ($formato == 'aaaammdd') {
            $formato = $fecha->getaaaammdd();
        } else {
            $formato = $fecha->getddmmaaaa();
        }
        unset($fecha);
        return $formato;
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

// END class RecibosClientes
?>