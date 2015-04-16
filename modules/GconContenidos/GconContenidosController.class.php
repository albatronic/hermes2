<?php

/**
 * CONTROLLER FOR GconContenidos
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 30.10.2012 18:45:39

 * Extiende a la clase controller
 */
class GconContenidosController extends Controller {

    protected $entity = "GconContenidos";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::newAction();
    }

}

?>