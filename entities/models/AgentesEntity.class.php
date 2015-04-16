<?php

/**
 * Usuarios del sistema
 * 
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 19:20:35
 */

/**
 * @orm:Entity(agentes)
 */
class AgentesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm IDAgente
     * @var integer
     * @assert NotBlank(groups="CpanUsuarios")
     */
    protected $IDAgente;

    /**
     * @var entities\CpanPerfiles
     * @assert NotBlank(groups="CpanUsuarios")
     */
    protected $IDPerfil;

    /**
     * @var entities\CpanRoles
     * @assert NotBlank(groups="CpanUsuarios")
     */
    protected $IDRol;

    /**
     * @var entities\CpanUsuariosTipos
     * @assert NotBlank(groups="CpanUsuarios")
     */
    protected $IDTipo = 0;

    /**
     * @orm:Column(type="integer")
     */
    protected $IDSucursal = 1;

    /**
     * @orm:Column(type="integer")
     */
    protected $IDAlmacen = 1;
    
    /**
     * @orm:Column(type="integer")
     */
    protected $Activo = 1;    

    /**
     * Para almacenar temporalmente la
     * repeticion de la password
     * @var string
     */
    protected $_repitePassword;

    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpUsuarios';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDAgente';

    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'ClientesDentrega', 'ParentColumn' => 'IDComercial'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'Clientes', 'ParentColumn' => 'IDComercial'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'AlbaranesCab', 'ParentColumn' => 'IDAgente'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'AlbaranesCab', 'ParentColumn' => 'IDComercial'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'PstoCab', 'ParentColumn' => 'IDAgente'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'PstoCab', 'ParentColumn' => 'IDComercial'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'Sucursales', 'ParentColumn' => 'IDResponsable'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'MvtosAlmacen', 'ParentColumn' => 'IDAgente'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'Expediciones', 'ParentColumn' => 'IDAlmacenero'),
        array('SourceColumn' => 'IDAgente', 'ParentEntity' => 'Expediciones', 'ParentColumn' => 'IDRepartidor'),
    );

    /**
     * GETTERS Y SETTERS
     */

    public function setIDAgente($IDAgente) {
        $this->IDAgente = $IDAgente;
    }

    public function getIDAgente() {
        if (!($this->IDAgente instanceof PcaeUsuarios))
            $this->IDAgente = new PcaeUsuarios($this->IDAgente);
        return $this->IDAgente;
    }

    public function setIDPerfil($IDPerfil) {
        $this->IDPerfil = $IDPerfil;
    }

    public function getIDPerfil() {
        if (!($this->IDPerfil instanceof Perfiles))
            $this->IDPerfil = new Perfiles($this->IDPerfil);
        return $this->IDPerfil;
    }

    public function setIDRol($IDRol) {
        $this->IDRol = $IDRol;
    }

    public function getIDRol() {
        if (!($this->IDRol instanceof Roles))
            $this->IDRol = new Roles($this->IDRol);
        return $this->IDRol;
    }

    public function setIDTipo($IDTipo) {
        $this->IDTipo = $IDTipo;
    }

    public function getIDTipo() {
        if (!($this->IDTipo instanceof TiposUsuarios))
            $this->IDTipo = new TiposUsuarios($this->IDTipo);
        return $this->IDTipo;
    }

    public function setIDSucursal($IDSucursal) {
        $this->IDSucursal = $IDSucursal;
    }

    public function getIDSucursal() {
        if (!($this->IDSucursal instanceof Sucursales))
            $this->IDSucursal = new Sucursales($this->IDSucursal);
        return $this->IDSucursal;
    }

    public function setIDAlmacen($IDAlmacen) {
        $this->IDAlmacen = $IDAlmacen;
    }

    public function getIDAlmacen() {
        if (!($this->IDAlmacen instanceof Almacenes))
            $this->IDAlmacen = new Almacenes($this->IDAlmacen);
        return $this->IDAlmacen;
    }

    public function setActivo($Activo) {
        $this->Activo = $Activo;
    }

    public function getActivo() {
        if (!($this->Activo instanceof ValoresSN))
            $this->Activo = new ValoresSN($this->Activo);
        return $this->Activo;
    }

}

// END class agentes
?>