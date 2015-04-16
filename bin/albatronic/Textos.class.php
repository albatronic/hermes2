<?php

/**
 * CLASE PARA TRATAMIENTOS DE TEXTOS
 *
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 19-sep-2012 18:15:06
 */
class Textos {

    /**
     * Recibe una cadena de texto, la trata y devuelve el resultado
     *
     * @param string $texto EL texto a tratar
     * @return string El texto resultante
     */
    static function limpia($texto) {

        $tabla = array(
            ' ' => '-',
            '_' => '-',
            '.' => '',
            ',' => '',
            ':' => '',
            ';' => '',
            '!' => '',
            '¡' => '',
            '(' => '',
            ')' => '',
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'à' => 'a',
            'è' => 'e',
            'ì' => 'i',
            'ò' => 'o',
            'ù' => 'u',
            'ä' => 'a',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'o',
            'ü' => 'u',
            'â' => 'a',
            'ê' => 'e',
            'î' => 'i',
            'ô' => 'o',
            'û' => 'u',            
            'ñ' => 'n',
            'Ñ' => 'n',
            'ç' => 'c',
            'Ç' => 'c',
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
            '?' => '',
            '¿' => '',
            '!' => '',
            '¡' => '',
            '$' => '',
            '%' => '',
            '#' => '',
            '"' => '',
            '|' => '',
            '/' => '-',
            '+' => '',
        );

        // Pasar a minúsculas
        $texto = strtolower($texto);

        // Sustituir caracteres por su correspondencias según $tabla
        foreach ($tabla as $key => $value)
            $texto = str_replace($key, $value, $texto);

        // Eliminamos todo lo que no sean letras, número o guión
        //$texto = preg_replace("/[^A-Za-z0-9-]+/","", $texto);
        
        // Quitar eventuales triples y dobles guiones
        $texto = str_replace("--", "-", $texto);
        $texto = str_replace("--", "-", $texto);
        
        // Quito el eventual primer guión
        if ($texto[0] == "-") $texto = substr($texto, 1);

        // Quito el eventual último guión
        if ($texto[strlen($texto)-1] == "-") $texto = substr($texto, 0,strlen($texto)-1);

        return $texto;
    }

}

?>
