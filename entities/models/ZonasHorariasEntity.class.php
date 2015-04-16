<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 14:24:31
 */

/**
 * @orm:Entity(CommZonasHorarias)
 */
class ZonasHorariasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm IDZona
     * @var integer
     * @assert NotBlank(groups="CommZonasHorarias")
     */
    protected $IDZona;

    /**
     * @var string
     */
    protected $Zona = '';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpZonasHorarias';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDZona';

    /**
     * Relacion de entidades que dependen de esta
     * @var string
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDZona', 'ParentEntity' => 'Paises', 'ParentColumn' => 'IDZonaHoraria'),
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
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDZona($IDZona) {
        $this->IDZona = $IDZona;
    }

    public function getIDZona() {
        return $this->IDZona;
    }

    public function setZona($Zona) {
        $this->Zona = trim($Zona);
    }

    public function getZona() {
        return $this->Zona;
    }

}

// END class CommZonasHorarias
?>