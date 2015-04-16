<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 11.07.2013 19:20:35
 */

/**
 * @orm:Entity(BolBoletines)
 */
class BolBoletinesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="BolBoletines")
     */
    protected $Id;

    /**
     * @var entities\BolTipos
     * @assert NotBlank(groups="BolBoletines")
     */
    protected $IDTipo;

    /**
     * @var string
     * @assert NotBlank(groups="BolBoletines")
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
     * @var entities\BolFormatos
     * @assert NotBlank(groups="BolBoletines")
     */
    protected $IDFormato;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'BolBoletines';

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
        'BolTipos',
        'BolFormatos',
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

    public function setIDTipo($IDTipo) {
        $this->IDTipo = $IDTipo;
    }

    public function getIDTipo() {
        if (!($this->IDTipo instanceof BolTipos))
            $this->IDTipo = new BolTipos($this->IDTipo);
        return $this->IDTipo;
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

    public function setIDFormato($IDFormato) {
        $this->IDFormato = $IDFormato;
    }

    public function getIDFormato() {
        if (!($this->IDFormato instanceof BolFormatos))
            $this->IDFormato = new BolFormatos($this->IDFormato);
        return $this->IDFormato;
    }

}

// END class BolBoletines
?>