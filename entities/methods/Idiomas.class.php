<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 01:32:22
 */

/**
 * @orm:Entity(ErpIdiomas)
 */
class Idiomas extends IdiomasEntity {

    public function __toString() {
        return $this->getIDIdioma();
    }

    /**
     * Devuelve array (Id,Value) con los idiomas definidos en
     * la variable Web de Proyecto [globales][lang]
     * 
     * Si no se ha definido ninguno, devuelve el español
     * 
     * @return array
     */
    public function getArrayIdiomas($opcionTodos = FALSE) {

        $listaIdiomas = trim($_SESSION['VARIABLES']['WebPro']['globales']['lang']);
        if ($listaIdiomas == '')
            $listaIdiomas = "es";
        $arrayIdiomas = explode(",", $listaIdiomas);

        if ($opcionTodos) {
            $array[] = array("Id" => "", "Value" => "** Todos **");
        }
        foreach ($arrayIdiomas as $value) {
            $filtro = "Codigo='" . trim($value) . "'";
            $rows = $this->cargaCondicion("Codigo as Id, Idioma as Value", $filtro);
            $array[] = $rows[0];
        }

        return $array;
    }

}

?>