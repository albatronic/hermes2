<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.09.2012 20:32:58
 */

/**
 * @orm:Entity(CpanAplicaciones)
 */
class CpanAplicaciones extends CpanAplicacionesEntity {

    public function __toString() {
        return $this->getId();
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'NombreApp';
        return parent::fetchAll($column, $default);
    }

}

?>