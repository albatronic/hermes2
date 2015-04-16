<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 25.12.2012 13:35:08
 */

/**
 * @orm:Entity(VidVideos)
 */
class VidVideosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="VidVideos")
     */
    protected $Id;

    /**
     * @var entities\VidSecciones
     * @assert NotBlank(groups="VidVideos")
     */
    protected $IdSeccion;

    /**
     * @var string
     * @assert NotBlank(groups="VidVideos")
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
    protected $Autor;

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="VidVideos")
     */
    protected $MostrarEnPortada = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="VidVideos")
     */
    protected $OrdenPortada = '0';

    /**
     * @var string
     */
    protected $UrlVideo;

    /**
     * @var entities\TiposVideos
     * @assert NotBlank(groups="VidVideos")
     */
    protected $IdTipo = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'VidVideos*';

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
        'VidSecciones',
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

    public function setIdSeccion($IdSeccion) {
        $this->IdSeccion = $IdSeccion;
    }

    public function getIdSeccion() {
        if (!($this->IdSeccion instanceof VidSecciones))
            $this->IdSeccion = new VidSecciones($this->IdSeccion);
        return $this->IdSeccion;
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

    public function setAutor($Autor) {
        $this->Autor = trim($Autor);
    }

    public function getAutor() {
        return $this->Autor;
    }

    public function setMostrarEnPortada($MostrarEnPortada) {
        $this->MostrarEnPortada = $MostrarEnPortada;
    }

    public function getMostrarEnPortada() {
        if (!($this->MostrarEnPortada instanceof ValoresSN))
            $this->MostrarEnPortada = new ValoresSN($this->MostrarEnPortada);
        return $this->MostrarEnPortada;
    }

    public function setOrdenPortada($OrdenPortada) {
        $this->OrdenPortada = $OrdenPortada;
    }

    public function getOrdenPortada() {
        return $this->OrdenPortada;
    }

    public function setUrlVideo($UrlVideo) {
        $this->UrlVideo = trim($UrlVideo);
    }

    public function getUrlVideo() {
        return $this->UrlVideo;
    }

    public function setIdTipo($IdTipo) {
        $this->IdTipo = $IdTipo;
    }

    public function getIdTipo() {
        if (!($this->IdTipo instanceof TiposVideos))
            $this->IdTipo = new TiposVideos($this->IdTipo);
        return $this->IdTipo;
    }

}

// END class VidVideos
?>