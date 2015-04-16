<?php

/**
 * CONTROLLER FOR Cupones
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 12.04.2014 18:24:51

 * Extiende a la clase controller
 */
class CuponesController extends Controller {

    protected $entity = "Cupones";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>