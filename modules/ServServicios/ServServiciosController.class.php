<?php

/**
 * CONTROLLER FOR ServServicios
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 04.02.2013 23:09:18

 * Extiende a la clase controller
 */
class ServServiciosController extends Controller {

    protected $entity = "ServServicios";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::newAction();
    }

}

?>