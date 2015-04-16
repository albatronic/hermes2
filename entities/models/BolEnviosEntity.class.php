<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.07.2013 12:30:18
 */

/**
 * @orm:Entity(BolEnvios)
 */
class BolEnviosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="BolEnvios")
     */
    protected $Id;

    /**
     * @var entities\BolBoletines
     * @assert NotBlank(groups="BolEnvios")
     */
    protected $IDBoletin;

    /**
     * @var string
     * @assert NotBlank(groups="BolEnvios")
     */
    protected $Para;

    /**
     * @var string
     */
    protected $Cc;

    /**
     * @var string
     */
    protected $Cco;

    /**
     * @var tinyint
     * @assert NotBlank(groups="BolEnvios")
     */
    protected $Estado = '0';

    /**
     * @var string
     */
    protected $Errores;

    /**
     * @var string
     * @assert NotBlank(groups="BolEnvios")
     */
    protected $ArchivoHtml;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'BolEnvios';

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
        'BolBoletines',
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

    public function setIDBoletin($IDBoletin) {
        $this->IDBoletin = $IDBoletin;
    }

    public function getIDBoletin() {
        if (!($this->IDBoletin instanceof BolBoletines))
            $this->IDBoletin = new BolBoletines($this->IDBoletin);
        return $this->IDBoletin;
    }

    public function setPara($Para) {
        $this->Para = trim($Para);
    }

    public function getPara() {
        return $this->Para;
    }

    public function setCc($Cc) {
        $this->Cc = trim($Cc);
    }

    public function getCc() {
        return $this->Cc;
    }

    public function setCco($Cco) {
        $this->Cco = trim($Cco);
    }

    public function getCco() {
        return $this->Cco;
    }

    public function setEstado($Estado) {
        $this->Estado = $Estado;
    }

    public function getEstado() {
        if (!($this->Estado instanceof ValoresSN))
            $this->Estado = new ValoresSN($this->Estado);
        return $this->Estado;
    }

    public function setErrores($Errores) {
        $this->Errores = trim($Errores);
    }

    public function getErrores() {
        return $this->Errores;
    }

    public function setArchivoHtml($ArchivoHtml) {
        $this->ArchivoHtml = trim($ArchivoHtml);
    }

    public function getArchivoHtml() {
        return $this->ArchivoHtml;
    }

}

// END class BolEnvios
?>