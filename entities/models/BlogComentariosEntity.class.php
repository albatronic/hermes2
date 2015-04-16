<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 21.02.2013 21:32:01
 */

/**
 * @orm:Entity(BlogComentarios)
 */
class BlogComentariosEntity extends EntityComunes {

    /**
     * @orm GeneratedValue
     * @orm Id
     * @var integer
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $Id;

    /**
     * @var string
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $Entidad;

    /**
     * @var entities\GconContenidos
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $IdEntidad;

    /**
     * @var string
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $IpAddress;

    /**
     * @var entities\CpanUsuarios
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $IdUsuario = '0';

    /**
     * @var string
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $Nombre;

    /**
     * @var string
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $Email;

    /**
     * @var integer
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $TiempoUnix = '0';

    /**
     * @var string
     * @assert NotBlank(groups="BlogComentarios")
     */
    protected $Comentario;

    /**
     * Nombre de la conexion a la BD
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'BlogComentarios*';

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
        'GconContenidos',
        'CpanUsuarios',
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
    public function setId($Id) {
        $this->Id = $Id;
    }

    public function getId() {
        return $this->Id;
    }

    public function setEntidad($Entidad) {
        $this->Entidad = trim($Entidad);
    }

    public function getEntidad() {
        return $this->Entidad;
    }

    public function setIdEntidad($IdEntidad) {
        $this->IdEntidad = $IdEntidad;
    }

    public function getIdEntidad() {
        if (!is_object($this->IdEntidad)) {
            if ($this->Entidad != '') {
                $this->IdEntidad = new $this->Entidad($this->IdEntidad);
            }
        }
        return $this->IdEntidad;
    }

    public function setIpAddress($IpAddress) {
        $this->IpAddress = trim($IpAddress);
    }

    public function getIpAddress() {
        return $this->IpAddress;
    }

    public function setIdUsuario($IdUsuario) {
        $this->IdUsuario = $IdUsuario;
    }

    public function getIdUsuario() {
        if (!($this->IdUsuario instanceof WebUsuarios))
            $this->IdUsuario = new WebUsuarios($this->IdUsuario);
        return $this->IdUsuario;
    }

    public function setNombre($Nombre) {
        $this->Nombre = trim($Nombre);
    }

    public function getNombre() {
        return $this->Nombre;
    }

    public function setEmail($Email) {
        $this->Email = trim($Email);
    }

    public function getEmail() {
        return $this->Email;
    }

    public function setTiempoUnix($TiempoUnix) {
        $this->TiempoUnix = $TiempoUnix;
    }

    public function getTiempoUnix() {
        return $this->TiempoUnix;
    }

    public function setComentario($Comentario) {
        $this->Comentario = trim($Comentario);
    }

    public function getComentario() {
        return $this->Comentario;
    }

}

// END class BlogComentarios
?>