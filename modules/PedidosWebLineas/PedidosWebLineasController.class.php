<?php

/**
 * CONTROLLER FOR PedidosWebLineas
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 12.07.2014 20:31:56

 * Extiende a la clase controller
 */
class PedidosWebLineasController extends Controller {

    protected $entity = "PedidosWebLineas";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}
