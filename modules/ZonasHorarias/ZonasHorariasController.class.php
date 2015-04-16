<?php

/**
 * CONTROLLER FOR ZonasHorarias
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 02.02.2014 13:12:28

 * Extiende a la clase controller
 */
class ZonasHorariasController extends Controller {

    protected $entity = "ZonasHorarias";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>