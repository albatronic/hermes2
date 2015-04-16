<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 21.09.2013 15:15:26
 */

/**
 * @orm:Entity(CpanMetaData)
 */
class CpanMetaDataEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="CpanMetaData")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="CpanMetaData")
     */
    protected $Entity;

    /**
     * @var integer
     * @assert NotBlank(groups="CpanMetaData")
     */
    protected $IdEntity;

    /**
     * @var string
     * @assert NotBlank(groups="CpanMetaData")
     */
    protected $Name;

    /**
     * @var string
     */
    protected $Value;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'CpanMetaData';

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

    public function setName($Name) {
        $this->Name = trim($Name);
    }

    public function getName() {
        return $this->Name;
    }

    public function setValue($Value) {
        $this->Value = trim($Value);
    }

    public function getValue() {
        return $this->Value;
    }

}

// END class CpanMetaData
?>