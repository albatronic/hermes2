<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.01.2013 19:57:09
 */

/**
 * @orm:Entity(ErpFamilias)
 */
class FamiliasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $IDFamilia;

    /**
     * @var string
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $Familia;

    /**
     * @var string
     */
    protected $Subtitulo;

    /**
     * @var string
     */
    protected $Descripcion1;

    /**
     * @var string
     */
    protected $Descripcion2;

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
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="ErpFamilias")
     */
    protected $MostrarEnTpv = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="ErpFamilias")
     */
    protected $OrdenTpv = '0';

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $Inventario = '0';

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $Trazabilidad = '0';

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $BajoPedido = '0';

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $BloqueoStock = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $MargenMinimo = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $MargenWeb = '0.00';

    /**
     * @var integer
     * @assert NotBlank(groups="ErpFamilias")
     */
    protected $Caducidad = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpFamilias*';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDFamilia';

    /**
     * Relacion de entidades que dependen de esta
     * @var string
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDFamilia', 'ParentEntity' => 'Articulos', 'ParentColumn' => 'IDCategoria'),
        array('SourceColumn' => 'IDFamilia', 'ParentEntity' => 'Articulos', 'ParentColumn' => 'IDFamilia'),
        array('SourceColumn' => 'IDFamilia', 'ParentEntity' => 'Articulos', 'ParentColumn' => 'IDSubfamilia'),
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
    public function setIDFamilia($IDFamilia) {
        $this->IDFamilia = $IDFamilia;
    }

    public function getIDFamilia() {
        return $this->IDFamilia;
    }

    public function setFamilia($Familia) {
        $this->Familia = trim($Familia);
    }

    public function getFamilia() {
        return $this->Familia;
    }

    public function setSubtitulo($Subtitulo) {
        $this->Subtitulo = trim($Subtitulo);
    }

    public function getSubtitulo() {
        return $this->Subtitulo;
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

    public function setMostrarEnTpv($MostrarEnTpv) {
        $this->MostrarEnTpv = $MostrarEnTpv;
    }

    public function getMostrarEnTpv() {
        if (!($this->MostrarEnTpv instanceof ValoresSN))
            $this->MostrarEnTpv = new ValoresSN($this->MostrarEnTpv);
        return $this->MostrarEnTpv;
    }

    public function setOrdenTpv($OrdenTpv) {
        $this->OrdenTpv = $OrdenTpv;
    }

    public function getOrdenTpv() {
        return $this->OrdenTpv;
    }

    public function setInventario($Inventario) {
        $this->Inventario = $Inventario;
    }

    public function getInventario() {
        if (!($this->Inventario instanceof ValoresSN))
            $this->Inventario = new ValoresSN($this->Inventario);
        return $this->Inventario;
    }

    public function setTrazabilidad($Trazabilidad) {
        $this->Trazabilidad = $Trazabilidad;
    }

    public function getTrazabilidad() {
        if (!($this->Trazabilidad instanceof ValoresSN))
            $this->Trazabilidad = new ValoresSN($this->Trazabilidad);
        return $this->Trazabilidad;
    }

    public function setBajoPedido($BajoPedido) {
        $this->BajoPedido = $BajoPedido;
    }

    public function getBajoPedido() {
        if (!($this->BajoPedido instanceof ValoresSN))
            $this->BajoPedido = new ValoresSN($this->BajoPedido);
        return $this->BajoPedido;
    }

    public function setBloqueoStock($BloqueoStock) {
        $this->BloqueoStock = $BloqueoStock;
    }

    public function getBloqueoStock() {
        if (!($this->BloqueoStock instanceof ValoresSN))
            $this->BloqueoStock = new ValoresSN($this->BloqueoStock);
        return $this->BloqueoStock;
    }

    public function setMargenMinimo($MargenMinimo) {
        $this->MargenMinimo = $MargenMinimo;
    }

    public function getMargenMinimo() {
        return $this->MargenMinimo;
    }

    public function setMargenWeb($MargenWeb) {
        $this->MargenWeb = $MargenWeb;
    }

    public function getMargenWeb() {
        return $this->MargenWeb;
    }

    public function setCaducidad($Caducidad) {
        $this->Caducidad = $Caducidad;
    }

    public function getCaducidad() {
        return $this->Caducidad;
    }

}

// END class Familias
?>