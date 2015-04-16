<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 05.10.2013 14:37:09
 */

/**
 * @orm:Entity(EmailBox)
 */
class EmailBox extends EmailBoxEntity {

    public function __toString() {
        return $this->getId();
    }

    public function create() {
        $this->setIp($_SERVER['REMOTE_ADDR']);
        return parent::create();
    }

}

?>