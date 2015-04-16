<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.07.2013 12:30:18
 */

/**
 * @orm:Entity(BolEnvios)
 */
class BolEnvios extends BolEnviosEntity {
	public function __toString() {
		return $this->getId();
	}
}
?>