<?php
/**
* CONTROLLER FOR WebPerfiles
* @author: Sergio Perez <sergio.perez@albatronic.com>
* @copyright: INFORMATICA ALBATRONIC SL 
* @date 18.03.2014 18:36:33

* Extiende a la clase controller
*/

class WebPerfilesController extends Controller {

	protected $entity = "WebPerfiles";
	protected $parentEntity = "";

	public function IndexAction() {
		return $this->listAction();
	}
}
?>