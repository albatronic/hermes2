<?php

/**
 * Clase contenedora de Utilidades
 *
 * @author Informática ALBATRONIC, SL <sergio.perez@albatronic.com>
 * @version 1.0 1-3-2014
 */
class Utils {
    
    /**
     * Calcula el IBAN en base a una cuenta corriente
     * 
     * @param string $cc Los veinte dígitos de la cuenta corriente
     * @param string $codigoPais Dos letras que indican el país. Opcional. Por defecto "ES"
     * @return string El IBAN
     */
    static function iban($cc, $codigoPais = "ES") {
        $dividendo = $cc . (ord($codigoPais[0]) - 55) . (ord($codigoPais[1]) - 55) . '00';
        $digitoControl = 98 - bcmod($dividendo, '97');
        if (strlen($digitoControl) == 1) {
            $digitoControl = '0' . $digitoControl;
        }
        return $codigoPais . $digitoControl . $cc;
    }

}
