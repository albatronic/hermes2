<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 06.07.2013 15:53:22
 */

/**
 * @orm:Entity(BolTipos)
 */
class BolTipos extends BolTiposEntity {

    protected $Publish = '1';

    public function __toString() {
        return $this->Titulo;
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Titulo';
        return parent::fetchAll($column, $default);
    }

}

?>