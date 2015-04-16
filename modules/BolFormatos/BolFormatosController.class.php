<?php

/**
 * CONTROLLER FOR BolFormatos
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 11.07.2013 19:17:44

 * Extiende a la clase controller
 */
class BolFormatosController extends Controller {

    protected $entity = "BolFormatos";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }

}

?>