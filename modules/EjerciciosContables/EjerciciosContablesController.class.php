<?php

/**
 * CONTROLLER FOR EjerciciosContables
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 26.01.2014 21:12:08

 * Extiende a la clase controller
 */
class EjerciciosContablesController extends Controller {

    protected $entity = "EjerciciosContables";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>