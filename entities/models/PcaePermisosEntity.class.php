<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 14:24:31
 */

/**
 * @orm:Entity(PcaePermisos)
 */
class PcaePermisosEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="PcaePermisos")
	 */
	protected $Id;
	/**
	 * @var entities\PcaeUsuarios
	 * @assert NotBlank(groups="PcaePermisos")
	 */
	protected $IdUsuario;
	/**
	 * @var entities\PcaeEmpresas
	 * @assert NotBlank(groups="PcaePermisos")
	 */
	protected $IdEmpresa;
	/**
	 * @var entities\PcaeProyectos
	 * @assert NotBlank(groups="PcaePermisos")
	 */
	protected $IdProyecto;
	/**
	 * @var entities\PcaeApps
	 * @assert NotBlank(groups="PcaePermisos")
	 */
	protected $IdApp;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = 'pcae';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'PcaePermisos';
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
			'PcaeUsuarios',
			'PcaeEmpresas',
			'PcaeProyectos',
			'PcaeApps',
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

	public function setIdUsuario($IdUsuario){
		$this->IdUsuario = $IdUsuario;
	}
	public function getIdUsuario(){
		if (!($this->IdUsuario instanceof PcaeUsuarios))
			$this->IdUsuario = new PcaeUsuarios($this->IdUsuario);
		return $this->IdUsuario;
	}

	public function setIdEmpresa($IdEmpresa){
		$this->IdEmpresa = $IdEmpresa;
	}
	public function getIdEmpresa(){
		if (!($this->IdEmpresa instanceof PcaeEmpresas))
			$this->IdEmpresa = new PcaeEmpresas($this->IdEmpresa);
		return $this->IdEmpresa;
	}

	public function setIdProyecto($IdProyecto){
		$this->IdProyecto = $IdProyecto;
	}
	public function getIdProyecto(){
		if (!($this->IdProyecto instanceof PcaeProyectos))
			$this->IdProyecto = new PcaeProyectos($this->IdProyecto);
		return $this->IdProyecto;
	}

	public function setIdApp($IdApp){
		$this->IdApp = $IdApp;
	}
	public function getIdApp(){
		if (!($this->IdApp instanceof PcaeApps))
			$this->IdApp = new PcaeApps($this->IdApp);
		return $this->IdApp;
	}

} // END class PcaePermisos

?>