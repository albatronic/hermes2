<?php

/**
 * CONTROLLER FOR PedidosWebCab
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 12.07.2014 20:29:53

 * Extiende a la clase controller
 */
class PedidosWebCabController extends Controller {

    protected $entity = "PedidosWebCab";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}
