<?php

/**
 * CONTROLLER FOR BannZonas
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 09.12.2012 08:35:28

 * Extiende a la clase controller
 */
class BannZonasController extends Controller {

    protected $entity = "BannZonas";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }

}

?>