<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 14:24:31
 */

/**
 * @orm:Entity(PcaeControlAcceso)
 */
class PcaeControlAccesoEntity extends EntityComunes {
	/**
	 * @orm GeneratedValue
	 * @orm Id
	 * @var integer
	 * @assert NotBlank(groups="PcaeControlAcceso")
	 */
	protected $Id;
	/**
	 * @var entities\PcaeUsuarios
	 * @assert NotBlank(groups="PcaeControlAcceso")
	 */
	protected $IdUsuario;
	/**
	 * @var entities\PcaeProyectosApps
	 * @assert NotBlank(groups="PcaeControlAcceso")
	 */
	protected $IdProyectoApp;
	/**
	 * @var string
	 * @assert NotBlank(groups="PcaeControlAcceso")
	 */
	protected $IdSesion;
	/**
	 * Nombre de la conexion a la BD
	 * @var string
	 */
	protected $_conectionName = 'pcae';
	/**
	 * Nombre de la tabla física
	 * @var string
	 */
	protected $_tableName = 'PcaeControlAcceso';
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
			'PcaeProyectosApps',
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

	public function setIdProyectoApp($IdProyectoApp){
		$this->IdProyectoApp = $IdProyectoApp;
	}
	public function getIdProyectoApp(){
		if (!($this->IdProyectoApp instanceof PcaeProyectosApps))
			$this->IdProyectoApp = new PcaeProyectosApps($this->IdProyectoApp);
		return $this->IdProyectoApp;
	}

	public function setIdSesion($IdSesion){
		$this->IdSesion = trim($IdSesion);
	}
	public function getIdSesion(){
		return $this->IdSesion;
	}

} // END class PcaeControlAcceso

?>