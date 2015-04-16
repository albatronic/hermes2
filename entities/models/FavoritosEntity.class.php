<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.10.2013 14:52:11
 */

/**
 * @orm:Entity(Favoritos)
 */
class FavoritosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="Favoritos")
     */
    protected $Id;

    /**
     * @var integer
     * @assert NotBlank(groups="Favoritos")
     */
    protected $IDUsuario;

    /**
     * @var string
     * @assert NotBlank(groups="Favoritos")
     */
    protected $Controller;

    /**
     * @var string
     * @assert NotBlank(groups="Favoritos")
     */
    protected $Titulo;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpFavoritos';

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

    public function setIDUsuario($IDUsuario) {
        $this->IDUsuario = $IDUsuario;
    }

    public function getIDUsuario() {
        return $this->IDUsuario;
    }

    public function setController($Controller) {
        $this->Controller = trim($Controller);
    }

    public function getController() {
        return $this->Controller;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = trim($Titulo);
    }

    public function getTitulo() {
        return $this->Titulo;
    }

}

// END class Favoritos
?>