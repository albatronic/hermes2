<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 14:24:31
 */

/**
 * @orm:Entity(CommPaises)
 */
class PaisesEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm ID
     * @var integer
     * @assert NotBlank(groups="CommPaises")
     */
    protected $IDPais;

    /**
     * @var string
     * @assert NotBlank(groups="CommPaises")
     */
    protected $Codigo;

    /**
     * @var string
     * @assert NotBlank(groups="CommPaises")
     */
    protected $Pais = '';

    /**
     * @var entities\CommMonedas
     */
    protected $IDMoneda = '0';

    /**
     * @var entities\CommZonasHorarias
     */
    protected $IDZonaHoraria = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="CommPaises")
     */
    protected $Latitud = '0';

    /**
     * @var integer
     * @assert NotBlank(groups="CommPaises")
     */
    protected $Longitud = '0';

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpPaises';

    /**
     * Nombre de la PrimaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDPais';

    /**
     * Relacion de entidades que dependen de esta
     * @var string
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDPais', 'ParentEntity' => 'Clientes', 'ParentColumn' => 'IDPais'),
        array('SourceColumn' => 'IDPais', 'ParentEntity' => 'Proveedores', 'ParentColumn' => 'IDPais'),
    );

    /**
     * Relacion de entidades de las que esta depende
     * @var string
     */
    protected $_childEntities = array(
        'Monedas',
        'ZonasHorarias',
        'ValoresSN',
        'ValoresPrivacy',
        'ValoresDchaIzq',
        'ValoresChangeFreq',
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDPais($IDPais) {
        $this->IDPais = $IDPais;
    }

    public function getIDPais() {
        return $this->IDPais;
    }

    public function setCodigo($Codigo) {
        $this->Codigo = trim($Codigo);
    }

    public function getCodigo() {
        return $this->Codigo;
    }

    public function setPais($Pais) {
        $this->Pais = trim($Pais);
    }

    public function getPais() {
        return $this->Pais;
    }

    public function setIDMoneda($IDMoneda) {
        $this->IDMoneda = $IDMoneda;
    }

    public function getIDMoneda() {
        if (!($this->IDMoneda instanceof Monedas))
            $this->IDMoneda = new Monedas($this->IDMoneda);
        return $this->IDMoneda;
    }

    public function setIDZonaHoraria($IDZonaHoraria) {
        $this->IDZonaHoraria = $IDZonaHoraria;
    }

    public function getIDZonaHoraria() {
        if (!($this->IDZonaHoraria instanceof ZonasHorarias))
            $this->IDZonaHoraria = new ZonasHorarias($this->IDZonaHoraria);
        return $this->IDZonaHoraria;
    }

    public function setLatitud($Latitud) {
        $this->Latitud = $Latitud;
    }

    public function getLatitud() {
        return $this->Latitud;
    }

    public function setLongitud($Longitud) {
        $this->Longitud = $Longitud;
    }

    public function getLongitud() {
        return $this->Longitud;
    }

}

// END class CommPaises
?>