<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 01:32:22
 */

/**
 * @orm:Entity(ErpIdiomas)
 */
class IdiomasEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="ErpIdiomas")
	 */
	protected $IDIdioma;
	/**
	 * @var string
	 * @assert NotBlank(groups="ErpIdiomas")
	 */
	protected $Codigo;
	/**
	 * @var string
	 * @assert NotBlank(groups="ErpIdiomas")
	 */
	protected $Idioma;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = '';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'ErpIdiomas';
	/**
	 * Nombre de la PrimaryKey
	 * @var string
	 */
	protected $_primaryKeyName = 'IDIdioma';
	/**
	 * Array con las columnas de la primarykey
	 * @var array
	 */
	protected $_arrayPrimaryKeys = array('IDIdioma');
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
	public function setIDIdioma($IDIdioma){
		$this->IDIdioma = $IDIdioma;
	}
	public function getIDIdioma(){
		return $this->IDIdioma;
	}

	public function setCodigo($Codigo){
		$this->Codigo = trim($Codigo);
	}
	public function getCodigo(){
		return $this->Codigo;
	}

	public function setIdioma($Idioma){
		$this->Idioma = trim($Idioma);
	}
	public function getIdioma(){
		return $this->Idioma;
	}

} // END class ErpIdiomas

?>