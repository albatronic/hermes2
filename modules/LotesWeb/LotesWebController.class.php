<?php
/**
* CONTROLLER FOR LotesWeb
* @author: Sergio Perez <sergio.perez@albatronic.com>
* @copyright: INFORMATICA ALBATRONIC SL 
* @date 20.06.2014 18:30:08

* Extiende a la clase controller
*/

class LotesWebController extends Controller {

	protected $entity = "LotesWeb";
	protected $parentEntity = "";

	public function IndexAction() {
		return $this->listAction();
	}
}
?>