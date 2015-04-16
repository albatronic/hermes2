<?php

/**
 * CONTROLLER FOR Propiedades
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.05.2013 19:41:32

 * Extiende a la clase controller
 */
class PropiedadesController extends Controller {

    protected $entity = "Propiedades";
    protected $parentEntity = "";

    public function indexAction() {
        return parent::ListAction();
    }
}

?>