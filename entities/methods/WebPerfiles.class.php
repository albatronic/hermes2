<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 15.02.2013 17:35:40
 */

/**
 * @orm:Entity(WebPerfiles)
 */
class WebPerfiles extends WebPerfilesEntity {

    public function __toString() {
        return $this->getId();
    }


    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Perfil';
        return parent::fetchAll($column, $default);
    }    
}

?>