<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.09.2012 13:51:04
 */

/**
 * @orm:Entity(CpanPerfiles)
 */
class CpanPerfilesEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="CpanPerfiles")
	 */
	protected $Id;
	/**
	 * @var string
	 * @assert NotBlank(groups="CpanPerfiles")
	 */
	protected $Perfil;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = '';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'CpanPerfiles';
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
			array('SourceColumn' => 'Id', 'ParentEntity' => 'CpanPermisos', 'ParentColumn' => 'IdPerfil'),
			array('SourceColumn' => 'Id', 'ParentEntity' => 'CpanUsuarios', 'ParentColumn' => 'IdPerfil'),
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

	public function setPerfil($Perfil){
		$this->Perfil = trim($Perfil);
	}
	public function getPerfil(){
		return $this->Perfil;
	}

} // END class CpanPerfiles

?>