<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 05.11.2012 01:09:30
 */

/**
 * @orm:Entity(PcaeProyectos)
 */
class PcaeProyectosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="PcaeProyectos")
     */
    protected $Id;

    /**
     * @var entities\PcaeEmpresas
     * @assert NotBlank(groups="PcaeProyectos")
     */
    protected $IdEmpresa;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeProyectos")
     */
    protected $Proyecto;

    /**
     * @var integer
     */
    protected $Presupuesto = '0';

    /**
     * @var integer
     */
    protected $HorasPrevistas = '0';

    /**
     * @var integer
     */
    protected $HorasReales = '0';

    /**
     * @var date
     * @assert NotBlank(groups="PcaeProyectos")
     */
    protected $FechaInicio = '0000-00-00';

    /**
     * @var date
     * @assert NotBlank(groups="PcaeProyectos")
     */
    protected $FechaFinPrevista = '0000-00-00';

    /**
     * @var string
     */
    protected $ResponsableAE;

    /**
     * @var string
     */
    protected $ResponsableCliente;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = 'pcae';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'PcaeProyectos';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'Id';

    /**
     * Relacion de entidades que dependen de esta
     * @var string
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PcaeProyectosApps', 'ParentColumn' => 'IdProyecto'),
        array('SourceColumn' => ' Id', 'ParentEntity' => 'PcaeVariables', 'ParentColumn' => 'IdProyectosApps'),
        array('SourceColumn' => ' Id', 'ParentEntity' => 'PcaeControlAcceso', 'ParentColumn' => 'IdProyectoApp'),
        array('SourceColumn' => ' Id', 'ParentEntity' => 'PcaePermisos', 'ParentColumn' => 'IdProyecto'),
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'PcaeEmpresas',
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

    public function setIdEmpresa($IdEmpresa) {
        $this->IdEmpresa = $IdEmpresa;
    }

    public function getIdEmpresa() {
        if (!($this->IdEmpresa instanceof PcaeEmpresas))
            $this->IdEmpresa = new PcaeEmpresas($this->IdEmpresa);
        return $this->IdEmpresa;
    }

    public function setProyecto($Proyecto) {
        $this->Proyecto = trim($Proyecto);
    }

    public function getProyecto() {
        return $this->Proyecto;
    }

    public function setPresupuesto($Presupuesto) {
        $this->Presupuesto = $Presupuesto;
    }

    public function getPresupuesto() {
        return $this->Presupuesto;
    }

    public function setHorasPrevistas($HorasPrevistas) {
        $this->HorasPrevistas = $HorasPrevistas;
    }

    public function getHorasPrevistas() {
        return $this->HorasPrevistas;
    }

    public function setHorasReales($HorasReales) {
        $this->HorasReales = $HorasReales;
    }

    public function getHorasReales() {
        return $this->HorasReales;
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

    public function setFechaFinPrevista($FechaFinPrevista) {
        $date = new Fecha($FechaFinPrevista);
        $this->FechaFinPrevista = $date->getFecha();
        unset($date);
    }

    public function getFechaFinPrevista() {
        $date = new Fecha($this->FechaFinPrevista);
        $ddmmaaaa = $date->getddmmaaaa();
        unset($date);
        return $ddmmaaaa;
    }

    public function setResponsableAE($ResponsableAE) {
        $this->ResponsableAE = trim($ResponsableAE);
    }

    public function getResponsableAE() {
        return $this->ResponsableAE;
    }

    public function setResponsableCliente($ResponsableCliente) {
        $this->ResponsableCliente = trim($ResponsableCliente);
    }

    public function getResponsableCliente() {
        return $this->ResponsableCliente;
    }

}

// END class PcaeProyectos
?>