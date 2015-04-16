<?php

/**
 * CONTROLLER FOR CpanUrlAmigables
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL
 * @date 30.09.2012 16:42:34

 * Extiende a la clase controller
 */
class CpanUrlAmigablesController extends Controller {

    protected $entity = "CpanUrlAmigables";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }

}

?>