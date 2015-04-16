<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 14:24:31
 */

/**
 * @orm:Entity(PcaeGruposEmpresas)
 */
class PcaeGruposEmpresasEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="PcaeGruposEmpresas")
	 */
	protected $Id;
	/**
	 * @var string
	 */
	protected $Descripcion;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = 'pcae';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'PcaeGruposEmpresas';
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
			array('SourceColumn' => 'Id', 'ParentEntity' => 'PcaeEmpresas', 'ParentColumn' => 'IdGrupo'),
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

	public function setDescripcion($Descripcion){
		$this->Descripcion = trim($Descripcion);
	}
	public function getDescripcion(){
		return $this->Descripcion;
	}

} // END class PcaeGruposEmpresas

?>