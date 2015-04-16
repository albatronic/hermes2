<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 10.12.2012 17:38:03
 */

/**
 * @orm:Entity(SldZonas)
 */
class SldZonasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="SldZonas")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="SldZonas")
     */
    protected $Titulo;

    /**
     * @var integer
     * @assert NotBlank(groups="SldZonas")
     */
    protected $NumeroMaximoSliders = '1';

    /**
     * @var integer
     * @assert NotBlank(groups="SldZonas")
     */
    protected $NumeroPosicionesSliders = '1';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'SldZonas*';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'SldSliders', 'ParentColumn' => 'IdZona'),
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

    public function setNumeroMaximoSliders($NumeroMaximoSliders) {
        $this->NumeroMaximoSliders = $NumeroMaximoSliders;
    }

    public function getNumeroMaximoSliders() {
        return $this->NumeroMaximoSliders;
    }

    public function setNumeroPosicionesSliders($NumeroPosicionesSliders) {
        $this->NumeroPosicionesSliders = $NumeroPosicionesSliders;
    }

    public function getNumeroPosicionesSliders() {
        return $this->NumeroPosicionesSliders;
    }

}

// END class SldZonas
?>