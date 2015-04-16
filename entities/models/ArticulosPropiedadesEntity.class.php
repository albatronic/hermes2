<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.05.2013 23:00:45
 */

/**
 * @orm:Entity(ErpArticulosPropiedades)
 */
class ArticulosPropiedadesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpArticulosPropiedades")
     */
    protected $Id;

    /**
     * @var entities\Articulos
     * @assert NotBlank(groups="ErpArticulosPropiedades")
     */
    protected $IDArticulo;

    /**
     * @var entities\Propiedades
     * @assert NotBlank(groups="ErpArticulosPropiedades")
     */
    protected $IDPropiedad;

    /**
     * @var entities\PropiedadesValores
     * @assert NotBlank(groups="ErpArticulosPropiedades")
     */
    protected $IDValor;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpArticulosPropiedades';

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
        'Propiedades',
        'PropiedadesValores',
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

    public function setIDArticulo($IDArticulo) {
        $this->IDArticulo = $IDArticulo;
    }

    public function getIDArticulo() {
        if (!($this->IDArticulo instanceof Articulos))
            $this->IDArticulo = new Articulos($this->IDArticulo);
        return $this->IDArticulo;
    }

    public function setIDPropiedad($IDPropiedad) {
        $this->IDPropiedad = $IDPropiedad;
    }

    public function getIDPropiedad() {
        if (!($this->IDPropiedad instanceof Propiedades))
            $this->IDPropiedad = new Propiedades($this->IDPropiedad);
        return $this->IDPropiedad;
    }

    public function setIDValor($IDValor) {
        $this->IDValor = $IDValor;
    }

    public function getIDValor() {
        if (!($this->IDValor instanceof PropiedadesValores))
            $this->IDValor = new PropiedadesValores($this->IDValor);
        return $this->IDValor;
    }

}

// END class ErpArticulosPropiedades
?>