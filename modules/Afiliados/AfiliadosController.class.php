<?php

/**
 * CONTROLLER FOR Afiliados
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 13.07.2014 17:39:52

 * Extiende a la clase controller
 */
class AfiliadosController extends Controller {

    protected $entity = "Afiliados";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}
