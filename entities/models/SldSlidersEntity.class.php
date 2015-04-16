<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 10.12.2012 17:38:34
 */

/**
 * @orm:Entity(SldSliders)
 */
class SldSlidersEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="SldSliders")
     */
    protected $Id;

    /**
     * @var entities\SldZonas
     * @assert NotBlank(groups="SldSliders")
     */
    protected $IdZona;

    /**
     * @var string
     * @assert NotBlank(groups="SldSliders")
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
     * @var entities\TiposSliders
     * @assert NotBlank(groups="SldSliders")
     */
    protected $IdTipo = '0';

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="SldSliders")
     */
    protected $MostrarTextos = '0';

    /**
     * @var string
     * @assert NotBlank(groups="SldSliders")
     */
    protected $Entidad = '';

    /**
     * @var integer
     * @assert NotBlank(groups="SldSliders")
     */
    protected $IdEntidad = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'SldSliders*';

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
        'SldZonas',
        'TiposSliders',
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

    public function setIdZona($IdZona) {
        $this->IdZona = $IdZona;
    }

    public function getIdZona() {
        if (!($this->IdZona instanceof SldZonas))
            $this->IdZona = new SldZonas($this->IdZona);
        return $this->IdZona;
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

    public function setIdTipo($IdTipo) {
        $this->IdTipo = $IdTipo;
    }

    public function getIdTipo() {
        if (!($this->IdTipo instanceof TiposSliders))
            $this->IdTipo = new TiposSliders($this->IdTipo);
        return $this->IdTipo;
    }

    public function setMostrarTextos($MostrarTextos) {
        $this->MostrarTextos = $MostrarTextos;
    }

    public function getMostrarTextos() {
        if (!($this->MostrarTextos instanceof ValoresSN))
            $this->MostrarTextos = new ValoresSN($this->MostrarTextos);
        return $this->MostrarTextos;
    }

    public function setEntidad($Entidad) {
        $this->Entidad = trim($Entidad);
    }

    public function getEntidad() {
        return $this->Entidad;
    }

    public function setIdEntidad($IdEntidad) {
        $this->IdEntidad = $IdEntidad;
    }

    public function getIdEntidad() {
        return $this->IdEntidad;
    }

}

// END class SldSliders
?>