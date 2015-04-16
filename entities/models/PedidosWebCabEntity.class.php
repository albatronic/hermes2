<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.07.2014 20:29:53
 */

/**
 * @orm:Entity(ErpPedidosWebCab)
 */
class PedidosWebCabEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDPedido;

    /**
     * @var string
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $sesion;

    /**
     * @var entities\Sucursales
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDSucursal = '0';

    /**
     * @var entities\Almacenes
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDAlmacen = '0';

    /**
     * @var entities\Agentes
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDAgente = '0';

    /**
     * @var entities\Afiliados
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDAfiliado = '0';

    /**
     * @var date
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Fecha = '0000-00-00';

    /**
     * @var date
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $FechaEntrega = '0000-00-00';

    /**
     * @var entities\Clientes
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDCliente = '0';

    /**
     * @var entities\ClientesDentrega
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDDirec = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Importe = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Descuento = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $BaseImponible1 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Iva1 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $CuotaIva1 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Recargo1 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $CuotaRecargo1 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $BaseImponible2 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Iva2 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $CuotaIva2 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Recargo2 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $CuotaRecargo2 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $BaseImponible3 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Iva3 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $CuotaIva3 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Recargo3 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $CuotaRecargo3 = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $TotalBases = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $TotalIva = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $TotalRecargo = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $RecargoFinanciero = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $CuotaRecargoFinanciero = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Total = '0.00';

    /**
     * @var entities\EstadosPedidosWeb
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDEstado = '0';

    /**
     * @var entities\AlbaranesCab
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDAlbaran = '0';

    /**
     * @var string
     */
    protected $Observaciones;

    /**
     * @var string
     */
    protected $NotasInternas;

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Peso = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Volumen = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Bultos = '0.00';

    /**
     * @var string
     */
    protected $Expedicion;

    /**
     * @var entities\Agencias
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDAgencia = '0';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $FlagFacturar = '1';

    /**
     * @var entities\FormasPago
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDFP = '0';

    /**
     * @var entities\ZonasTransporte
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDZonaEnvio = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $GastosEnvio = '0.00';

    /**
     * @var string
     */
    protected $PlazoEntrega;

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $Envolver = '0';

    /**
     * @var string
     */
    protected $TextoEnvolver;

    /**
     * @var entities\Cupones
     * @assert NotBlank(groups="ErpPedidosWebCab")
     */
    protected $IDCupon = '0';

    /**
     * @var string
     */
    protected $Key1Pasarela;

    /**
     * @var string
     */
    protected $Key2Pasarela;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPedidosWebCab';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDPedido';

    /**
     * Array con las columnas de la primarykey
     * @var array
     */
    protected $_arrayPrimaryKeys = array('IDPedido');

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
        'Sucursales',
        'Almacenes',
        'Agentes',
        'Clientes',
        'ClientesDentrega',
        'EstadosPedidosWeb',
        'AlbaranesCab',
        'Agencias',
        'ValoresSN',
        'FormasPago',
        'ZonasTransporte',
        'Cupones',
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
    public function setIDPedido($IDPedido) {
        $this->IDPedido = $IDPedido;
    }

    public function getIDPedido() {
        return $this->IDPedido;
    }

    public function setsesion($sesion) {
        $this->sesion = trim($sesion);
    }

    public function getsesion() {
        return $this->sesion;
    }

    public function setIDSucursal($IDSucursal) {
        $this->IDSucursal = $IDSucursal;
    }

    public function getIDSucursal() {
        if (!($this->IDSucursal instanceof Sucursales))
            $this->IDSucursal = new Sucursales($this->IDSucursal);
        return $this->IDSucursal;
    }

    public function setIDAlmacen($IDAlmacen) {
        $this->IDAlmacen = $IDAlmacen;
    }

    public function getIDAlmacen() {
        if (!($this->IDAlmacen instanceof Almacenes))
            $this->IDAlmacen = new Almacenes($this->IDAlmacen);
        return $this->IDAlmacen;
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

    public function setFechaEntrega($FechaEntrega) {
        $date = new Fecha($FechaEntrega);
        $this->FechaEntrega = $date->getFecha();
        unset($date);
    }

    public function getFechaEntrega() {
        $date = new Fecha($this->FechaEntrega);
        $ddmmaaaa = $date->getddmmaaaa();
        unset($date);
        return $ddmmaaaa;
    }

    public function setIDCliente($IDCliente) {
        $this->IDCliente = $IDCliente;
    }

    public function getIDCliente() {
        if (!($this->IDCliente instanceof Clientes))
            $this->IDCliente = new Clientes($this->IDCliente);
        return $this->IDCliente;
    }

    public function setIDDirec($IDDirec) {
        $this->IDDirec = $IDDirec;
    }

    public function getIDDirec() {
        if (!($this->IDDirec instanceof ClientesDentrega))
            $this->IDDirec = new ClientesDentrega($this->IDDirec);
        return $this->IDDirec;
    }

    public function setImporte($Importe) {
        $this->Importe = $Importe;
    }

    public function getImporte() {
        return $this->Importe;
    }

    public function setDescuento($Descuento) {
        $this->Descuento = $Descuento;
    }

    public function getDescuento() {
        return $this->Descuento;
    }

    public function setBaseImponible1($BaseImponible1) {
        $this->BaseImponible1 = $BaseImponible1;
    }

    public function getBaseImponible1() {
        return $this->BaseImponible1;
    }

    public function setIva1($Iva1) {
        $this->Iva1 = $Iva1;
    }

    public function getIva1() {
        return $this->Iva1;
    }

    public function setCuotaIva1($CuotaIva1) {
        $this->CuotaIva1 = $CuotaIva1;
    }

    public function getCuotaIva1() {
        return $this->CuotaIva1;
    }

    public function setRecargo1($Recargo1) {
        $this->Recargo1 = $Recargo1;
    }

    public function getRecargo1() {
        return $this->Recargo1;
    }

    public function setCuotaRecargo1($CuotaRecargo1) {
        $this->CuotaRecargo1 = $CuotaRecargo1;
    }

    public function getCuotaRecargo1() {
        return $this->CuotaRecargo1;
    }

    public function setBaseImponible2($BaseImponible2) {
        $this->BaseImponible2 = $BaseImponible2;
    }

    public function getBaseImponible2() {
        return $this->BaseImponible2;
    }

    public function setIva2($Iva2) {
        $this->Iva2 = $Iva2;
    }

    public function getIva2() {
        return $this->Iva2;
    }

    public function setCuotaIva2($CuotaIva2) {
        $this->CuotaIva2 = $CuotaIva2;
    }

    public function getCuotaIva2() {
        return $this->CuotaIva2;
    }

    public function setRecargo2($Recargo2) {
        $this->Recargo2 = $Recargo2;
    }

    public function getRecargo2() {
        return $this->Recargo2;
    }

    public function setCuotaRecargo2($CuotaRecargo2) {
        $this->CuotaRecargo2 = $CuotaRecargo2;
    }

    public function getCuotaRecargo2() {
        return $this->CuotaRecargo2;
    }

    public function setBaseImponible3($BaseImponible3) {
        $this->BaseImponible3 = $BaseImponible3;
    }

    public function getBaseImponible3() {
        return $this->BaseImponible3;
    }

    public function setIva3($Iva3) {
        $this->Iva3 = $Iva3;
    }

    public function getIva3() {
        return $this->Iva3;
    }

    public function setCuotaIva3($CuotaIva3) {
        $this->CuotaIva3 = $CuotaIva3;
    }

    public function getCuotaIva3() {
        return $this->CuotaIva3;
    }

    public function setRecargo3($Recargo3) {
        $this->Recargo3 = $Recargo3;
    }

    public function getRecargo3() {
        return $this->Recargo3;
    }

    public function setCuotaRecargo3($CuotaRecargo3) {
        $this->CuotaRecargo3 = $CuotaRecargo3;
    }

    public function getCuotaRecargo3() {
        return $this->CuotaRecargo3;
    }

    public function setTotalBases($TotalBases) {
        $this->TotalBases = $TotalBases;
    }

    public function getTotalBases() {
        return $this->TotalBases;
    }

    public function setTotalIva($TotalIva) {
        $this->TotalIva = $TotalIva;
    }

    public function getTotalIva() {
        return $this->TotalIva;
    }

    public function setTotalRecargo($TotalRecargo) {
        $this->TotalRecargo = $TotalRecargo;
    }

    public function getTotalRecargo() {
        return $this->TotalRecargo;
    }

    public function setRecargoFinanciero($RecargoFinanciero) {
        $this->RecargoFinanciero = $RecargoFinanciero;
    }

    public function getRecargoFinanciero() {
        return $this->RecargoFinanciero;
    }

    public function setCuotaRecargoFinanciero($CuotaRecargoFinanciero) {
        $this->CuotaRecargoFinanciero = $CuotaRecargoFinanciero;
    }

    public function getCuotaRecargoFinanciero() {
        return $this->CuotaRecargoFinanciero;
    }

    public function setTotal($Total) {
        $this->Total = $Total;
    }

    public function getTotal() {
        return $this->Total;
    }

    public function setIDEstado($IDEstado) {
        $this->IDEstado = $IDEstado;
    }

    public function getIDEstado() {
        if (!($this->IDEstado instanceof EstadosPedidosWeb))
            $this->IDEstado = new EstadosPedidosWeb($this->IDEstado);
        return $this->IDEstado;
    }

    public function setIDAlbaran($IDAlbaran) {
        $this->IDAlbaran = $IDAlbaran;
    }

    public function getIDAlbaran() {
        if (!($this->IDAlbaran instanceof AlbaranesCab))
            $this->IDAlbaran = new AlbaranesCab($this->IDAlbaran);
        return $this->IDAlbaran;
    }

    public function setObservaciones($Observaciones) {
        $this->Observaciones = trim($Observaciones);
    }

    public function getObservaciones() {
        return $this->Observaciones;
    }

    public function setNotasInternas($NotasInternas) {
        $this->NotasInternas = trim($NotasInternas);
    }

    public function getNotasInternas() {
        return $this->NotasInternas;
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

    public function setBultos($Bultos) {
        $this->Bultos = $Bultos;
    }

    public function getBultos() {
        return $this->Bultos;
    }

    public function setExpedicion($Expedicion) {
        $this->Expedicion = trim($Expedicion);
    }

    public function getExpedicion() {
        return $this->Expedicion;
    }

    public function setIDAgencia($IDAgencia) {
        $this->IDAgencia = $IDAgencia;
    }

    public function getIDAgencia() {
        if (!($this->IDAgencia instanceof Agencias))
            $this->IDAgencia = new Agencias($this->IDAgencia);
        return $this->IDAgencia;
    }

    public function setFlagFacturar($FlagFacturar) {
        $this->FlagFacturar = $FlagFacturar;
    }

    public function getFlagFacturar() {
        if (!($this->FlagFacturar instanceof ValoresSN))
            $this->FlagFacturar = new ValoresSN($this->FlagFacturar);
        return $this->FlagFacturar;
    }

    public function setIDFP($IDFP) {
        $this->IDFP = $IDFP;
    }

    public function getIDFP() {
        if (!($this->IDFP instanceof FormasPago))
            $this->IDFP = new FormasPago($this->IDFP);
        return $this->IDFP;
    }

    public function setIDZonaEnvio($IDZonaEnvio) {
        $this->IDZonaEnvio = $IDZonaEnvio;
    }

    public function getIDZonaEnvio() {
        if (!($this->IDZonaEnvio instanceof ZonasTransporte))
            $this->IDZonaEnvio = new ZonasTransporte($this->IDZonaEnvio);
        return $this->IDZonaEnvio;
    }

    public function setGastosEnvio($GastosEnvio) {
        $this->GastosEnvio = $GastosEnvio;
    }

    public function getGastosEnvio() {
        return $this->GastosEnvio;
    }

    public function setPlazoEntrega($PlazoEntrega) {
        $this->PlazoEntrega = trim($PlazoEntrega);
    }

    public function getPlazoEntrega() {
        return $this->PlazoEntrega;
    }

    public function setEnvolver($Envolver) {
        $this->Envolver = $Envolver;
    }

    public function getEnvolver() {
        if (!($this->Envolver instanceof ValoresSN))
            $this->Envolver = new ValoresSN($this->Envolver);
        return $this->Envolver;
    }

    public function setTextoEnvolver($TextoEnvolver) {
        $this->TextoEnvolver = trim($TextoEnvolver);
    }

    public function getTextoEnvolver() {
        return $this->TextoEnvolver;
    }

    public function setIDCupon($IDCupon) {
        $this->IDCupon = $IDCupon;
    }

    public function getIDCupon() {
        if (!($this->IDCupon instanceof Cupones))
            $this->IDCupon = new Cupones($this->IDCupon);
        return $this->IDCupon;
    }

    public function setKey1Pasarela($Key1Pasarela) {
        $this->Key1Pasarela = trim($Key1Pasarela);
    }

    public function getKey1Pasarela() {
        return $this->Key1Pasarela;
    }

    public function setKey2Pasarela($Key2Pasarela) {
        $this->Key2Pasarela = trim($Key2Pasarela);
    }

    public function getKey2Pasarela() {
        return $this->Key2Pasarela;
    }

}

// END class ErpPedidosWebCab
