<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.09.2012 13:51:04
 */

/**
 * @orm:Entity(Funcionalidades)
 */
class FuncionalidadesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="Funcionalidades")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="Funcionalidades")
     */
    protected $Codigo;

    /**
     * @var string
     * @assert NotBlank(groups="Funcionalidades")
     */
    protected $Titulo;

    /**
     * @var string
     */
    protected $Descripcion;

    /**
     * @var entities\ValoresSN
     * @assert NotBlank(groups="Funcionalidades")
     */
    protected $EsEstandar = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpFuncionalidades';

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

    public function setCodigo($Codigo) {
        $this->Codigo = trim($Codigo);
    }

    public function getCodigo() {
        return $this->Codigo;
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

    public function setEsEstandar($EsEstandar) {
        $this->EsEstandar = $EsEstandar;
    }

    public function getEsEstandar() {
        if (!($this->EsEstandar instanceof ValoresSN))
            $this->EsEstandar = new ValoresSN($this->EsEstandar);
        return $this->EsEstandar;
    }

}

// END class Funcionalidades
?>