<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 01:19:34
 */

/**
 * @orm:Entity(ErpMunicipios)
 */
class MunicipiosEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="ErpMunicipios")
	 */
	protected $IDMunicipio;
	/**
	 * @var entities\Paises
	 * @assert NotBlank(groups="ErpMunicipios")
	 */
	protected $IDPais;
	/**
	 * @var entities\Provincias
	 * @assert NotBlank(groups="ErpMunicipios")
	 */
	protected $IDProvincia;
	/**
	 * @var string
	 * @assert NotBlank(groups="ErpMunicipios")
	 */
	protected $Codigo;
	/**
	 * @var string
	 * @assert NotBlank(groups="ErpMunicipios")
	 */
	protected $DigitoControl;
	/**
	 * @var string
	 * @assert NotBlank(groups="ErpMunicipios")
	 */
	protected $Municipio;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = '';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'ErpMunicipios';
	/**
	 * Nombre de la PrimaryKey
	 * @var string
	 */
	protected $_primaryKeyName = 'IDMunicipio';
	/**
	 * Array con las columnas de la primarykey
	 * @var array
	 */
	protected $_arrayPrimaryKeys = array('IDMunicipio');
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
			'Paises',
			'Provincias',
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
	public function setIDMunicipio($IDMunicipio){
		$this->IDMunicipio = $IDMunicipio;
	}
	public function getIDMunicipio(){
		return $this->IDMunicipio;
	}

	public function setIDPais($IDPais){
		$this->IDPais = $IDPais;
	}
	public function getIDPais(){
		if (!($this->IDPais instanceof Paises))
			$this->IDPais = new Paises($this->IDPais);
		return $this->IDPais;
	}

	public function setIDProvincia($IDProvincia){
		$this->IDProvincia = $IDProvincia;
	}
	public function getIDProvincia(){
		if (!($this->IDProvincia instanceof Provincias))
			$this->IDProvincia = new Provincias($this->IDProvincia);
		return $this->IDProvincia;
	}

	public function setCodigo($Codigo){
		$this->Codigo = trim($Codigo);
	}
	public function getCodigo(){
		return $this->Codigo;
	}

	public function setDigitoControl($DigitoControl){
		$this->DigitoControl = trim($DigitoControl);
	}
	public function getDigitoControl(){
		return $this->DigitoControl;
	}

	public function setMunicipio($Municipio){
		$this->Municipio = trim($Municipio);
	}
	public function getMunicipio(){
		return $this->Municipio;
	}

} // END class ErpMunicipios

?>