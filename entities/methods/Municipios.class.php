<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 01:19:34
 */

/**
 * @orm:Entity(ErpMunicipios)
 */
class Municipios extends MunicipiosEntity {

    public function __toString() {

        return ($this->IDMunicipio>0) ? $this->Municipio : "";
    }

}

?>