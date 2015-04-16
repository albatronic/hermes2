<?php

/**
 * CONTROLLER FOR Municipios
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 02.02.2014 01:19:34

 * Extiende a la clase controller
 */
class MunicipiosController extends Controller {

    protected $entity = "Municipios";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>