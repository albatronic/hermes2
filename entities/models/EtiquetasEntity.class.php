<?php

/**
 * Etiquetas
 * 
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 18:39:47
 */

/**
 * @orm:Entity(etiquetas)
 */
class EtiquetasEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="etiquetas")
     */
    protected $Id;
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="etiquetas")
     */
    protected $IDAgente;
    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="etiquetas")
     */
    protected $IDArticulo;
    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="etiquetas")
     */
    protected $Descripcion;
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="etiquetas")
     */
    protected $Unidades;
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="etiquetas")
     */
    protected $Precio;
    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';
    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpEtiquetas';
    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'Id';

    /**
     * GETTERS Y SETTERS
     */
    public function setId($Id) {
        $this->Id = $Id;
    }

    public function getId() {
        return $this->Id;
    }

    public function setIDAgente($IDAgente) {
        $this->IDAgente = $IDAgente;
    }

    public function getIDAgente() {
        if (!($this->IDAgente instanceof Agentes))
            $this->IDAgente = new Agentes($this->IDAgente);
        return $this->IDAgente;
    }

    public function setIDArticulo($IDArticulo) {
        $this->IDArticulo = $IDArticulo;
    }

    public function getIDArticulo() {
        if (!($this->IDArticulo instanceof Articulos))
            $this->IDArticulo = new Articulos($this->IDArticulo);
        return $this->IDArticulo;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function setUnidades($Unidades) {
        $this->Unidades = $Unidades;
    }

    public function getUnidades() {
        return $this->Unidades;
    }

    public function setPrecio($Precio) {
        $this->Precio = $Precio;
    }

    public function getPrecio() {
        return $this->Precio;
    }

}

// END class etiquetas
?>