<?php

/**
 * CONTROLLER FOR PlanContable
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 26.01.2014 19:54:31

 * Extiende a la clase controller
 */
class PlanContableController extends Controller {

    protected $entity = "PlanContable";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>