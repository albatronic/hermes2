<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.05.2013 19:32:19
 */

/**
 * @orm:Entity(ErpFamiliasPropiedades)
 */
class FamiliasPropiedadesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpFamiliasPropiedades")
     */
    protected $Id;

    /**
     * @var entities\Familias
     * @assert NotBlank(groups="ErpFamiliasPropiedades")
     */
    protected $IDFamilia;

    /**
     * @var entities\Propiedades
     * @assert NotBlank(groups="ErpFamiliasPropiedades")
     */
    protected $IDPropiedad;

    /**
     * @var entities\Propiedades
     * @assert NotBlank(groups="ErpFamiliasPropiedades")
     */
    protected $Filtrable = '1';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpFamiliasPropiedades';

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
        'Familias',
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

    public function setIDFamilia($IDFamilia) {
        $this->IDFamilia = $IDFamilia;
    }

    public function getIDFamilia() {
        if (!($this->IDFamilia instanceof Familias))
            $this->IDFamilia = new Familias($this->IDFamilia);
        return $this->IDFamilia;
    }

    public function setIDPropiedad($IDPropiedad) {
        $this->IDPropiedad = $IDPropiedad;
    }

    public function getIDPropiedad() {
        if (!($this->IDPropiedad instanceof Propiedades))
            $this->IDPropiedad = new Propiedades($this->IDPropiedad);
        return $this->IDPropiedad;
    }

    public function setFiltrable($Filtrable) {
        $this->Filtrable = $Filtrable;
    }

    public function getFiltrable() {
        if (!($this->Filtrable instanceof ValoresSN))
            $this->Filtrable = new ValoresSN($this->Filtrable);
        return $this->Filtrable;
    }
}

// END class ErpFamiliasPropiedades
?>