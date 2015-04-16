<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.02.2013 23:09:18
 */

/**
 * @orm:Entity(ServServicios)
 */
class ServServiciosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ServServicios")
     */
    protected $Id;

    /**
     * @var entities\ServFamilias
     * @assert NotBlank(groups="ServServicios")
     */
    protected $IdFamilia;

    /**
     * @var string
     * @assert NotBlank(groups="ServServicios")
     */
    protected $Titulo;

    /**
     * @var string
     */
    protected $Subtitulo;

    /**
     * @var string
     */
    protected $Resumen;

    /**
     * @var string
     */
    protected $ResumenBreve;

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="ServServicios")
     */
    protected $MostrarPortada = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ServServicios")
     */
    protected $MostrarPortadaOrden = '0';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="ServServicios")
     */
    protected $MostrarEnListado = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ServServicios")
     */
    protected $MostrarEnListadoOrden = '0';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="ServServicios")
     */
    protected $TieneDesarrollo = '0';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="ServServicios")
     */
    protected $MuestraEjemplos = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ServServicios*';

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
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'ServFamilias',
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

    public function setIdFamilia($IdFamilia) {
        $this->IdFamilia = $IdFamilia;
    }

    public function getIdFamilia() {
        if (!($this->IdFamilia instanceof ServFamilias))
            $this->IdFamilia = new ServFamilias($this->IdFamilia);
        return $this->IdFamilia;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = trim($Titulo);
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setSubtitulo($Subtitulo) {
        $this->Subtitulo = trim($Subtitulo);
    }

    public function getSubtitulo() {
        return $this->Subtitulo;
    }

    public function setResumen($Resumen) {
        $this->Resumen = trim($Resumen);
    }

    public function getResumen() {
        return $this->Resumen;
    }

    public function setResumenBreve($ResumenBreve) {
        $this->ResumenBreve = trim($ResumenBreve);
    }

    public function getResumenBreve() {
        return $this->ResumenBreve;
    }

    public function setMostrarPortada($MostrarPortada) {
        $this->MostrarPortada = $MostrarPortada;
    }

    public function getMostrarPortada() {
        if (!($this->MostrarPortada instanceof ValoresSN))
            $this->MostrarPortada = new ValoresSN($this->MostrarPortada);
        return $this->MostrarPortada;
    }

    public function setMostrarPortadaOrden($MostrarPortadaOrden) {
        $this->MostrarPortadaOrden = $MostrarPortadaOrden;
    }

    public function getMostrarPortadaOrden() {
        return $this->MostrarPortadaOrden;
    }

    public function setMostrarEnListado($MostrarEnListado) {
        $this->MostrarEnListado = $MostrarEnListado;
    }

    public function getMostrarEnListado() {
        if (!($this->MostrarEnListado instanceof ValoresSN))
            $this->MostrarEnListado = new ValoresSN($this->MostrarEnListado);
        return $this->MostrarEnListado;
    }

    public function setMostrarEnListadoOrden($MostrarEnListadoOrden) {
        $this->MostrarEnListadoOrden = $MostrarEnListadoOrden;
    }

    public function getMostrarEnListadoOrden() {
        return $this->MostrarEnListadoOrden;
    }

    public function setTieneDesarrollo($TieneDesarrollo) {
        $this->TieneDesarrollo = $TieneDesarrollo;
    }

    public function getTieneDesarrollo() {
        if (!($this->TieneDesarrollo instanceof ValoresSN))
            $this->TieneDesarrollo = new ValoresSN($this->TieneDesarrollo);
        return $this->TieneDesarrollo;
    }

    public function setMuestraEjemplos($MuestraEjemplos) {
        $this->MuestraEjemplos = $MuestraEjemplos;
    }

    public function getMuestraEjemplos() {
        if (!($this->MuestraEjemplos instanceof ValoresSN))
            $this->MuestraEjemplos = new ValoresSN($this->MuestraEjemplos);
        return $this->MuestraEjemplos;
    }

}

// END class ServServicios
?>