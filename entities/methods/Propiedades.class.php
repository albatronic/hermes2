<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 06.05.2013 17:29:09
 */

/**
 * @orm:Entity(Propiedades)
 */
class Propiedades extends PropiedadesEntity {

    public function __toString() {
        return $this->getId();
    }

    /**
     * Devuelve un array con los valores posibles de la propiedad en curso
     * 
     * Cada elemento del array es:
     * - Id: El id del valor
     * - Valor: el valor de la propiedad
     * - Color: el valor del color (opcional)
     * 
     * @param integer $idPropiedad
     * @return array
     */
    public function getValores($aditional = false) {

        $valores = new PropiedadesValores();
        $rows = $valores->cargaCondicion("Id,Valor,Color", "IDPropiedad='{$this->getPrimaryKeyValue()}'");

        if ($aditional)
            array_push($rows, array("Id" => '', 'Valor' => ':: Indique un valor'));

        return $rows;
    }

}

?>