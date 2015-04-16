<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 16.10.2012 16:33:56
 */

/**
 * @orm:Entity(PcaeProyectos)
 */
class PcaeProyectos extends PcaeProyectosEntity {

    public function __toString() {
        return $this->getProyecto();
    }

    /**
     * Devuelve un array de objetos \PcaeApps
     * asociados al proyecto en curso.
     *
     * @return array Array de objetos \PcaeApps
     */
    public function getApps() {

        $apps = array();

        $app = new PcaeProyectosApps();
        $rows = $app->cargaCondicion("IdApp", "IdProyecto='{$this->Id}'");
        unset($app);

        foreach ($rows as $row)
            $apps[] = new PcaeApps($row['IdApp']);

        return $apps;
    }

}

?>