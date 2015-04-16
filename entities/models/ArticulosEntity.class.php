<?php

/**
 * Articulos
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:46
 */

/**
 * @orm:Entity(articulos)
 */
class ArticulosEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $IDArticulo;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Codigo;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Descripcion = '';

    /**
     * @var string
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $Subtitulo;

    /**
     * @var string
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $Resumen;

    /**
     * @var string
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $ReclamoCorto;

    /**
     * @var string
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $ReclamoLargo;

    /**
     * @var entities\ErpFamilias
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $IDCategoria = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $IDFamilia = '0';

    /**
     * @orm:Column(type="integer")
     */
    protected $IDSubfamilia = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $IDFabricante = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Pvd = '0.000';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Pvp = '0.000';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Margen = '0.00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Pmc = '0.000';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $IDIva = '1';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $PvpAnterior = '0.000';

    /**
     * @orm:Column(type="string")
     */
    protected $Etiqueta;

    /**
     * @orm:Column(type="string")
     */
    protected $CodigoEAN;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Caducidad = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Garantia;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Peso = '0.0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Volumen = '0.0';

    /**
     * @orm:Column(type="string")
     */
    protected $Caracteristicas;

    /**
     * @orm:Column(type="datetime")
     */
    protected $FechaUltimoPrecio;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Vigente = '1';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Inventario = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Trazabilidad = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $PackingCompras = '1.00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $PackingVentas = '1.00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $Merma = '0.00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $BloqueoStock = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $BajoPedido = '0';

    /**
     * @var entities\ArticulosEstados
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $IDEstado1 = '0';

    /**
     * @var entities\ArticulosEstados
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $IDEstado2 = '0';

    /**
     * @var entities\ArticulosEstados
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $IDEstado3 = '0';

    /**
     * @var entities\ArticulosEstados
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $IDEstado4 = '0';

    /**
     * @var entities\ArticulosEstados
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $IDEstado5 = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $StockMinimo = '0.00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $StockMaximo = '0.00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $MinimoVentaAlto = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $MinimoVentaAncho = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $MinimoVenta = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $MultiploAlto = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $MultiploAncho = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $RecargoEnergetico = '0';

    /**
     * Unidad de Medida Básica
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $UMB = '1';

    /**
     * Unidad de Medida para las Compras
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $UMC = '1';

    /**
     * Factor de conversion de la UMC hacia la UMB
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $CUMC = '1';

    /**
     * Unidad de Medida de Almacén
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $UMA = '1';

    /**
     * Factor de conversion de la UMA hacia la UMB
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $CUMA = '1';

    /**
     * Unidad de Media para las Ventas
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $UMV = '1';

    /**
     * Factor de conversion de la UMV hacia la UMB
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="articulos")
     */
    protected $CUMV = '1';

    /**
     * @var string
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $AvisosPedidos;    

    /**
     * @var string
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $AvisosPresupuestos;    

    /**
     * @var string
     * @assert NotBlank(groups="ErpArticulos")
     */
    protected $AvisosAlbaranes;    
   
    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpArticulos*';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDArticulo';

    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'AlbaranesLineas', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'PstoLineas', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'FemitidasLineas', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'FrecibidasLineas', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'PedidosLineas', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'Existencias', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'Promociones', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'InventariosLineas', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'Lotes', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'TraspasosLineas', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'MvtosAlmacen', 'ParentColumn' => 'IDArticulo'),
        array('SourceColumn' => 'IDArticulo', 'ParentEntity' => 'ArticulosPropiedades', 'ParentColumn' => 'IDArticulo'),
    );

    /**
     * Relacion de entidades de las que depende esta
     * @var array
     */
    protected $_childEntities = array(
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDArticulo($IDArticulo) {
        $this->IDArticulo = $IDArticulo;
    }

    public function getIDArticulo() {
        return $this->IDArticulo;
    }

    public function setCodigo($Codigo) {
        $this->Codigo = trim($Codigo);
    }

    public function getCodigo() {
        return $this->Codigo;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = trim($Descripcion);
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function setSubtitulo($Subtitulo) {
        $this->Subtitulo = trim($Subtitulo);
    }

    public function getSubtitulo() {
        return $this->Subtitulo;
    }

    public function setResumen($Resumen) {
        $this->Resumen = trim($Resumen);
    }

    public function getResumen() {
        return $this->Resumen;
    }

    public function setReclamoCorto($ReclamoCorto) {
        $this->ReclamoCorto = trim($ReclamoCorto);
    }

    public function getReclamoCorto() {
        return $this->ReclamoCorto;
    }

    public function setReclamoLargo($ReclamoLargo) {
        $this->ReclamoLargo = trim($ReclamoLargo);
    }

    public function getReclamoLargo() {
        return $this->ReclamoLargo;
    }

    public function setIDCategoria($IDCategoria) {
        $this->IDCategoria = $IDCategoria;
    }

    public function getIDCategoria() {
        if (!($this->IDCategoria instanceof Familias))
            $this->IDCategoria = new Familias($this->IDCategoria);
        return $this->IDCategoria;
    }

    public function setIDFamilia($IDFamilia) {
        $this->IDFamilia = $IDFamilia;
    }

    public function getIDFamilia() {
        if (!($this->IDFamilia instanceof Familias))
            $this->IDFamilia = new Familias($this->IDFamilia);
        return $this->IDFamilia;
    }

    public function setIDSubfamilia($IDSubfamilia) {
        $this->IDSubfamilia = $IDSubfamilia;
    }

    public function getIDSubfamilia() {
        if (!($this->IDSubfamilia instanceof Familias))
            $this->IDSubfamilia = new Familias($this->IDSubfamilia);
        return $this->IDSubfamilia;
    }

    public function setIDFabricante($IDFabricante) {
        $this->IDFabricante = $IDFabricante;
    }

    public function getIDFabricante() {
        if (!($this->IDFabricante instanceof Fabricantes))
            $this->IDFabricante = new Fabricantes($this->IDFabricante);
        return $this->IDFabricante;
    }

    public function setPvd($Pvd) {
        $this->Pvd = $Pvd;
    }

    public function getPvd() {
        return $this->Pvd;
    }

    public function setPvp($Pvp) {
        $this->Pvp = $Pvp;
    }

    public function getPvp() {
        return $this->Pvp;
    }

    public function setMargen($Margen) {
        $this->Margen = $Margen;
    }

    public function getMargen() {
        return $this->Margen;
    }

    public function setPmc($Pmc) {
        $this->Pmc = $Pmc;
    }

    public function getPmc() {
        return $this->Pmc;
    }

    public function setIDIva($IDIva) {
        $this->IDIva = $IDIva;
    }

    public function getIDIva() {
        if (!($this->IDIva instanceof TiposIva))
            $this->IDIva = new TiposIva($this->IDIva);
        return $this->IDIva;
    }

    public function setPvpAnterior($PvpAnterior) {
        $this->PvpAnterior = $PvpAnterior;
    }

    public function getPvpAnterior() {
        return $this->PvpAnterior;
    }

    public function setEtiqueta($Etiqueta) {
        $this->Etiqueta = trim($Etiqueta);
    }

    public function getEtiqueta() {
        return $this->Etiqueta;
    }

    public function setCodigoEAN($CodigoEAN) {
        $this->CodigoEAN = trim($CodigoEAN);
    }

    public function getCodigoEAN() {
        return $this->CodigoEAN;
    }

    public function setCaducidad($Caducidad) {
        $this->Caducidad = $Caducidad;
    }

    public function getCaducidad() {
        return $this->Caducidad;
    }

    public function setGarantia($Garantia) {
        $this->Garantia = $Garantia;
    }

    public function getGarantia() {
        return $this->Garantia;
    }

    public function setPeso($Peso) {
        $this->Peso = $Peso;
    }

    public function getPeso() {
        return $this->Peso;
    }

    public function setVolumen($Volumen) {
        $this->Volumen = $Volumen;
    }

    public function getVolumen() {
        return $this->Volumen;
    }

    public function setCaracteristicas($Caracteristicas) {
        $this->Caracteristicas = trim($Caracteristicas);
    }

    public function getCaracteristicas() {
        return $this->Caracteristicas;
    }

    public function setFechaUltimoPrecio($FechaUltimoPrecio) {
        $this->FechaUltimoPrecio = $FechaUltimoPrecio;
    }

    public function getFechaUltimoPrecio() {
        return $this->FechaUltimoPrecio;
    }

    public function setVigente($Vigente) {
        $this->Vigente = $Vigente;
    }

    public function getVigente() {
        if (!($this->Vigente instanceof ValoresSN))
            $this->Vigente = new ValoresSN($this->Vigente);
        return $this->Vigente;
    }

    public function setInventario($Inventario) {
        $this->Inventario = $Inventario;
    }

    public function getInventario() {
        if (!($this->Inventario instanceof ValoresSN))
            $this->Inventario = new ValoresSN($this->Inventario);
        return $this->Inventario;
    }

    public function setTrazabilidad($Trazabilidad) {
        $this->Trazabilidad = $Trazabilidad;
    }

    public function getTrazabilidad() {
        if (!($this->Trazabilidad instanceof ValoresSN))
            $this->Trazabilidad = new ValoresSN($this->Trazabilidad);
        return $this->Trazabilidad;
    }

    public function setBajoPedido($BajoPedido) {
        $this->BajoPedido = $BajoPedido;
    }

    public function getBajoPedido() {
        if (!($this->BajoPedido instanceof ValoresSN))
            $this->BajoPedido = new ValoresSN($this->BajoPedido);
        return $this->BajoPedido;
    }

    public function setPackingCompras($PackingCompras) {
        $this->PackingCompras = $PackingCompras;
    }

    public function getPackingCompras() {
        return $this->PackingCompras;
    }

    public function setPackingVentas($PackingVentas) {
        $this->PackingVentas = $PackingVentas;
    }

    public function getPackingVentas() {
        return $this->PackingVentas;
    }

    public function setMerma($Merma) {
        $this->Merma = $Merma;
    }

    public function getMerma() {
        return $this->Merma;
    }

    public function setBloqueoStock($BloqueoStock) {
        $this->BloqueoStock = $BloqueoStock;
    }

    public function getBloqueoStock() {
        if (!($this->BloqueoStock instanceof ValoresSN))
            $this->BloqueoStock = new ValoresSN($this->BloqueoStock);
        return $this->BloqueoStock;
    }

    public function setIDEstado1($IDEstado1) {
        $this->IDEstado1 = $IDEstado1;
    }

    public function getIDEstado1() {
        if (!($this->IDEstado1 instanceof ArticulosEstados))
            $this->IDEstado1 = new ArticulosEstados($this->IDEstado1);
        return $this->IDEstado1;
    }

    public function setIDEstado2($IDEstado2) {
        $this->IDEstado2 = $IDEstado2;
    }

    public function getIDEstado2() {
        if (!($this->IDEstado2 instanceof ArticulosEstados))
            $this->IDEstado2 = new ArticulosEstados($this->IDEstado2);
        return $this->IDEstado2;
    }

    public function setIDEstado3($IDEstado3) {
        $this->IDEstado3 = $IDEstado3;
    }

    public function getIDEstado3() {
        if (!($this->IDEstado3 instanceof ArticulosEstados))
            $this->IDEstado3 = new ArticulosEstados($this->IDEstado3);
        return $this->IDEstado3;
    }

    public function setIDEstado4($IDEstado4) {
        $this->IDEstado4 = $IDEstado4;
    }

    public function getIDEstado4() {
        if (!($this->IDEstado4 instanceof ArticulosEstados))
            $this->IDEstado4 = new ArticulosEstados($this->IDEstado4);
        return $this->IDEstado4;
    }

    public function setIDEstado5($IDEstado5) {
        $this->IDEstado5 = $IDEstado5;
    }

    public function getIDEstado5() {
        if (!($this->IDEstado5 instanceof ArticulosEstados))
            $this->IDEstado5 = new ArticulosEstados($this->IDEstado5);
        return $this->IDEstado5;
    }

    public function setStockMinimo($StockMinimo) {
        $this->StockMinimo = $StockMinimo;
    }

    public function getStockMinimo() {
        return $this->StockMinimo;
    }

    public function setStockMaximo($StockMaximo) {
        $this->StockMaximo = $StockMaximo;
    }

    public function getStockMaximo() {
        return $this->StockMaximo;
    }

    public function setMinimoVentaAlto($MinimoVentaAlto) {
        $this->MinimoVentaAlto = $MinimoVentaAlto;
    }

    public function getMinimoVentaAlto() {
        return $this->MinimoVentaAlto;
    }

    public function setMinimoVentaAncho($MinimoVentaAncho) {
        $this->MinimoVentaAncho = $MinimoVentaAncho;
    }

    public function getMinimoVentaAncho() {
        return $this->MinimoVentaAncho;
    }

    public function setMinimoVenta($MinimoVenta) {
        $this->MinimoVenta = $MinimoVenta;
    }

    public function getMinimoVenta() {
        return $this->MinimoVenta;
    }

    public function setMultiploAlto($MultiploAlto) {
        $this->MultiploAlto = $MultiploAlto;
    }

    public function getMultiploAlto() {
        return $this->MultiploAlto;
    }

    public function setMultiploAncho($MultiploAncho) {
        $this->MultiploAncho = $MultiploAncho;
    }

    public function getMultiploAncho() {
        return $this->MultiploAncho;
    }

    public function setRecargoEnergetico($RecargoEnergetico) {
        $this->RecargoEnergetico = $RecargoEnergetico;
    }

    public function getRecargoEnergetico() {
        if (!($this->RecargoEnergetico instanceof ValoresSN))
            $this->RecargoEnergetico = new ValoresSN($this->RecargoEnergetico);
        return $this->RecargoEnergetico;
    }

    public function setUMB($UMB) {
        $this->UMB = $UMB;
    }

    public function getUMB() {
        if (!($this->UMB instanceof UnidadesMedida))
            $this->UMB = new UnidadesMedida($this->UMB);
        return $this->UMB;
    }

    public function setUMC($UMC) {
        $this->UMC = $UMC;
    }

    public function getUMC() {
        if (!($this->UMC instanceof UnidadesMedida))
            $this->UMC = new UnidadesMedida($this->UMC);
        return $this->UMC;
    }

    public function setCUMC($CUMC) {
        $this->CUMC = $CUMC;
    }

    public function getCUMC() {
        return $this->CUMC;
    }

    public function setUMA($UMA) {
        $this->UMA = $UMA;
    }

    public function getUMA() {
        if (!($this->UMA instanceof UnidadesMedida))
            $this->UMA = new UnidadesMedida($this->UMA);
        return $this->UMA;
    }

    public function setCUMA($CUMA) {
        $this->CUMA = $CUMA;
    }

    public function getCUMA() {
        return $this->CUMA;
    }

    public function setUMV($UMV) {
        $this->UMV = $UMV;
    }

    public function getUMV() {
        if (!($this->UMV instanceof UnidadesMedida))
            $this->UMV = new UnidadesMedida($this->UMV);
        return $this->UMV;
    }

    public function setCUMV($CUMV) {
        $this->CUMV = $CUMV;
    }

    public function getCUMV() {
        return $this->CUMV;
    }

    public function setAvisosPedidos($AvisosPedidos) {
        $this->AvisosPedidos = trim($AvisosPedidos);
    }

    public function getAvisosPedidos() {
        return $this->AvisosPedidos;
    }

    public function setAvisosPresupuestos($AvisosPresupuestos) {
        $this->AvisosPresupuestos = trim($AvisosPresupuestos);
    }

    public function getAvisosPresupuestos() {
        return $this->AvisosPresupuestos;
    }    
    
    public function setAvisosAlbaranes($AvisosAlbaranes) {
        $this->AvisosAlbaranes = trim($AvisosAlbaranes);
    }

    public function getAvisosAlbaranes() {
        return $this->AvisosAlbaranes;
    }
}

// END class articulos
?>