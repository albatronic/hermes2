<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.11.2012 17:52:26
 */

/**
 * @orm:Entity(EvenEventos)
 */
class EvenEventos extends EvenEventosEntity {

    public function __toString() {
        return $this->getId();
    }

    /**
     * Devuelve el objeto relacionado con el evento
     * @return objeto
     */
    public function getObjeto() {
        if ($this->Entidad != '' and $this->IdEntidad != '')
            $objeto = new $this->Entidad($this->IdEntidad);
        else
            $objeto = null;

        return $objeto;
    }

}

?>