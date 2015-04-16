<?php
/**
* CONTROLLER FOR PedidosWebLog
* @author: Sergio Perez <sergio.perez@albatronic.com>
* @copyright: INFORMATICA ALBATRONIC SL 
* @date 20.09.2014 17:49:23

* Extiende a la clase controller
*/

class PedidosWebLogController extends Controller {

	protected $entity = "PedidosWebLog";
	protected $parentEntity = "";

	public function IndexAction() {
		return $this->listAction();
	}
}
?>