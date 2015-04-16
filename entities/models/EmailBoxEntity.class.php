<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 05.10.2013 14:37:09
 */

/**
 * @orm:Entity(EmailBox)
 */
class EmailBoxEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $Ip;

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $De;

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $Para;

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $CC='';

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $CCO='';

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $Asunto='';

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $Mensaje='';

    /**
     * @var string
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $Adjuntos='';

    /**
     * @var tinyint
     * @assert NotBlank(groups="ErpEmailBox")
     */
    protected $Ok = '0';

    /**
     * @var string
     */
    protected $Smtp;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpEmailBox';

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

    public function setIp($Ip) {
        $this->Ip = trim($Ip);
    }

    public function getIp() {
        return $this->Ip;
    }

    public function setDe($De) {
        $this->De = trim($De);
    }

    public function getDe() {
        return $this->De;
    }

    public function setPara($Para) {
        $this->Para = trim($Para);
    }

    public function getPara() {
        return $this->Para;
    }

    public function setCC($CC) {
        $this->CC = trim($CC);
    }

    public function getCC() {
        return $this->CC;
    }

    public function setCCO($CCO) {
        $this->CCO = trim($CCO);
    }

    public function getCCO() {
        return $this->CCO;
    }

    public function setAsunto($Asunto) {
        $this->Asunto = trim($Asunto);
    }

    public function getAsunto() {
        return $this->Asunto;
    }

    public function setMensaje($Mensaje) {
        $this->Mensaje = trim($Mensaje);
    }

    public function getMensaje() {
        return $this->Mensaje;
    }

    public function setAdjuntos($Adjuntos) {
        $this->Adjuntos = trim($Adjuntos);
    }

    /**
     * Devuelve un array con los archivos adjuntos
     * @return array
     */
    public function getAdjuntos() {
        $array = json_decode($this->Adjuntos);
        return $array;
    }

    public function setOk($Ok) {
        $this->Ok = $Ok;
    }

    public function getOk() {
        if (!($this->Ok instanceof ValoresSN))
            $this->Ok = new ValoresSN($this->Ok);
        return $this->Ok;
    }

    public function setSmtp($Smtp) {
        $this->Smtp = trim($Smtp);
    }

    public function getSmtp() {
        return $this->Smtp;
    }

}

// END class ErpEmailBox
?>