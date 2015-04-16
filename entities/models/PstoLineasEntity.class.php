<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 11.02.2012 00:11:58
 */

/**
 * @orm:Entity(PstoLineas)
 */
class PstoLineasEntity extends EntityComunes {

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\PstoCab
     */
    protected $IDPsto = '0';
    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $IDLinea;
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\Articulos
     */
    protected $IDArticulo;
    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Descripcion;
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Unidades = '1.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Precio = '0.0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Descuento = '0.0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Importe = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $ImporteCosto = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\Almacenes
     */
    protected $IDAlmacen = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Iva = '';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Recargo = '0.0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $ComisionAgente = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $ComisionMontador = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\TiposVenta
     */
    protected $IDVenta = '1';
    /**
     * @orm:Column(type="tinyint")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $Comisionar = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\Agentes
     */
    protected $IDAgente = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\Agentes
     */
    protected $IDComercial = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\Promociones
     */
    protected $IDPromocion = '0';
    /**
     * @orm:Column(type="tinyint")
     * @assert:NotBlank(groups="PstoLineas")
     * @var entities\EstadosLineasPresupuestos
     */
    protected $IDEstado = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $AltoAl = '0.000';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $AnchoAl = '0.000';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $MtsAl = '0.000';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $AltoFa = '0.000';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $AnchoFa = '0.000';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="PstoLineas")
     */
    protected $MtsFa = '0.000';
    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';
    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPstoLineas';
    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDLinea';
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
        'PstoCab',
        'Articulos',
        'Almacenes',
        'TiposVenta',
        'Agentes',
        'Promociones',
        'EstadosLineasPresupuestos',
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDPsto($IDPsto) {
        $this->IDPsto = $IDPsto;
    }

    public function getIDPsto() {
        if (!($this->IDPsto instanceof PstoCab))
            $this->IDPsto = new PstoCab($this->IDPsto);
        return $this->IDPsto;
    }

    public function setIDLinea($IDLinea) {
        $this->IDLinea = $IDLinea;
    }

    public function getIDLinea() {
        return $this->IDLinea;
    }

    public function setIDArticulo($IDArticulo) {
        $this->IDArticulo = $IDArticulo;
    }

    public function getIDArticulo() {
        if (!($this->IDArticulo instanceof Articulos))
            $this->IDArticulo = new Articulos($this->IDArticulo);
        return $this->IDArticulo;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = trim($Descripcion);
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function setUnidades($Unidades) {
        $this->Unidades = $Unidades;
    }

    public function getUnidades() {
        return $this->Unidades;
    }

    public function setPrecio($Precio) {
        $this->Precio = $Precio;
    }

    public function getPrecio() {
        return $this->Precio;
    }

    public function setDescuento($Descuento) {
        $this->Descuento = $Descuento;
    }

    public function getDescuento() {
        return $this->Descuento;
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function setImporteCosto($ImporteCosto) {
        $this->ImporteCosto = $ImporteCosto;
    }

    public function getImporteCosto() {
        return $this->ImporteCosto;
    }

    public function setIDAlmacen($IDAlmacen) {
        $this->IDAlmacen = $IDAlmacen;
    }

    public function getIDAlmacen() {
        if (!($this->IDAlmacen instanceof Almacenes))
            $this->IDAlmacen = new Almacenes($this->IDAlmacen);
        return $this->IDAlmacen;
    }

    public function setIva($Iva) {
        $this->Iva = $Iva;
    }

    public function getIva() {
        return $this->Iva;
    }

    public function setRecargo($Recargo) {
        $this->Recargo = $Recargo;
    }

    public function getRecargo() {
        return $this->Recargo;
    }

    public function setComisionAgente($ComisionAgente) {
        $this->ComisionAgente = $ComisionAgente;
    }

    public function getComisionAgente() {
        return $this->ComisionAgente;
    }

    public function setComisionMontador($ComisionMontador) {
        $this->ComisionMontador = $ComisionMontador;
    }

    public function getComisionMontador() {
        return $this->ComisionMontador;
    }

    public function setIDVenta($IDVenta) {
        $this->IDVenta = $IDVenta;
    }

    public function getIDVenta() {
        if (!($this->IDVenta instanceof TiposVenta))
            $this->IDVenta = new TiposVenta($this->IDVenta);
        return $this->IDVenta;
    }

    public function setComisionar($Comisionar) {
        $this->Comisionar = $Comisionar;
    }

    public function getComisionar() {
        if (!($this->Comisionar instanceof ValoresSN))
            $this->Comisionar = new ValoresSN($this->Comisionar);
        return $this->Comisionar;
    }

    public function setIDAgente($IDAgente) {
        $this->IDAgente = $IDAgente;
    }

    public function getIDAgente() {
        if (!($this->IDAgente instanceof Agentes))
            $this->IDAgente = new Agentes($this->IDAgente);
        return $this->IDAgente;
    }

    public function setIDComercial($IDComercial) {
        $this->IDComercial = $IDComercial;
    }

    public function getIDComercial() {
        if (!($this->IDComercial instanceof Agentes))
            $this->IDComercial = new Agentes($this->IDComercial);
        return $this->IDComercial;
    }

    public function setIDPromocion($IDPromocion) {
        $this->IDPromocion = $IDPromocion;
    }

    public function getIDPromocion() {
        if (!($this->IDPromocion instanceof Promociones))
            $this->IDPromocion = new Promociones($this->IDPromocion);
        return $this->IDPromocion;
    }

    public function setIDEstado($IDEstado) {
        $this->IDEstado = $IDEstado;
    }

    public function getIDEstado() {
        if (!($this->IDEstado instanceof EstadosLineasPresupuestos))
            $this->IDEstado = new EstadosLineasPresupuestos($this->IDEstado);
        return $this->IDEstado;
    }

    public function setAltoAl($AltoAl) {
        $this->AltoAl = $AltoAl;
    }

    public function getAltoAl() {
        return $this->AltoAl;
    }

    public function setAnchoAl($AnchoAl) {
        $this->AnchoAl = $AnchoAl;
    }

    public function getAnchoAl() {
        return $this->AnchoAl;
    }

    public function setMtsAl($MtsAl) {
        $this->MtsAl = $MtsAl;
    }

    public function getMtsAl() {
        return $this->MtsAl;
    }

    public function setAltoFa($AltoFa) {
        $this->AltoFa = $AltoFa;
    }

    public function getAltoFa() {
        return $this->AltoFa;
    }

    public function setAnchoFa($AnchoFa) {
        $this->AnchoFa = $AnchoFa;
    }

    public function getAnchoFa() {
        return $this->AnchoFa;
    }

    public function setMtsFa($MtsFa) {
        $this->MtsFa = $MtsFa;
    }

    public function getMtsFa() {
        return $this->MtsFa;
    }

}

// END class PstoLineas
?>