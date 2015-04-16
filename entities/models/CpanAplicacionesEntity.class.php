<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.09.2012 13:51:04
 */

/**
 * @orm:Entity(CpanAplicaciones)
 */
class CpanAplicacionesEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="CpanAplicaciones")
	 */
	protected $Id;
	/**
	 * @var string
	 * @assert NotBlank(groups="CpanAplicaciones")
	 */
	protected $CodigoApp;
	/**
	 * @var string
	 * @assert NotBlank(groups="CpanAplicaciones")
	 */
	protected $NombreApp = '';
	/**
	 * @var string
	 */
	protected $Descripcion;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = '';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'CpanAplicaciones';
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
			array('SourceColumn' => 'CodigoApp', 'ParentEntity' => 'CpanModulos', 'ParentColumn' => 'CodigoApp'),
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
		);
	/**
	 * GETTERS Y SETTERS
	 */
	public function setId($Id){
		$this->Id = $Id;
	}
	public function getId(){
		return $this->Id;
	}

	public function setCodigoApp($CodigoApp){
		$this->CodigoApp = trim($CodigoApp);
	}
	public function getCodigoApp(){
		return $this->CodigoApp;
	}

	public function setNombreApp($NombreApp){
		$this->NombreApp = trim($NombreApp);
	}
	public function getNombreApp(){
		return $this->NombreApp;
	}

	public function setDescripcion($Descripcion){
		$this->Descripcion = trim($Descripcion);
	}
	public function getDescripcion(){
		return $this->Descripcion;
	}

} // END class CpanAplicaciones

?>