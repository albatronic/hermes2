<?php

/**
 * CONTROLLER FOR Idiomas
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 02.02.2014 01:32:22

 * Extiende a la clase controller
 */
class IdiomasController extends Controller {

    protected $entity = "Idiomas";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>