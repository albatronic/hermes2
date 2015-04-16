<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 13.11.2013 23:33:08
 */

/**
 * @orm:Entity(FavoritosTpv)
 */
class FavoritosTpvEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="FavoritosTpv")
     */
    protected $Id;

    /**
     * @var integer
     * @assert NotBlank(groups="FavoritosTpv")
     */
    protected $IDTpv;

    /**
     * @var integer
     * @assert NotBlank(groups="FavoritosTpv")
     */
    protected $IDArticulo;

    /**
     * @var string
     * @assert NotBlank(groups="FavoritosTpv")
     */
    protected $Descripcion;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpFavoritosTpv';

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

    public function setIDTpv($IDTpv) {
        $this->IDTpv = $IDTpv;
    }

    public function getIDTpv() {
        return $this->IDTpv;
    }

    public function setIDArticulo($IDArticulo) {
        $this->IDArticulo = $IDArticulo;
    }

    public function getIDArticulo() {
        return $this->IDArticulo;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = trim($Descripcion);
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

}

// END class FavoritosTpv
?>