<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 01.02.2014 21:09:46
 */

/**
 * @orm:Entity(CpanTextos)
 */
class CpanTextos extends CpanTextosEntity {

    protected $Publish = '1';
  
    public function __toString() {
        return $this->getObservations();
    }

    /**
     * Devuelve un array con los textos comunes a todos los
     * controles y los del propio controlador indicado.
     * 
     * De tal manera que prevalecen los del controller sobre los
     * comunes en caso de solapamiento,
     * 
     * El índice del array es la palabra clave y el valor es el 
     * texto traducido.
     * 
     * @param string $controller El nombre del controlador
     * @param string $idioma El código de idioma. Por defecto el idioma en curso
     * @return array Array con los textos
     */
    function getTextos($controller, $idioma = '') {

        $array = array();

        if (!$idioma) {
            $idioma = $_SESSION['idiomas']['disponibles'][$_SESSION['idiomas']['actual']]['codigo'];
        }

        $filtroIdioma = "Lang='$idioma'";

        // Obtener los textos comunes a todos los controles
        $rowsComunes = $this->cargaCondicion("Clave,Observations", "Controller='' AND {$filtroIdioma}");
        foreach ($rowsComunes as $texto) {
            $array[$texto['Clave']] = $texto['Observations'];
        }
        // Obtener los textos del controlador indicado      
        $rowsController = $this->cargaCondicion("Clave,Observations", "Controller='{$controller}' AND {$filtroIdioma}");
        foreach ($rowsController as $texto) {
            $array[$texto['Clave']] = $texto['Observations'];
        }

        return $array;
    }

}