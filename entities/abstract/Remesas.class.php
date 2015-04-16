<?php

/**
 * Método utilizados por los cuadernos para generar
 * las remesas
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 14-10-2013
 *
 */
class Remesas {

    /**
     * Generar un string con $n espacios
     * 
     * @param integer $n El número de espacios a añadir
     * @return string
     */
    static function Vacio($n) {
        return(str_repeat(" ", $n));
    }

    /**
     * self::Rellena a ceros por la izquierda
     * 
     * @param string $s El texto a rellenar
     * @param integer $n El número de espacios a añadir
     * @return string
     */
    static function Ceros($s, $n) {
        return(str_pad($s, $n, "0", STR_PAD_LEFT));
    }

    /**
     * self::Rellena con espacios por la derecha
     * 
     * @param string $s El texto a rellenar
     * @param integer $n El número de espacios a añadir
     * @return string
     */
    static function Rellena($s, $n) {
        $s = substr($s, 0, $n); //Primero recorto
        return(str_pad($s, $n, " ", STR_PAD_RIGHT)); //y luego le añado espacios
    }

    function Escribe($f, $r) {
        return fwrite($f, $r . "\n");
    }

}

?>
