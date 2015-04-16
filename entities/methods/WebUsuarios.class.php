<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 07.08.2013 21:27:18
 */

/**
 * @orm:Entity(WebUsuarios)
 */
class WebUsuarios extends WebUsuariosEntity {

    public function __toString() {
        return $this->getId();
    }

    /**
     * Devuelve el nombre concatenado con los apellidos
     * @return string
     */
    public function getNombreApellidos() {
        return trim($this->getNombre() . " " . $this->getApellidos());
    }

    /**
     * Devuelve los apellidos concatenado con el nombre
     * @return string
     */
    public function getApellidosNombre() {
        $texto = $this->getApellidos() . ", " . $this->getNombre();
        if ($texto == ", ")
            $texto = "";
        return $texto;
    }

    /**
     * Devuelve la razón social de la empresa
     * @return string
     */
    public function getRazonSocial() {
        $texto = $this->getEmpresa();
        if ($texto == '') {
            $texto = $this->getApellidos() . ", " . $this->getNombre();
            if ($texto == ", ")
                $texto = "";
        }
        return $texto;
    }

}

?>