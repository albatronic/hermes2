<?php

/**
 * Formas de Pago
 * 
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:47
 */

/**
 * @orm:Entity(formas_pago)
 */
class FormasPagoEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $IDFP;
    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $Descripcion = '';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $NumeroVctos = '1';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $DiaPrimerVcto = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $DiasIntervalo = '0';
    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $CContable = '0000000000';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $AnotarEnCaja = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $EstadoRecibo = '0';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $RecargoFinanciero = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $DescuentoFinanciero = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $Uso = '0';    
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $Accion = '0'; 
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="formas_pago")
     */
    protected $TipoTpv = '0';     
    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';
    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpFormasPago';
    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDFP';
    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'Clientes', 'ParentColumn' => 'IDFP'),
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'Proveedores', 'ParentColumn' => 'IDFP'),
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'AlbaranesCab', 'ParentColumn' => 'IDFP'),
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'PstoCab', 'ParentColumn' => 'IDFP'),
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'FemitidasCab', 'ParentColumn' => 'IDFP'),
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'PedidosCab', 'ParentColumn' => 'IDFP'),
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'FrecibidasCab', 'ParentColumn' => 'IDFP'),
        array('SourceColumn' => 'IDFP', 'ParentEntity' => 'ZonasPagoEnvio', 'ParentColumn' => 'IDFP'),
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDFP($IDFP) {
        $this->IDFP = $IDFP;
    }

    public function getIDFP() {
        return $this->IDFP;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = trim($Descripcion);
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function setNumeroVctos($NumeroVctos) {
        $this->NumeroVctos = $NumeroVctos;
    }

    public function getNumeroVctos() {
        return $this->NumeroVctos;
    }

    public function setDiaPrimerVcto($DiaPrimerVcto) {
        $this->DiaPrimerVcto = $DiaPrimerVcto;
    }

    public function getDiaPrimerVcto() {
        return $this->DiaPrimerVcto;
    }

    public function setDiasIntervalo($DiasIntervalo) {
        $this->DiasIntervalo = $DiasIntervalo;
    }

    public function getDiasIntervalo() {
        return $this->DiasIntervalo;
    }

    public function setCContable($CContable) {
        $this->CContable = $CContable;
    }

    public function getCContable() {
        return $this->CContable;
    }

    public function setAnotarEnCaja($AnotarEnCaja) {
        $this->AnotarEnCaja = $AnotarEnCaja;
    }

    public function getAnotarEnCaja() {
        if (!($this->AnotarEnCaja instanceof ValoresSN))
            $this->AnotarEnCaja = new ValoresSN($this->AnotarEnCaja);
        return $this->AnotarEnCaja;
    }

    public function setEstadoRecibo($EstadoRecibo) {
        $this->EstadoRecibo = $EstadoRecibo;
    }

    public function getEstadoRecibo() {
        if (!($this->EstadoRecibo instanceof EstadosRecibos))
            $this->EstadoRecibo = new EstadosRecibos($this->EstadoRecibo);
        return $this->EstadoRecibo;
    }

    public function setRecargoFinanciero($RecargoFinanciero) {
        $this->RecargoFinanciero = $RecargoFinanciero;
    }

    public function getRecargoFinanciero() {
        return $this->RecargoFinanciero;
    }

    public function setDescuentoFinanciero($DescuentoFinanciero) {
        $this->DescuentoFinanciero = $DescuentoFinanciero;
    }

    public function getDescuentoFinanciero() {
        return $this->DescuentoFinanciero;
    }

    public function setUso($Uso) {
        $this->Uso = trim($Uso);
    }

    public function getUso() {
        if (!($this->Uso instanceof UsoWeb))
            $this->Uso = new UsoWeb($this->Uso);
        return $this->Uso;
    }

    public function setAccion($Accion) {
        $this->Accion = trim($Accion);
    }

    public function getAccion() {
        if (!($this->Accion instanceof AccionesFormasPago))
            $this->Accion = new AccionesFormasPago($this->Accion);
        return $this->Accion;
    }

    public function setTipoTpv($TipoTpv) {
        $this->TipoTpv = trim($TipoTpv);
    }

    public function getTipoTpv() {
        if (!($this->TipoTpv instanceof TiposTpv))
            $this->TipoTpv = new TiposTpv($this->TipoTpv);
        return $this->TipoTpv;
    }
}