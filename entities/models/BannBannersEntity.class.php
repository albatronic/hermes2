<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.12.2012 09:27:33
 */

/**
 * @orm:Entity(BannBanners)
 */
class BannBannersEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="BannBanners")
     */
    protected $Id;

    /**
     * @var entities\BannZonas
     * @assert NotBlank(groups="BannBanners")
     */
    protected $IdZona;

    /**
     * @var string
     * @assert NotBlank(groups="BannBanners")
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
     * @var tinyint
     * @assert NotBlank(groups="BannBanners")
     */
    protected $IdTipo = '0';

    /**
     * @var tinyint
     * @assert NotBlank(groups="BannBanners")
     */
    protected $MostrarEnListado = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="BannBanners")
     */
    protected $OrdenMostrarEnListado = '0';

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
    protected $_tableName = 'BannBanners*';

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
        'BannZonas',
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
        if (!($this->IdZona instanceof BannZonas))
            $this->IdZona = new BannZonas($this->IdZona);
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
        if (!($this->IdTipo instanceof TiposBanners))
            $this->IdTipo = new TiposBanners($this->IdTipo);
        return $this->IdTipo;
    }

    public function setMostrarEnListado($MostrarEnListado) {
        $this->MostrarEnListado = $MostrarEnListado;
    }

    public function getMostrarEnListado() {
        if (!($this->MostrarEnListado instanceof ValoresSN))
            $this->MostrarEnListado = new ValoresSN($this->MostrarEnListado);
        return $this->MostrarEnListado;
    }

    public function setOrdenMostrarEnListado($OrdenMostrarEnListado) {
        $this->OrdenMostrarEnListado = $OrdenMostrarEnListado;
    }

    public function getOrdenMostrarEnListado() {
        return $this->OrdenMostrarEnListado;
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

// END class BannBanners
?>