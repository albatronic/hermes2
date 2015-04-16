<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.01.2014 19:54:31
 */

/**
 * @orm:Entity(PlanContable)
 */
class PlanContable extends PlanContableEntity {

    public function __toString() {
        return $this->getCuenta();
    }

}

?>