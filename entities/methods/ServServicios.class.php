<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.02.2013 23:09:18
 */

/**
 * @orm:Entity(ServServicios)
 */
class ServServicios extends ServServiciosEntity {

    public function __toString() {
        return $this->Titulo;
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Titulo';
        return parent::fetchAll($column, $default);
    }

    /**
     * Devuelve un array anidado de servicios
     * 
     * @return array Array de familias y servicios
     */
    public function getArbolHijos($conServicios = true, $entidadRelacionada = '', $idEntidadRelacionada = '') {

        if ($conServicios == '') $conServicios = true;
        
        $familias = new ServFamilias();
        $arbolFamilias = $familias->getArbolHijos($conServicios, $entidadRelacionada, $idEntidadRelacionada);
        unset($familias);

        return $arbolFamilias;
    }    
}

?>