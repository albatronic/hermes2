<?php

/**
 * Description of CambioComercialController
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 23-abr-2012
 *
 */
class CambioComercialController extends Controller {

    protected $entity = "CambioComercial";
    protected $parentEntity = "";

    public function __construct($request) {

        // Cargar lo que viene en el request
        $this->request = $request;

        // Cargar la configuracion del modulo (modules/moduloName/config.yaml)
        $this->form = new Form($this->entity);

        // Cargar los permisos.
        // Si la entidad no está sujeta a control de permisos, se habilitan todos
        if ($this->form->getPermissionControl()) {
            if ($this->parentEntity == '')
                $this->permisos = new ControlAcceso($this->entity);
            else
                $this->permisos = new ControlAcceso($this->parentEntity);
        } else
            $this->permisos = new ControlAcceso();

        $this->values['titulo'] = $this->form->getTitle();
        $this->values['ayuda'] = $this->form->getHelpFile();
        $this->values['permisos'] = $this->permisos->getPermisos();
        $this->values['request'] = $this->request;
        $this->values['linkBy'] = array(
            'id' => $this->form->getLinkBy(),
            'value' => '',
        );

        // Si se ha indicado una entidad en el config.yml del controlador
        // pero no se ha definido la conexion, se muestra un error
        if (($this->form->getEntity()) and (!$this->form->getConection())) {
            echo "No se ha definido la conexión para la entidad: " . $this->entity;
        }

        // QUITAR LOS COMENTARIOS PARA Actualizar los favoritos para el usuario
        //if ($this->form->getFavouriteControl())
        //    $this->actualizaFavoritos();;
    }

    public function indexAction() {

        if ($this->values['permisos']['permisosModulo']['UP']) {
            $agentes = new Agentes();
            $zonas = new Zonas();

            $this->values['comerciales'] = $agentes->getComerciales('', $_SESSION['suc'], false);
            $this->values['zonas'] = $zonas->fetchAll($_SESSION['suc']);

            unset($agentes);
            unset($zonas);

            $template = $this->entity . '/index.html.twig';
        } else
            $template = "_global/forbiden.html.twig";

        return array('template' => $template, 'values' => $this->values);
    }

    public function cambiarAction() {

        switch ($this->request["METHOD"]) {
            case 'POST':
                if ($this->values['permisos']['permisosModulo']['UP']) {
                    if ($this->valida()) {
                        $this->cambiarEnFichaCliente();
                        if ($this->request['Dentrega'] == 'on')
                            $this->cambiarEnDireccionesEntrega();
                    }

                    return $this->indexAction();
                } else
                    $template = "_global/forbiden.html.twig";

                break;

            case 'GET':
                $template = "_global/forbiden.html.twig";
                break;
        }

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Realiza el cambio de comercial en la ficha
     * del cliente
     */
    private function cambiarEnFichaCliente() {

        $filtro = "IDComercial='{$this->request['ComercialOrigen']}'";
        if ($this->request['Zona'] != '')
            $filtro .= " and IDZona='{$this->request['Zona']}'";

        $cliente = new Clientes();
        $numeroCambios = $cliente->queryUpdate(array("IDComercial" => $this->request['ComercialDestino']), $filtro);
        $this->values['errores'] = $cliente->getErrores();
        unset($cliente);

        $this->values['mensaje'][] = "Se han cambiado " . $numeroCambios . " clientes.";
    }

    /**
     * Realiza el cambio de comercial en la dirección de entrega
     */
    private function cambiarEnDireccionesEntrega() {

        $numeroCambios = 0;

        $tablas = "ErpClientesDentrega d";
        $filtro = "d.IDComercial='{$this->request['ComercialOrigen']}'";
        if ($this->request['Zona'] != '') {
            $tablas .= " , ErpClientes c";
            $filtro .= " and d.IDCliente = c.IDCliente";
            $filtro .= " and c.IDZona='{$this->request['Zona']}'";
        }

        $em = new EntityManager($this->form->getConection());
        if ($em->getDbLink()) {
            $query = "update {$tablas} set d.IDComercial='{$this->request['ComercialDestino']}' where {$filtro}";
            $em->query($query);
            $this->values['errores'] = $em->getError();
            $numeroCambios = $em->getAffectedRows();
            $em->desConecta();
        }
        unset($em);

        $this->values['mensaje'][] = "Se han cambiado " . $numeroCambios . " direcciones de entrega.";
    }

    /**
     * Realiza la validación previa antes del cambio.
     * 
     * Concretamente que el comercial origen y el destino
     * no sean el mismo
     * 
     * @return boolean
     */
    private function valida() {

        $ok = true;

        if ($this->request['ComercialOrigen'] == $this->request['ComercialDestino']) {
            $this->values['errores'][] = "El comercial origen y destino debe ser diferente";
            $ok = false;
        }

        return $ok;
    }

}

?>
