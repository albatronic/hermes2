<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.11.2012 00:50:31
 */

/**
 * @orm:Entity(EtiqEtiquetas)
 */
class EtiqEtiquetasEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="EtiqEtiquetas")
     */
    protected $Id;

    /**
     * @var entities\CpanModulos
     * @assert NotBlank(groups="EtiqEtiquetas")
     */
    protected $IdModulo;

    /**
     * @var string
     * @assert NotBlank(groups="EtiqEtiquetas")
     */
    protected $Etiqueta = '';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'EtiqEtiquetas*';

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
        array('SourceColumn' => 'Id', 'ParentEntity' => 'EtiqRelaciones', 'ParentColumn' => 'IdEtiqueta'),        
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'CpanModulos',
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

    public function setIdModulo($IdModulo) {
        $this->IdModulo = $IdModulo;
    }

    public function getIdModulo() {
        if (!($this->IdModulo instanceof CpanModulos))
            $this->IdModulo = new CpanModulos($this->IdModulo);
        return $this->IdModulo;
    }

    public function setEtiqueta($Etiqueta) {
        $this->Etiqueta = trim($Etiqueta);
    }

    public function getEtiqueta() {
        return $this->Etiqueta;
    }

}

// END class EtiqEtiquetas
?>