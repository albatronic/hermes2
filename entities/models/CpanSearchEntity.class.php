<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 18.04.2013 13:10:03
 */

/**
 * @orm:Entity(CpanSearch)
 */
class CpanSearchEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="CpanSearch")
     */
    protected $Id;

    /**
     * @var integer
     * @assert NotBlank(groups="CpanSearch")
     */
    protected $Texto;

    /**
     * @var string
     * @assert NotBlank(groups="CpanSearch")
     */
    protected $Entity;

    /**
     * @var integer
     * @assert NotBlank(groups="CpanSearch")
     */
    protected $IdEntity;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'CpanSearch*';

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

    public function setTexto($Texto) {
        $this->Texto = $Texto;
    }

    public function getTexto() {
        return $this->Texto;
    }

    public function setEntity($Entity) {
        $this->Entity = trim($Entity);
    }

    public function getEntity() {
        return $this->Entity;
    }

    public function setIdEntity($IdEntity) {
        $this->IdEntity = $IdEntity;
    }

    public function getIdEntity() {
        return $this->IdEntity;
    }

}

// END class CpanSearch
?>