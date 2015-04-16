<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.04.2014 18:24:51
 */

/**
 * @orm:Entity(ErpCupones)
 */
class CuponesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $Cupon;

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $NumeroUsos = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $LimiteUsos = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $ImporteMinimo = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $Tipo = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $Valor = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpCupones")
     */
    protected $AplicaA = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpCupones';

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

    public function setCupon($Cupon) {
        $this->Cupon = trim($Cupon);
    }

    public function getCupon() {
        return $this->Cupon;
    }

    public function setNumeroUsos($NumeroUsos) {
        $this->NumeroUsos = $NumeroUsos;
    }

    public function getNumeroUsos() {
        return $this->NumeroUsos;
    }

    public function setLimiteUsos($LimiteUsos) {
        $this->LimiteUsos = $LimiteUsos;
    }

    public function getLimiteUsos() {
        return $this->LimiteUsos;
    }

    public function setImporteMinimo($ImporteMinimo) {
        $this->ImporteMinimo = $ImporteMinimo;
    }

    public function getImporteMinimo() {
        return $this->ImporteMinimo;
    }

    public function setValor($Valor) {
        $this->Valor = $Valor;
    }

    public function getValor() {
        return $this->Valor;
    }

    public function setTipo($Tipo) {
        $this->Tipo = $Tipo;
    }

    public function getTipo() {
        if (!($this->Tipo instanceof CuponesTipos))
            $this->Tipo = new CuponesTipos($this->Tipo);
        return $this->Tipo;
    }

    public function setAplicaA($AplicaA) {
        $this->AplicaA = $AplicaA;
    }

    public function getAplicaA() {
        if (!($this->AplicaA instanceof CuponesAplicaA))
            $this->AplicaA = new CuponesAplicaA($this->AplicaA);
        return $this->AplicaA;
    }    
}

// END class ErpCupones
