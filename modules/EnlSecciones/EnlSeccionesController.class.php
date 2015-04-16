<?php

/**
 * CONTROLLER FOR EnlSecciones
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 05.12.2012 10:14:17

 * Extiende a la clase controller
 */
class EnlSeccionesController extends Controller {

    protected $entity = "EnlSecciones";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }


}

?>