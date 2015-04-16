<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 20.06.2014 18:25:52
 */

/**
 * @orm:Entity(ErpLotesWeb)
 */
class LotesWebEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpLotesWeb")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="ErpLotesWeb")
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
    protected $Desarrollo;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="ErpFamilias")
     */
    protected $MostrarEnPortada = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="ErpFamilias")
     */
    protected $OrdenPortada = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpLotesWeb';

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

    public function setDesarrollo($Desarrollo) {
        $this->Desarrollo = trim($Desarrollo);
    }

    public function getDesarrollo() {
        return $this->Desarrollo;
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

}

// END class ErpLotesWeb
