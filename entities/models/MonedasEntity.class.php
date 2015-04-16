<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 01:15:20
 */

/**
 * @orm:Entity(ErpMonedas)
 */
class MonedasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpMonedas")
     */
    protected $IDMoneda;

    /**
     * @var string
     * @assert NotBlank(groups="ErpMonedas")
     */
    protected $Codigo;

    /**
     * @var string
     * @assert NotBlank(groups="ErpMonedas")
     */
    protected $Moneda;

    /**
     * @var string
     */
    protected $Simbolo;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpMonedas';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDMoneda';

    /**
     * Array con las columnas de la primarykey
     * @var array
     */
    protected $_arrayPrimaryKeys = array('IDMoneda');

    /**
     * Relacion de entidades que dependen de esta
     * @var string
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDMoneda', 'ParentEntity' => 'Paises', 'ParentColumn' => 'IDMoneda'),
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
    public function setIDMoneda($IDMoneda) {
        $this->IDMoneda = $IDMoneda;
    }

    public function getIDMoneda() {
        return $this->IDMoneda;
    }

    public function setCodigo($Codigo) {
        $this->Codigo = trim($Codigo);
    }

    public function getCodigo() {
        return $this->Codigo;
    }

    public function setMoneda($Moneda) {
        $this->Moneda = trim($Moneda);
    }

    public function getMoneda() {
        return $this->Moneda;
    }

    public function setSimbolo($Simbolo) {
        $this->Simbolo = trim($Simbolo);
    }

    public function getSimbolo() {
        return $this->Simbolo;
    }

}

// END class ErpMonedas
?>