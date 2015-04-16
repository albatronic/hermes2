<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.01.2013 19:57:09
 */

/**
 * @orm:Entity(PropiedadesValores)
 */
class PropiedadesValoresEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="PropiedadesValores")
     */
    protected $Id;

    /**
     * @var entities\Propiedades
     * @assert NotBlank(groups="PropiedadesValores")
     */
    protected $IDPropiedad;

    /**
     * @var string
     * @assert NotBlank(groups="PropiedadesValores")
     */
    protected $Valor;

    /**
     * @var string
     * @assert NotBlank(groups="PropiedadesValores")
     */
    protected $Color;
    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPropiedadesValores';

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
        'Propiedades',
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

    public function setIDPropiedad($IDPropiedad) {
        $this->IDPropiedad = $IDPropiedad;
    }

    public function getIDPropiedad() {
        if (!($this->IDPropiedad instanceof Propiedades))
            $this->IDPropiedad = new Propiedades($this->IDPropiedad);
        return $this->IDPropiedad;
    }

    public function setValor($Valor) {
        $this->Valor = trim($Valor);
    }

    public function getValor() {
        return $this->Valor;
    }

    public function setColor($Color) {
        $this->Color = trim($Color);
    }

    public function getColor() {
        return $this->Color;
    }

}

// END class PropiedadesValores
?>