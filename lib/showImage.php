<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include "autoloader.inc";

$fileName = "../docs/docs" . $_SESSION['emp'] . "/catalog/" . $_GET['img'];
if (file_exists($fileName)) {
    $thumb = new Thumb();
    $thumb->loadImage($fileName);
    $thumb->resize($_GET['w'],'width');
    $thumb->show();
}

