<?php

/**
 * CONTROLLER FOR GconSecciones
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 30.10.2012 18:44:56

 * Extiende a la clase controller
 */
class GconSeccionesController extends Controller {

    protected $entity = "GconSecciones";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::newAction();
    }
  
}

?>