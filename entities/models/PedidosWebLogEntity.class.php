<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 20.09.2014 17:49:23
 */

/**
 * @orm:Entity(ErpPedidosWebLog)
 */
class PedidosWebLogEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $Id;

    /**
     * @var entities\PedidosWebCab
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $IDPedido = '0';

    /**
     * @var datetime
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $Fecha;

    /**
     * @var string
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $IpAddress;

    /**
     * @var string
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $Accion;

    /**
     * @var entities\EstadosPedidosWeb
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $IDEstado = '0';

    /**
     * @var string
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $Pasarela;

    /**
     * @var string
     * @assert NotBlank(groups="ErpPedidosWebLog")
     */
    protected $ResultadoPasarela;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPedidosWebLog';

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
        'PedidosWebCab',
        'EstadosPedidosWeb',
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

    public function setIDPedido($IDPedido) {
        $this->IDPedido = $IDPedido;
    }

    public function getIDPedido() {
        if (!($this->IDPedido instanceof PedidosWebCab))
            $this->IDPedido = new PedidosWebCab($this->IDPedido);
        return $this->IDPedido;
    }

    public function setFecha($Fecha) {
        $this->Fecha = $Fecha;
    }

    public function getFecha() {
        return $this->Fecha;
    }

    public function setIpAddress($IpAddress) {
        $this->IpAddress = trim($IpAddress);
    }

    public function getIpAddress() {
        return $this->IpAddress;
    }

    public function setAccion($Accion) {
        $this->Accion = trim($Accion);
    }

    public function getAccion() {
        return $this->Accion;
    }

    public function setIDEstado($IDEstado) {
        $this->IDEstado = $IDEstado;
    }

    public function getIDEstado() {
        if (!($this->IDEstado instanceof EstadosPedidosWeb))
            $this->IDEstado = new EstadosPedidosWeb($this->IDEstado);
        return $this->IDEstado;
    }

    public function setPasarela($Pasarela) {
        $this->Pasarela = trim($Pasarela);
    }

    public function getPasarela() {
        return $this->Pasarela;
    }

    public function setResultadoPasarela($ResultadoPasarela) {
        $this->ResultadoPasarela = trim($ResultadoPasarela);
    }

    public function getResultadoPasarela() {
        return $this->ResultadoPasarela;
    }

}
