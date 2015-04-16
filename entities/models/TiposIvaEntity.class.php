<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:48
 */

/**
 * @orm:Entity(TiposIva)
 */
class TiposIvaEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="TiposIva")
     */
    protected $IDIva;
    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="TiposIva")
     */
    protected $Tipo;
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="TiposIva")
     */
    protected $Iva = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="TiposIva")
     */
    protected $Recargo = '0.00';
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="TiposIva")
     */
    protected $Retencion = '0.00';
    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';
    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpTiposIva';
    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDIva';
    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDIva', 'ParentEntity' => 'Articulos', 'ParentColumn' => 'IDIva'),
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDIva($IDIva) {
        $this->IDIva = $IDIva;
    }

    public function getIDIva() {
        return $this->IDIva;
    }

    public function setTipo($Tipo) {
        $this->Tipo = trim($Tipo);
    }

    public function getTipo() {
        return $this->Tipo;
    }

    public function setIva($Iva) {
        $this->Iva = $Iva;
    }

    public function getIva() {
        return $this->Iva;
    }

    public function setRecargo($Recargo) {
        $this->Recargo = $Recargo;
    }

    public function getRecargo() {
        return $this->Recargo;
    }

    public function setRetencion($Retencion) {
        $this->Retencion = $Retencion;
    }

    public function getRetencion() {
        return $this->Retencion;
    }

}

// END class TiposIva
?>