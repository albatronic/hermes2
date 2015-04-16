<?php

/**
 * Fabricantes
 * 
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:47
 */

/**
 * @orm:Entity(fabricantes)
 */
class FabricantesEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="fabricantes")
     */
    protected $IDFabricante;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="fabricantes")
     */
    protected $Titulo = '';

    /**
     * @var string
     */
    protected $Descripcion1;

    /**
     * @var string
     */
    protected $Descripcion2;

    /**
     * @orm:Column(type="string")
     */
    protected $Telefono;

    /**
     * @orm:Column(type="string")
     */
    protected $Web;

    /**
     * @orm:Column(type="string")
     */
    protected $Email;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="ErpFamilias")
     */
    protected $MostrarPortada = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="ErpFamilias")
     */
    protected $OrdenPortada = '0';    

    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpFabricantes';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDFabricante';

    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDFabricante', 'ParentEntity' => 'Articulos', 'ParentColumn' => 'IDFabricante'),
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDFabricante($IDFabricante) {
        $this->IDFabricante = $IDFabricante;
    }

    public function getIDFabricante() {
        return $this->IDFabricante;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = trim($Titulo);
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setDescripcion1($Descripcion1) {
        $this->Descripcion1 = trim($Descripcion1);
    }

    public function getDescripcion1() {
        return $this->Descripcion1;
    }

    public function setDescripcion2($Descripcion2) {
        $this->Descripcion2 = trim($Descripcion2);
    }

    public function getDescripcion2() {
        return $this->Descripcion2;
    }

    public function setTelefono($Telefono) {
        $this->Telefono = trim($Telefono);
    }

    public function getTelefono() {
        return $this->Telefono;
    }

    public function setWeb($Web) {
        $this->Web = trim($Web);
    }

    public function getWeb() {
        return $this->Web;
    }

    public function setEmail($Email) {
        $this->Email = trim($Email);
    }

    public function getEmail() {
        return $this->Email;
    }

    public function setMostrarPortada($MostrarPortada) {
        $this->MostrarPortada = $MostrarPortada;
    }

    public function getMostrarPortada() {
        if (!($this->MostrarPortada instanceof ValoresSN))
            $this->MostrarPortada = new ValoresSN($this->MostrarPortada);
        return $this->MostrarPortada;
    }

    public function setOrdenPortada($OrdenPortada) {
        $this->OrdenPortada = $OrdenPortada;
    }

    public function getOrdenPortada() {
        return $this->OrdenPortada;
    }
}

// END class fabricantes
?>