<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.02.2014 19:37:17
 */

/**
 * @orm:Entity(ErpCarrito)
 */
class CarritoEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $sesion;

    /**
     * @var string
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $IpOrigen;

    /**
     * @var string
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $UserAgent;

    /**
     * @var entities\Articulos
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $IDArticulo = '0';

    /**
     * @var string
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Descripcion;

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Unidades = '0.00';

    /**
     * @var string
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $UnidadMedida = 'UMV';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Precio = '0.000';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Descuento = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Importe = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Iva = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Recargo = '0.00';

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpCarrito")
     */
    protected $Estado;

    /**
     * @var entities\Promociones
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IDPromocion = '0';    

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpPedidosWebLineas")
     */
    protected $IvaIncluido = '0';
    
    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpCarrito';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'Id';

    /**
     * Array con las columnas de la primarykey
     * @var array
     */
    protected $_arrayPrimaryKeys = array('Id');

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
        'Articulos',
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

    public function setsesion($sesion) {
        $this->sesion = trim($sesion);
    }

    public function getsesion() {
        return $this->sesion;
    }

    public function setIpOrigen($IpOrigen) {
        $this->IpOrigen = trim($IpOrigen);
    }

    public function getIpOrigen() {
        return $this->IpOrigen;
    }

    public function setUserAgent($UserAgent) {
        $this->UserAgent = trim($UserAgent);
    }

    public function getUserAgent() {
        return $this->UserAgent;
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

    public function setEstado($Estado) {
        $this->Estado = $Estado;
    }

    public function getEstado() {
        if (!($this->Estado instanceof ValoresSN))
            $this->Estado = new ValoresSN($this->Estado);
        return $this->Estado;
    }

    public function setIDPromocion($IDPromocion) {
        $this->IDPromocion = $IDPromocion;
    }

    public function getIDPromocion() {
        if (!($this->IDPromocion instanceof Promociones))
            $this->IDPromocion = new Promociones($this->IDPromocion);
        return $this->IDPromocion;
    }

    public function setIvaIncluido($IvaIncluido) {
        $this->IvaIncluido = $IvaIncluido;
    }

    public function getIvaIncluido() {
        if (!($this->IvaIncluido instanceof ValoresSN))
            $this->IvaIncluido = new ValoresSN($this->IvaIncluido);
        return $this->IvaIncluido;
    }    
}