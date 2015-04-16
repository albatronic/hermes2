<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 17:43:37
 */

/**
 * @orm:Entity(CpanPlantillas)
 */
class CpanPlantillas extends CpanPlantillasEntity {

    protected $Publish = '1';
  
    public function __toString() {
        return $this->getObservations();
    }

    /**
     * Devuelve el texto de la plantilla con los campos variables
     * reemplazados por sus valores en base al array $sustituir
     * 
     * @param string $controller El nombre del controlador
     * @param string $clave El nombre de la plantilla
     * @param array $sustituir Array opcional con los pares a sustituir
     * @param string $idioma El idioma, por defecto el que está en curso
     * @param string $separadorInicio La cadena de separación de inicio. Opcional. Por defecto {{
     * @param string $separadorFin La cadena de separación de fin. Opcional. Por defecto }}
     * @return string El texto de la plantilla
     */
    public function getPlantilla($controller, $clave, $sustituir = array(), $idioma = '', $separadorInicio = "{{", $separadorFin = "}}") {

        if (!$idioma) {
            $idioma = $_SESSION['idiomas']['disponibles'][$_SESSION['idiomas']['actual']]['codigo'];
        }

        $filtroIdioma = "Lang='$idioma'";

        // Obtener los textos del controlador indicado      
        $row = $this->cargaCondicion("Observations", "Controller='{$controller}' AND Clave='{$clave}' AND {$filtroIdioma}");
        $plantilla = $row[0]['Observations'];

        foreach ($sustituir as $key => $value) {
            $plantilla = str_replace($separadorInicio . $key . $separadorFin, $value, $plantilla);
        }

        return $plantilla;
    }

}

?>