<?php

/**
 * CONTROLLER FOR AvisadorStock
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 19.07.2014 02:26:08

 * Extiende a la clase controller
 */
class AvisadorStockController extends Controller {

    protected $entity = "AvisadorStock";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}
