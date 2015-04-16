<?php

/**
 * CONTROLLER FOR SldZonas
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 10.12.2012 17:38:03

 * Extiende a la clase controller
 */
class SldZonasController extends Controller {

    protected $entity = "SldZonas";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }

}

?>