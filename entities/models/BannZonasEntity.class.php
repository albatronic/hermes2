<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.12.2012 09:27:03
 */

/**
 * @orm:Entity(BannZonas)
 */
class BannZonasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="BannZonas")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="BannZonas")
     */
    protected $Titulo;

    /**
     * @var integer
     * @assert NotBlank(groups="BannZonas")
     */
    protected $NumeroMaximoBanners = '1';

    /**
     * @var integer
     * @assert NotBlank(groups="BannZonas")
     */
    protected $NumeroPosicionesBanners = '1';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'BannZonas*';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'BannBanners', 'ParentColumn' => 'IdZona'),
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

    public function setNumeroMaximoBanners($NumeroMaximoBanners) {
        $this->NumeroMaximoBanners = $NumeroMaximoBanners;
    }

    public function getNumeroMaximoBanners() {
        return $this->NumeroMaximoBanners;
    }

    public function setNumeroPosicionesBanners($NumeroPosicionesBanners) {
        $this->NumeroPosicionesBanners = $NumeroPosicionesBanners;
    }

    public function getNumeroPosicionesBanners() {
        return $this->NumeroPosicionesBanners;
    }

}

// END class BannZonas
?>