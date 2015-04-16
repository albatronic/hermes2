<?php

/**
 * CONTROLLER FOR AlbmSecciones
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 24.12.2012 12:14:33

 * Extiende a la clase controller
 */
class AlbmSeccionesController extends Controller {

    protected $entity = "AlbmSecciones";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }

}

?>