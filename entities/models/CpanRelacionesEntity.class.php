<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 01.02.2013 20:16:29
 */

/**
 * @orm:Entity(CpanRelaciones)
 */
class CpanRelacionesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="CpanRelaciones")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="CpanRelaciones")
     */
    protected $EntidadOrigen;

    /**
     * @var integer
     * @assert NotBlank(groups="CpanRelaciones")
     */
    protected $IdEntidadOrigen = '0';

    /**
     * @var string
     * @assert NotBlank(groups="CpanRelaciones")
     */
    protected $EntidadDestino;

    /**
     * @var integer
     * @assert NotBlank(groups="CpanRelaciones")
     */
    protected $IdEntidadDestino = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'CpanRelaciones';

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

    public function setEntidadOrigen($EntidadOrigen) {
        $this->EntidadOrigen = trim($EntidadOrigen);
    }

    public function getEntidadOrigen() {
        return $this->EntidadOrigen;
    }

    public function setIdEntidadOrigen($IdEntidadOrigen) {
        $this->IdEntidadOrigen = $IdEntidadOrigen;
    }

    public function getIdEntidadOrigen() {
        return $this->IdEntidadOrigen;
    }

    public function setEntidadDestino($EntidadDestino) {
        $this->EntidadDestino = trim($EntidadDestino);
    }

    public function getEntidadDestino() {
        return $this->EntidadDestino;
    }

    public function setIdEntidadDestino($IdEntidadDestino) {
        $this->IdEntidadDestino = $IdEntidadDestino;
    }

    public function getIdEntidadDestino() {
        return $this->IdEntidadDestino;
    }

}

// END class CpanRelaciones
?>