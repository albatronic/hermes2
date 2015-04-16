<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.01.2013 19:57:09
 */

/**
 * @orm:Entity(PropiedadesValores)
 */
class PropiedadesValores extends PropiedadesValoresEntity {

    public function __toString() {
        return $this->getId();
    }

    public function save() {
        
        $this->Publish = 1;
        
        return parent::save();
    }
    
    /**
     * Comprueba la unicidad de la dupla propiedad-valor
     */
    public function validaLogico() {
        
        parent::validaLogico();

        // Comprobar la unicidad de la dupla propiedad-valor
        $valores = new PropiedadesValores();
        $rows = $valores->cargaCondicion('Id',"IDPropiedad='{$this->IDPropiedad}' and Valor='{$this->Valor}'");
        unset($valores);

        if (count($rows) and ($this->Id == ''))
            $this->_errores[] = "Ya existe el valor '{$this->Valor}' para la propiedad";
        
    }

    /**
     * Devuelve un array con los valores posibles de la propiedad $idPropiedad
     * 
     * Cada elemento del array es:
     * - Id: El id del valor
     * - Valor: el valor de la propiedad
     * - Color: el valor del color (opcional)
     * 
     * @param integer $idPropiedad
     * @param boolean $aditional Si TRUE añada el valor adicional "Indique un valor". Por defecto false
     * @return array
     */
    public function getValores($idPropiedad, $aditional = false) {
        $valores = $this->cargaCondicion("Id,Valor,Color","IDPropiedad='{$idPropiedad}'");
        
        if ($aditional)
            array_push ($valores, array("Id" => '', 'Valor' => ':: Indique un valor'));
        
        return $valores;
    }
}

?>