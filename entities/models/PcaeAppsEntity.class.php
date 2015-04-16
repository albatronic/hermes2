<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 17:08:48
 */

/**
 * @orm:Entity(PcaeApps)
 */
class PcaeAppsEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="PcaeApps")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeApps")
     */
    protected $Aplicacion;

    /**
     * @var string
     * @assert NotBlank(groups="PcaeApps")
     */
    protected $Url;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = 'pcae';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'PcaeApps';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PcaeProyectosApps', 'ParentColumn' => 'IdApp'),
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PcaePermisos', 'ParentColumn' => 'IdApp'),
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
    public function setId($Id) {
        $this->Id = $Id;
    }

    public function getId() {
        return $this->Id;
    }

    public function setAplicacion($Aplicacion) {
        $this->Aplicacion = trim($Aplicacion);
    }

    public function getAplicacion() {
        return $this->Aplicacion;
    }

    public function setUrl($Url) {
        $this->Url = trim($Url);
    }

    public function getUrl() {
        return $this->Url;
    }

}

// END class PcaeApps
?>