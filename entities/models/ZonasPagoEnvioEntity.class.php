<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 14.03.2014 23:46:54
 */

/**
 * @orm:Entity(ErpZonasPagoEnvio)
 */
class ZonasPagoEnvioEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $Id;

    /**
     * @var entities\ZonasTransporte
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $IDZona = '0';

    /**
     * @var entities\FormasPago
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $IDFP = '0';

    /**
     * @var entities\Agencias
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $IDAgencia = '0';

    /**
     * @var string
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $PlazoEntrega;

    /**
     * @var integer
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $ImportePedidoMinimo = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $ImportePedidoMaximo = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $DesdeGramos = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $HastaGramos = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpZonasPagoEnvio")
     */
    protected $Gastos = '0.00';

    /**
     * @orm:Column(type="string")
     */
    protected $Uso = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpZonasPagoEnvio';

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
        'ZonasTransporte',
        'FormasPago',
        'Agencias',
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

    public function setIDZona($IDZona) {
        $this->IDZona = $IDZona;
    }

    public function getIDZona() {
        if (!($this->IDZona instanceof ZonasTransporte))
            $this->IDZona = new ZonasTransporte($this->IDZona);
        return $this->IDZona;
    }

    public function setIDFP($IDFP) {
        $this->IDFP = $IDFP;
    }

    public function getIDFP() {
        if (!($this->IDFP instanceof FormasPago))
            $this->IDFP = new FormasPago($this->IDFP);
        return $this->IDFP;
    }

    public function setIDAgencia($IDAgencia) {
        $this->IDAgencia = $IDAgencia;
    }

    public function getIDAgencia() {
        if (!($this->IDAgencia instanceof Agencias))
            $this->IDAgencia = new Agencias($this->IDAgencia);
        return $this->IDAgencia;
    }

    public function setPlazoEntrega($PlazoEntrega) {
        $this->PlazoEntrega = trim($PlazoEntrega);
    }

    public function getPlazoEntrega() {
        return $this->PlazoEntrega;
    }

    public function setImportePedidoMinimo($ImportePedidoMinimo) {
        $this->ImportePedidoMinimo = $ImportePedidoMinimo;
    }

    public function getImportePedidoMinimo() {
        return $this->ImportePedidoMinimo;
    }

    public function setImportePedidoMaximo($ImportePedidoMaximo) {
        $this->ImportePedidoMaximo = $ImportePedidoMaximo;
    }

    public function getImportePedidoMaximo() {
        return $this->ImportePedidoMaximo;
    }

    public function setDesdeGramos($DesdeGramos) {
        $this->DesdeGramos = $DesdeGramos;
    }

    public function getDesdeGramos() {
        return $this->DesdeGramos;
    }

    public function setHastaGramos($HastaGramos) {
        $this->HastaGramos = $HastaGramos;
    }

    public function getHastaGramos() {
        return $this->HastaGramos;
    }

    public function setGastos($Gastos) {
        $this->Gastos = $Gastos;
    }

    public function getGastos() {
        return $this->Gastos;
    }

    public function setUso($Uso) {
        $this->Uso = trim($Uso);
    }

    public function getUso() {
        if (!($this->Uso instanceof UsoWeb))
            $this->Uso = new UsoWeb($this->Uso);
        return $this->Uso;
    }

}

// END class ErpZonasPagoEnvio
?>