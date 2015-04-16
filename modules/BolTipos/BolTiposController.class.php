<?php

/**
 * CONTROLLER FOR BolTipos
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 06.07.2013 15:53:22

 * Extiende a la clase controller
 */
class BolTiposController extends Controller {

    protected $entity = "BolTipos";

    public function IndexAction() {
        return parent::listAction();
    }

}

?>