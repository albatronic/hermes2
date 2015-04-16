<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 07.02.2013 15:32:56
 */

/**
 * @orm:Entity(Escaparates)
 */
class EscaparatesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="Escaparates")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="Escaparates")
     */
    protected $Controller;

    /**
     * @var integer
     * @assert NotBlank(groups="Escaparates")
     */
    protected $Zona = '0';

    /**
     * @var entities\ErpLotesWeb
     * @assert NotBlank(groups="Escaparates")
     */
    protected $IDLote = '0';

    /**
     * @var entities\ErpArticulosEstados
     * @assert NotBlank(groups="Escaparates")
     */
    protected $IDEstado = '0';

    /**
     * @var entities\ErpMarcas
     * @assert NotBlank(groups="Escaparates")
     */
    protected $IDFabricante = '0';

    /**
     * @var entities\ErpFamilias
     * @assert NotBlank(groups="Escaparates")
     */
    protected $IDCategoria = '0';

    /**
     * @var entities\ErpFamilias
     * @assert NotBlank(groups="Escaparates")
     */
    protected $IDFamilia = '0';

    /**
     * @var entities\ErpFamilias
     * @assert NotBlank(groups="Escaparates")
     */
    protected $IDSubfamilia = '0';

    /**
     * @var string
     */
    protected $Filtro;

    /**
     * @var integer
     * @assert NotBlank(groups="Escaparates")
     */
    protected $NItems = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="Escaparates")
     */
    protected $ItemsPagina = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpEscaparates';

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
        'LotesWeb',
        'ArticulosEstados',
        'Fabricantes',
        'Familias',
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

    public function setController($Controller) {
        $this->Controller = trim($Controller);
    }

    public function getController() {
        return $this->Controller;
    }

    public function setZona($Zona) {
        $this->Zona = $Zona;
    }

    public function getZona() {
        return $this->Zona;
    }

    public function setIDLote($IDLote) {
        $this->IDLote = $IDLote;
    }

    public function getIDLote() {
        if (!($this->IDLote instanceof LotesWeb))
            $this->IDLote = new LotesWeb($this->IDLote);
        return $this->IDLote;
    }    

    public function setIDEstado($IDEstado) {
        $this->IDEstado = $IDEstado;
    }

    public function getIDEstado() {
        if (!($this->IDEstado instanceof ArticulosEstados))
            $this->IDEstado = new ArticulosEstados($this->IDEstado);
        return $this->IDEstado;
    }

    public function setIDFabricante($IDFabricante) {
        $this->IDFabricante = $IDFabricante;
    }

    public function getIDFabricante() {
        if (!($this->IDFabricante instanceof Fabricantes))
            $this->IDFabricante = new Fabricantes($this->IDFabricante);
        return $this->IDFabricante;
    }

    public function setIDCategoria($IDCategoria) {
        $this->IDCategoria = $IDCategoria;
    }

    public function getIDCategoria() {
        if (!($this->IDCategoria instanceof Familias))
            $this->IDCategoria = new Familias($this->IDCategoria);
        return $this->IDCategoria;
    }

    public function setIDFamilia($IDFamilia) {
        $this->IDFamilia = $IDFamilia;
    }

    public function getIDFamilia() {
        if (!($this->IDFamilia instanceof Familias))
            $this->IDFamilia = new Familias($this->IDFamilia);
        return $this->IDFamilia;
    }

    public function setIDSubfamilia($IDSubfamilia) {
        $this->IDSubfamilia = $IDSubfamilia;
    }

    public function getIDSubfamilia() {
        if (!($this->IDSubfamilia instanceof Familias))
            $this->IDSubfamilia = new Familias($this->IDSubfamilia);
        return $this->IDSubfamilia;
    }

    public function setFiltro($Filtro) {
        $this->Filtro = trim($Filtro);
    }

    public function getFiltro() {
        return $this->Filtro;
    }

    public function setNItems($NItems) {
        $this->NItems = $NItems;
    }

    public function getNItems() {
        return $this->NItems;
    }

    public function setItemsPagina($ItemsPagina) {
        $this->ItemsPagina = $ItemsPagina;
    }

    public function getItemsPagina() {
        return $this->ItemsPagina;
    }

}

// END class Escaparates
