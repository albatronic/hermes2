<?php

/**
 * CONTROLLER FOR Carrito
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 08.02.2014 19:37:17

 * Extiende a la clase controller
 */
class CarritoController extends Controller {

    protected $entity = "Carrito";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>