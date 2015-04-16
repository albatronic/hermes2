<?php

/**
 * CONTROLLER FOR ZonasPagoEnvio
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 14.03.2014 23:46:54

 * Extiende a la clase controller
 */
class ZonasPagoEnvioController extends Controller {

    protected $entity = "ZonasPagoEnvio";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>