<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 17:43:37
 */

/**
 * @orm:Entity(CpanPlantillas)
 */
class CpanPlantillasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="CpanPlantillas")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="CpanPlantillas")
     */
    protected $Lang = 'es';

    /**
     * @var string
     * @assert NotBlank(groups="CpanPlantillas")
     */
    protected $Controller;

    /**
     * @var string
     * @assert NotBlank(groups="CpanPlantillas")
     */
    protected $Clave;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'CpanPlantillas';

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

    public function setLang($Lang) {
        $this->Lang = trim($Lang);
    }

    public function getLang() {
        return $this->Lang;
    }

    public function setController($Controller) {
        $this->Controller = trim($Controller);
    }

    public function getController() {
        return $this->Controller;
    }

    public function setClave($Clave) {
        $this->Clave = trim($Clave);
    }

    public function getClave() {
        return $this->Clave;
    }

}

// END class CpanPlantillas
?>