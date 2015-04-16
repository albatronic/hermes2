<?php

/**
 * CONTROLLER FOR ServFamilias
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 04.02.2013 19:23:25

 * Extiende a la clase controller
 */
class ServFamiliasController extends Controller {

    protected $entity = "ServFamilias";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }

}

?>