<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 24.09.2013 23:03:00
 */

/**
 * @orm:Entity(ArticulosEscandallos)
 */
class ArticulosEscandallosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $Id;

    /**
     * @var entities\Articulos
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $IDArticuloOrigen;

    /**
     * @var entities\Articulos
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $IDArticuloDestino;

    /**
     * @var integer
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $Unidades = '1.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $ImporteCosto = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $ImporteVenta = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $Peso = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpArticulosEscandallos")
     */
    protected $Volumen = '0.00';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpArticulosEscandallos';

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

    public function setIDArticuloOrigen($IDArticuloOrigen) {
        $this->IDArticuloOrigen = $IDArticuloOrigen;
    }

    public function getIDArticuloOrigen() {
        if (!($this->IDArticuloOrigen instanceof Articulos))
            $this->IDArticuloOrigen = new Articulos($this->IDArticuloOrigen);
        return $this->IDArticuloOrigen;
    }

    public function setIDArticuloDestino($IDArticuloDestino) {
        $this->IDArticuloDestino = $IDArticuloDestino;
    }

    public function getIDArticuloDestino() {
        if (!($this->IDArticuloDestino instanceof Articulos))
            $this->IDArticuloDestino = new Articulos($this->IDArticuloDestino);
        return $this->IDArticuloDestino;
    }

    public function setUnidades($Unidades) {
        $this->Unidades = $Unidades;
    }

    public function getUnidades() {
        return $this->Unidades;
    }

    public function setImporteCosto($ImporteCosto) {
        $this->ImporteCosto = $ImporteCosto;
    }

    public function getImporteCosto() {
        return $this->ImporteCosto;
    }

    public function setImporteVenta($ImporteVenta) {
        $this->ImporteVenta = $ImporteVenta;
    }

    public function getImporteVenta() {
        return $this->ImporteVenta;
    }

    public function setPeso($Peso) {
        $this->Peso = $Peso;
    }

    public function getPeso() {
        return $this->Peso;
    }

    public function setVolumen($Volumen) {
        $this->Volumen = $Volumen;
    }

    public function getVolumen() {
        return $this->Volumen;
    }

}

// END class ErpArticulosEscandallos
?>