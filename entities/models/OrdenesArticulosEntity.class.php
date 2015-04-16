<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.02.2013 13:00:03
 */

/**
 * @orm:Entity(OrdenesArticulos)
 */
class OrdenesArticulosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="OrdenesArticulos")
     */
    protected $Id;

    /**
     * @var integer
     * @assert NotBlank(groups="OrdenesArticulos")
     */
    protected $IDRegla = '0';

    /**
     * @var entities\Articulos
     * @assert NotBlank(groups="OrdenesArticulos")
     */
    protected $IDArticulo = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpOrdenesArticulos';

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
        'Articulos',
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

    public function setIDRegla($IDRegla) {
        $this->IDRegla = $IDRegla;
    }

    public function getIDRegla() {
        return $this->IDRegla;
    }

    public function setIDArticulo($IDArticulo) {
        $this->IDArticulo = $IDArticulo;
    }

    public function getIDArticulo() {
        if (!($this->IDArticulo instanceof Articulos))
            $this->IDArticulo = new Articulos($this->IDArticulo);
        return $this->IDArticulo;
    }

}

// END class OrdenesArticulos
?>