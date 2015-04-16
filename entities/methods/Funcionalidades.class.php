<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.09.2012 20:32:58
 */

/**
 * @orm:Entity(Funcionalidades)
 */
class Funcionalidades extends FuncionalidadesEntity {

    public function __toString() {
        return $this->getId();
    }

    /**
     * Devuelve un array(Id,Value) con las funcionalidades posibles
     * @return array Array con las funcionalidades
     */
    public function getArrayFuncionalidades() {
        return $this->cargaCondicion("Codigo as Id, Titulo as Value","1","Codigo ASC");
    }
    
    /**
     * Devueve un string separado por comas con las funcionalidades
     * @return string Las funcionalidades
     */
    public function getStringFuncionalidades() {
        $array = $this->getArrayFuncionalidades();
        
        foreach ($array as $funcionalidad) 
            $string .= $funcionalidad['Id'] . ",";

        $string = substr($string, 0, -1);
        
        return $string;
    }
}

?>