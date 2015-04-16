<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 19.07.2014 02:26:08
 */

/**
 * @orm:Entity(ErpAvisadorStock)
 */
class AvisadorStockEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpAvisadorStock")
     */
    protected $Id;

    /**
     * @var entities\Clientes
     * @assert NotBlank(groups="ErpAvisadorStock")
     */
    protected $IDCliente = '0';

    /**
     * @var string
     * @assert NotBlank(groups="ErpAvisadorStock")
     */
    protected $Email;

    /**
     * @var entities\Articulos
     * @assert NotBlank(groups="ErpAvisadorStock")
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
    protected $_tableName = 'ErpAvisadorStock';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'Id';

    /**
     * Array con las columnas de la primarykey
     * @var array
     */
    protected $_arrayPrimaryKeys = array('Id');

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
        'Clientes',
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

    public function setIDCliente($IDCliente) {
        $this->IDCliente = $IDCliente;
    }

    public function getIDCliente() {
        if (!($this->IDCliente instanceof Clientes))
            $this->IDCliente = new Clientes($this->IDCliente);
        return $this->IDCliente;
    }

    public function setEmail($Email) {
        $this->Email = trim($Email);
    }

    public function getEmail() {
        return $this->Email;
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

// END class ErpAvisadorStock
