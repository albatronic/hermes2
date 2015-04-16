<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.09.2012 13:51:04
 */

/**
 * @orm:Entity(Modulos)
 */
class ModulosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="Modulos")
     */
    protected $Id;

    /**
     * @var entities\Aplicaciones
     * @assert NotBlank(groups="Modulos")
     */
    protected $CodigoApp;

    /**
     * @var string
     * @assert NotBlank(groups="Modulos")
     */
    protected $NombreModulo;

    /**
     * @var integer
     * @assert NotBlank(groups="Modulos")
     */
    protected $Nivel = '0';

    /**
     * @var string
     * @assert NotBlank(groups="Modulos")
     */
    protected $Titulo;

    /**
     * @var string
     */
    protected $Descripcion;

    /**
     * @var string
     * @assert NotBlank(groups="Modulos")
     */
    protected $Funcionalidades;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpModulos';

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
        array('SourceColumn' => 'NombreModulo', 'ParentEntity' => 'Permisos', 'ParentColumn' => 'NombreModulo'),
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'Aplicaciones',
        'ValoresSN',
        'ValoresPrivacy',
        'ValoresDchaIzq',
        'ValoresChangeFreq',
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

    public function setCodigoApp($CodigoApp) {
        $this->CodigoApp = trim($CodigoApp);
    }

    public function getCodigoApp() {
        //if (!($this->CodigoApp instanceof Aplicaciones))
        //	$this->CodigoApp = new Aplicaciones($this->CodigoApp);
        return $this->CodigoApp;
    }

    public function setNombreModulo($NombreModulo) {
        $this->NombreModulo = trim($NombreModulo);
    }

    public function getNombreModulo() {
        return $this->NombreModulo;
    }

    public function setNivel($Nivel) {
        $this->Nivel = $Nivel;
    }

    public function getNivel() {
        return $this->Nivel;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = trim($Titulo);
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = trim($Descripcion);
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function setFuncionalidades($Funcionalidades) {
        $this->Funcionalidades = trim($Funcionalidades);
    }

    public function getFuncionalidades() {
        return $this->Funcionalidades;
    }

}

// END class Modulos
?>