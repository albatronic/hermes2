<?php
/**
* CONTROLLER FOR Monedas
* @author: Sergio Perez <sergio.perez@albatronic.com>
* @copyright: INFORMATICA ALBATRONIC SL 
* @date 02.02.2014 01:15:20

* Extiende a la clase controller
*/

class MonedasController extends Controller {

	protected $entity = "Monedas";
	protected $parentEntity = "";

	public function IndexAction() {
		return $this->listAction();
	}
}
?>