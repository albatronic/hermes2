<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 11.07.2013 19:17:44
 */

/**
 * @orm:Entity(BolFormatos)
 */
class BolFormatosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="BolFormatos")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="BolFormatos")
     */
    protected $Titulo;

    /**
     * @var entities\ValoresPrivacy
     * @assert NotBlank(groups="BolFormatos")
     */
    protected $PrivacidadContenidos = '0';

    /**
     * @var string
     * @assert NotBlank(groups="BolFormatos")
     */
    protected $PlantillaHtm = 'plantillaBoletin1.htm';
    
    /**
     * @var string
     */
    protected $Css;

    /**
     * @var string
     */
    protected $Html;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'BolFormatos';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'BolBoletines', 'ParentColumn' => 'IDFormato'),
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'ValoresPrivacy',
        'ValoresSN',
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

    public function setPrivacidadContenidos($PrivacidadContenidos) {
        $this->PrivacidadContenidos = $PrivacidadContenidos;
    }

    public function getPrivacidadContenidos() {
        if (!($this->PrivacidadContenidos instanceof ValoresPrivacy))
            $this->PrivacidadContenidos = new ValoresPrivacy($this->PrivacidadContenidos);
        return $this->PrivacidadContenidos;
    }

    public function setPlantillaHtm($PlantillaHtm) {
        $this->PlantillaHtm = trim($PlantillaHtm);
    }

    public function getPlantillaHtm() {
        return $this->PlantillaHtm;
    }
    
    public function setCss($Css) {
        $this->Css = trim($Css);
    }

    public function getCss() {
        return $this->Css;
    }

    public function setHtml($Html) {
        $this->Html = trim($Html);
    }

    public function getHtml() {
        return $this->Html;
    }

}

// END class BolFormatos
?>