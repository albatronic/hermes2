<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:48
 */

/**
 * @orm:Entity(ZonasTransporte)
 */
class ZonasTransporteEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="ZonasTransporte")
     */
    protected $IDZona;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="ZonasTransporte")
     */
    protected $Zona = '';

    /**
     * @orm:Column(type="tinyint")
     * @assert:NotBlank(groups="ZonasTransporte")
     */
    protected $Uso = '0';

    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpZonasTransporte';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDZona';

    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDZona', 'ParentEntity' => 'Provincias', 'ParentColumn' => 'IDZona'),
        array('SourceColumn' => 'IDZona', 'ParentEntity' => 'TablaPortes', 'ParentColumn' => 'IDZona'),
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

    public function setUso($Uso) {
        $this->Uso = trim($Uso);
    }

    public function getUso() {
        if (!($this->Uso instanceof UsoWeb))
            $this->Uso = new UsoWeb($this->Uso);
        return $this->Uso;
    }

}

// END class ZonasTransporte
?>