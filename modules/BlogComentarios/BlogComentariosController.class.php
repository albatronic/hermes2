<?php

/**
 * CONTROLLER FOR BlogComentarios
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 21.02.2013 21:32:01

 * Extiende a la clase controller
 */
class BlogComentariosController extends Controller {

    protected $entity = "BlogComentarios";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

}

?>