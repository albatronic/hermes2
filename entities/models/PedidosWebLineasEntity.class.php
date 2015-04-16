<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.07.2014 20:31:56
 */

/**
 * @orm:Entity(ErpPedidosWebLineas)
 */
class PedidosWebLineasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDLinea;

    /**
     * @var entities\PedidosWebCab
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDPedido = '0';

    /**
     * @var entities\Articulos
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDArticulo = '0';

    /**
     * @var string
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Descripcion;

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Unidades = '0.00';

    /**
     * @var string
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $UnidadMedida = 'UMV';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Precio = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $PvpVigente = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Descuento = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Importe = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $ImporteCosto = '0.00';

    /**
     * @var entities\Almacenes
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDAlmacen = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Iva = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Recargo = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $ComisionAgente = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $ComisionMontador = '0.00';

    /**
     * @var entities\TiposVenta
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDVenta = '1';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $Comisionar = '0';

    /**
     * @var entities\Agentes
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDAgente = '0';

    /**
     * @var entities\Afiliados
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDAfiliado = '0';

    /**
     * @var entities\Promociones
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDPromocion = '0';

    /**
     * @var entities\EstadosLineasPedidosWeb
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDEstado = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $AltoAl = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $AnchoAl = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $MtsAl = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $AltoFa = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $AnchoFa = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLineas")
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
    protected $_tableName = 'ErpPedidosWebLineas';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDLinea';

    /**
     * Array con las columnas de la primarykey
     * @var array
     */
    protected $_arrayPrimaryKeys = array('IDLinea');

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
        'PedidosWebCab',
        'Articulos',
        'Almacenes',
        'TiposVenta',
        'ValoresSN',
        'Agentes',
        'Promociones',
        'EstadosLineasPedidosWeb',
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
    public function setIDLinea($IDLinea) {
        $this->IDLinea = $IDLinea;
    }

    public function getIDLinea() {
        return $this->IDLinea;
    }

    public function setIDPedido($IDPedido) {
        $this->IDPedido = $IDPedido;
    }

    public function getIDPedido() {
        if (!($this->IDPedido instanceof PedidosWebCab))
            $this->IDPedido = new PedidosWebCab($this->IDPedido);
        return $this->IDPedido;
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

    public function setUnidadMedida($UnidadMedida) {
        $this->UnidadMedida = trim($UnidadMedida);
    }

    public function getUnidadMedida() {
        return $this->UnidadMedida;
    }

    public function setPrecio($Precio) {
        $this->Precio = $Precio;
    }

    public function getPrecio() {
        return $this->Precio;
    }

    public function setPvpVigente($PvpVigente) {
        $this->PvpVigente = $PvpVigente;
    }

    public function getPvpVigente() {
        return $this->PvpVigente;
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

    public function setIDAfiliado($IDAfiliado) {
        $this->IDAfiliado = $IDAfiliado;
    }

    public function getIDAfiliado() {
        if (!($this->IDAfiliado instanceof Afiliados))
            $this->IDAfiliado = new Afiliados($this->IDAfiliado);
        return $this->IDAfiliado;
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
        if (!($this->IDEstado instanceof EstadosLineasPedidosWeb))
            $this->IDEstado = new EstadosLineasPedidosWeb($this->IDEstado);
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

// END class ErpPedidosWebLineas
