<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 21.02.2013 21:32:01
 */

/**
 * @orm:Entity(BlogComentarios)
 */
class BlogComentarios extends BlogComentariosEntity {

    public function __toString() {
        return $this->getId();
    }

    public function create() {
        $this->Publish = 1;
        $this->IpAddress = $_SERVER['REMOTE_ADDR'];
        $this->TiempoUnix = time();        
        return parent::create();
    }
    
    public function fetchAllAditional($column = '', $default = true) {
        if ($column == '')
            $column = $this->getPrimaryKeyName();

        $this->conecta();

        if (is_resource($this->_dbLink)) {
            $query = "SELECT distinct {$column} as Id, {$column} as Value FROM `{$this->_dataBaseName}`.`{$this->_tableName}` WHERE (Deleted = '0') ORDER BY $column ASC";
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
            $this->setStatus($this->_em->numRows());
            //$this->_em->desConecta();
            //unset($this->_em);
        }

        if ($default == TRUE) {
            array_unshift($rows, array('Id' => '', Value => ':: Indique un Valor'));
        }

        return $rows;
    }

    /**
     * Devuelve el objeto asociado al comentario
     * 
     * @return \Entidad
     */
    public function getObjetoAsociado() {
        if (class_exists($this->Entidad)) {
            return new $this->Entidad($this->IdEntidad);
        }
    }

    /**
     * Devuelve array de objetos \BlogComentarios
     * de la entidad $entidad e $idEntidad
     * 
     * @param string $entidad
     * @param integer $idEntidad
     */
    public function getComentarios($entidad,$idEntidad) {
        
        $array = array();
        
        $rows = $this->cargaCondicion("Id","Entidad='{$entidad}' AND IdEntidad='{$idEntidad}'","TiempoUnix DESC");
        foreach($rows as $row) {
            $array[] = new BlogComentarios($row['Id']);
        }
        
        return $array;
        
    }
}