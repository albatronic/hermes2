<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 17.08.2011 12:37:30
 */

/**
 * @orm:Entity(TiposMvtosAlmacen)
 */
class TiposMvtosAlmacenEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="TiposMvtosAlmacen")
     */
    protected $Id;
    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="TiposMvtosAlmacen")
     */
    protected $Descripcion;
    /**
     * @orm:Column(type="")
     * @assert:NotBlank(groups="TiposMvtosAlmacen")
     */
    protected $Signo = 'E';
    /**
     * @orm:Column(type="")
     * @assert:NotBlank(groups="TiposMvtosAlmacen")
     */
    protected $Uso = 'A';
    /**
     * @orm:Column(type="")
     * @assert:NotBlank(groups="TiposMvtosAlmacen")
     */
    protected $TipoDocumento = '';
    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';
    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpTiposMvtosAlmacen';
    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'Id';
    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'Id', 'ParentEntity' => 'MvtosAlmacen', 'ParentColumn' => 'IDTipo'),
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

    public function setDescripcion($Descripcion) {
        $this->Descripcion = trim($Descripcion);
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function setSigno($Signo) {
        $this->Signo = $Signo;
    }

    public function getSigno() {
        if (!($this->Signo instanceof SignosMvtosAlmacen))
            $this->Signo = new SignosMvtosAlmacen($this->Signo);
        return $this->Signo;
    }

    public function setUso($Uso) {
        $this->Uso = $Uso;
    }

    public function getUso() {
        if (!($this->Uso instanceof UsosMvtosAlmacen))
            $this->Uso = new UsosMvtosAlmacen($this->Uso);
        return $this->Uso;
    }

    public function setTipoDocumento($TipoDocumento) {
        $this->TipoDocumento = $TipoDocumento;
    }

    public function getTipoDocumento() {
        return $this->TipoDocumento;
    }

}

// END class TiposMvtosAlmacen
?>