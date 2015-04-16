<?php

/**
 * CONTROLLER FOR EmailBox
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 05.10.2013 14:37:08

 * Extiende a la clase controller
 */
class EmailBoxController extends Controller {

    protected $entity = "EmailBox";
    protected $parentEntity = "";

    /**
     * QUITO LOS PERMISOS DE INSERCCION, BORRARO Y ACTULIZACION
     * 
     * @param array $request
     */
    public function __construct($request) {
        parent::__construct($request);

        $this->values['permisos']['permisosModulo']['IN'] = false;
        $this->values['permisos']['permisosModulo']['DE'] = false;
        $this->values['permisos']['permisosModulo']['UP'] = false;
    }

    public function IndexAction() {
        return $this->listAction();
    }

    /**
     * SI NO ES SUPER SOLO MUESTRO LOS EMAIL DEL USUARIO EN CURSO
     * @param type $aditionalFilter
     * @return type
     */
    public function listAction($aditionalFilter = '') {

        if ($_SESSION['usuarioPortal']['Id'] != '1') {
            if ($aditionalFilter != '')
                $aditionalFilter .= " AND ";
            $aditionalFilter .= "De='{$_SESSION['usuarioPortal']['email']}'";
        }

        return parent::listAction($aditionalFilter);
    }

}

?>