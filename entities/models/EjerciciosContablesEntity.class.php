<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.01.2014 21:12:08
 */

/**
 * @orm:Entity(EjerciciosContables)
 */
class EjerciciosContablesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="EjerciciosContables")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="EjerciciosContables")
     */
    protected $Titulo;

    /**
     * @var date
     * @assert NotBlank(groups="EjerciciosContables")
     */
    protected $FechaInicio = '0000-00-00';

    /**
     * @var date
     */
    protected $FechaFin = '0000-00-00';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="EjerciciosContables")
     */
    protected $Activo = '0';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="EjerciciosContables")
     */
    protected $Cerrado = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpEjerciciosContables';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PlanContable', 'ParentColumn' => 'IDEjercicio'),
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'ValoresSN',
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

    public function setTitulo($Titulo) {
        $this->Titulo = trim($Titulo);
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setFechaInicio($FechaInicio) {
        $date = new Fecha($FechaInicio);
        $this->FechaInicio = $date->getFecha();
        unset($date);
    }

    public function getFechaInicio() {
        $date = new Fecha($this->FechaInicio);
        $ddmmaaaa = $date->getddmmaaaa();
        unset($date);
        return $ddmmaaaa;
    }

    public function setFechaFin($FechaFin) {
        $date = new Fecha($FechaFin);
        $this->FechaFin = $date->getFecha();
        unset($date);
    }

    public function getFechaFin() {
        $date = new Fecha($this->FechaFin);
        $ddmmaaaa = $date->getddmmaaaa();
        unset($date);
        return $ddmmaaaa;
    }

    public function setActivo($Activo) {
        $this->Activo = $Activo;
    }

    public function getActivo() {
        if (!($this->Activo instanceof ValoresSN))
            $this->Activo = new ValoresSN($this->Activo);
        return $this->Activo;
    }

    public function setCerrado($Cerrado) {
        $this->Cerrado = $Cerrado;
    }

    public function getCerrado() {
        if (!($this->Cerrado instanceof ValoresSN))
            $this->Cerrado = new ValoresSN($this->Cerrado);
        return $this->Cerrado;
    }

}

// END class EjerciciosContables
?>