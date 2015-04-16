<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 14:24:31
 */

/**
 * @orm:Entity(PcaeEmpresasUsuarios)
 */
class PcaeEmpresasUsuariosEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="PcaeEmpresasUsuarios")
	 */
	protected $Id;
	/**
	 * @var entities\PcaeEmpresas
	 * @assert NotBlank(groups="PcaeEmpresasUsuarios")
	 */
	protected $IdEmpresa;
	/**
	 * @var entities\PcaeUsuarios
	 * @assert NotBlank(groups="PcaeEmpresasUsuarios")
	 */
	protected $IdUsuario;
	/**
	 * @var entities\PcaePerfiles
	 * @assert NotBlank(groups="PcaeEmpresasUsuarios")
	 */
	protected $IdPerfil;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = 'pcae';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'PcaeEmpresasUsuarios';
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
			'PcaeEmpresas',
			'PcaeUsuarios',
			'PcaePerfiles',
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

	public function setIdEmpresa($IdEmpresa){
		$this->IdEmpresa = $IdEmpresa;
	}
	public function getIdEmpresa(){
		if (!($this->IdEmpresa instanceof PcaeEmpresas))
			$this->IdEmpresa = new PcaeEmpresas($this->IdEmpresa);
		return $this->IdEmpresa;
	}

	public function setIdUsuario($IdUsuario){
		$this->IdUsuario = $IdUsuario;
	}
	public function getIdUsuario(){
		if (!($this->IdUsuario instanceof PcaeUsuarios))
			$this->IdUsuario = new PcaeUsuarios($this->IdUsuario);
		return $this->IdUsuario;
	}

	public function setIdPerfil($IdPerfil){
		$this->IdPerfil = $IdPerfil;
	}
	public function getIdPerfil(){
		if (!($this->IdPerfil instanceof PcaePerfiles))
			$this->IdPerfil = new PcaePerfiles($this->IdPerfil);
		return $this->IdPerfil;
	}

} // END class PcaeEmpresasUsuarios

?>