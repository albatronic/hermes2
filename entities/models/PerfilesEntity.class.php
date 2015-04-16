<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.09.2012 13:51:04
 */

/**
 * @orm:Entity(ErpPerfiles)
 */
class PerfilesEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="ErpPerfiles")
	 */
	protected $IDPerfil;
	/**
	 * @var string
	 * @assert NotBlank(groups="ErpPerfiles")
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
	protected $_tableName = 'ErpPerfiles';
	/**
	 * Nombre de la PrimaryKey
	 * @var string
	 */
	protected $_primaryKeyName = 'IDPerfil';
	/**
	 * Relacion de entidades que dependen de esta
	 * @var string
	 */
	protected $_parentEntities = array(
			array('SourceColumn' => 'IDPerfil', 'ParentEntity' => 'Permisos', 'ParentColumn' => 'IdPerfil'),
			array('SourceColumn' => 'IDPerfil', 'ParentEntity' => 'Agentes', 'ParentColumn' => 'IdPerfil'),
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
	public function setIDPerfil($IDPerfil){
		$this->IDPerfil = $IDPerfil;
	}
	public function getIDPerfil(){
		return $this->IDPerfil;
	}

	public function setPerfil($Perfil){
		$this->Perfil = trim($Perfil);
	}
	public function getPerfil(){
		return $this->Perfil;
	}

} // END class ErpPerfiles

?>