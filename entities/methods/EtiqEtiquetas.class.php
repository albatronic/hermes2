<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.11.2012 00:50:31
 */

/**
 * @orm:Entity(EtiqEtiquetas)
 */
class EtiqEtiquetas extends EtiqEtiquetasEntity {

    public function __toString() {
        return $this->getEtiqueta();
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Etiqueta';
        return parent::fetchAll($column, $default);
    }
}
?>