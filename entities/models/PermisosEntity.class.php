<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 22.06.2011 00:43:35
 */

/**
 * @orm:Entity(permisos)
 */
class PermisosEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="permisos")
     */
    protected $Id;
    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="permisos")
     */
    protected $IdPerfil;
    /**
     * @orm:Column(type="string")
     */
    protected $NombreModulo;
    /**
     * @orm:Column(type="string")
     */
    protected $Funcionalidades;
    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';
    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPermisos';
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

    public function setIdPerfil($IdPerfil) {
        $this->IdPerfil = $IdPerfil;
    }

    public function getIdPerfil() {
        return $this->IdPerfil;
    }

    public function setNombreModulo($NombreModulo) {
        $this->NombreModulo = $NombreModulo;
    }

    public function getNombreModulo() {
        return $this->NombreModulo;
    }

    public function setFuncionalidades($Funcionalidades) {
        $this->Funcionalidades = $Funcionalidades;
    }

    public function getFuncionalidades() {
        return $this->Funcionalidades;
    }

}

// END class permisos
?>