<?php

/**
 * CONTROLLER FOR Empresas
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 19:41:33

 * Extiende a la clase controller
 */
class PcaeEmpresasController extends Controller {

    protected $entity = "PcaeEmpresas";
    protected $parentEntity = "";

    /**
     * SI EL USUARIO NO ES SUPER, LE QUITO LOS PERMISOS DE INSERTAR Y BORRAR
     * EMPRESAS
     * 
     * @param type $request
     */
    public function __construct($request) {
        parent::__construct($request);

        if ($_SESSION['usuarioPortal']['IdPerfil'] != 1) {
            $this->values['permisos']['permisosModulo']['IN'] = false;
            $this->values['permisos']['permisosModulo']['DE'] = false;
        }

    }

    public function IndexAction() {
        return $this->listAction();
    }

    /**
     * SOLO SE MUESTRAN LAS EMPRESAS A LAS QUE TIENE ACCESO EL USUARIO
     * 
     * @param type $aditionalFilter
     * @return type
     */
    public function listAction($aditionalFilter = '') {

        $empresasUsuarios = new PcaeEmpresasUsuarios();
        $tabla = $empresasUsuarios->getDataBaseName() . "." . $empresasUsuarios->getTableName();
        unset($empresasUsuarios);
        
        $aditionalFilter = "Id IN (select IdEmpresa from {$tabla} where {$tabla}.IdUsuario='{$_SESSION['usuarioPortal']['Id']}')";

        return parent::listAction($aditionalFilter);
    }

}

?>