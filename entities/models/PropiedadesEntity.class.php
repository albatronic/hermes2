<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 06.05.2013 17:29:09
 */

/**
 * @orm:Entity(Propiedades)
 */
class PropiedadesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="Propiedades")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="Propiedades")
     */
    protected $Titulo;

    /**
     * @var entities\TiposPropiedades
     * @assert NotBlank(groups="Propiedades")
     */
    protected $IDTipo = '1';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPropiedades';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'PropiedadesValores', 'ParentColumn' => 'IDPropiedad'),        
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'TiposPropiedades',
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

    public function setTitulo($Titulo) {
        $this->Titulo = trim($Titulo);
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setIDTipo($IDTipo) {
        $this->IDTipo = $IDTipo;
    }

    public function getIDTipo() {
        if (!($this->IDTipo instanceof TiposPropiedades))
            $this->IDTipo = new TiposPropiedades($this->IDTipo);
        return $this->IDTipo;
    }

}

// END class Propiedades
?>