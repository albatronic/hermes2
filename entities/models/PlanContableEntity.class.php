<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.01.2014 20:42:34
 */

/**
 * @orm:Entity(PlanContable)
 */
class PlanContableEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="PlanContable")
     */
    protected $Id;

    /**
     * @var integer
     * @assert NotBlank(groups="PlanContable")
     */
    protected $IDEjercicio = '0';

    /**
     * @var string
     * @assert NotBlank(groups="PlanContable")
     */
    protected $Cuenta;

    /**
     * @var string
     * @assert NotBlank(groups="PlanContable")
     */
    protected $Titulo;

    /**
     * @var integer
     */
    protected $Debe = '0.00';

    /**
     * @var integer
     */
    protected $Haber = '0.00';

    /**
     * @var integer
     */
    protected $Saldo = '0.00';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPlanContable';

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

    public function setIDEjercicio($IDEjercicio) {
        $this->IDEjercicio = $IDEjercicio;
    }

    public function getIDEjercicio() {
        if (!($this->IDEjercicio instanceof EjerciciosContables))
            $this->IDEjercicio = new EjerciciosContables($this->IDEjercicio);
        return $this->IDEjercicio;
    }

    public function setCuenta($Cuenta) {
        $this->Cuenta = trim($Cuenta);
    }

    public function getCuenta() {
        return $this->Cuenta;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = trim($Titulo);
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setDebe($Debe) {
        $this->Debe = $Debe;
    }

    public function getDebe() {
        return $this->Debe;
    }

    public function setHaber($Haber) {
        $this->Haber = $Haber;
    }

    public function getHaber() {
        return $this->Haber;
    }

    public function setSaldo($Saldo) {
        $this->Saldo = $Saldo;
    }

    public function getSaldo() {
        return $this->Saldo;
    }

}

// END class PlanContable
?>