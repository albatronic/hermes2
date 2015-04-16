<?php

/**
 * Description of CambioClienteFacturaController
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 22-Noviembre-2012
 *
 */
class CambioClienteFacturaController extends Controller {

    protected $entity = "CambioClienteFactura";
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
    }

    public function IndexAction() {

        return array(
            'template' => $this->entity . '/index.html.twig',
            'values' => $this->values
        );
    }

    /**
     * Busca un factura emitidad por número de factura
     * @return array
     */
    public function BuscarAction() {

        switch ($this->request["METHOD"]) {
            case 'POST':
                if ($this->values['permisos']['permisosModulo']['UP']) {
                    $fEmitida = new FemitidasCab();
                    $rows = $fEmitida->cargaCondicion('IDFactura,Asiento', "NumeroFactura='{$this->request['numeroFactura']}'");
                    unset($fEmitida);

                    if ($rows[0]['IDFactura']) {
                        $this->values['factura'] = new FemitidasCab($rows[0]['IDFactura']);
                    } else {
                        $this->values['errores'][] = "No existe esa factura";
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

    public function CambiarAction() {

        switch ($this->request["METHOD"]) {
            case 'POST':
                if ($this->values['permisos']['permisosModulo']['UP']) {
                    if ($this->valida()) {
                        $this->cambiarCliente();
                    }

                    return $this->IndexAction();
                } else {
                    $template = "_global/forbiden.html.twig";
                }

                break;

            case 'GET':
                $template = "_global/forbiden.html.twig";
                break;
        }

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Realiza el cambio de cliente en la factura, albaranes y recibos
     */
    private function cambiarCliente() {

        $ok = false;

        // Cambiar factura
        $femitidas = new FemitidasCab();
        $filtro = "NumeroFactura='{$this->request['numeroFactura']}' AND IDCliente='{$this->request['idClienteAnterior']}'";
        $okFactura = $femitidas->queryUpdate(array("IDCliente" => $this->request['idClienteNuevo']), $filtro);
        $this->values['errores'] = $femitidas->getErrores();

        if ($okFactura) {

            $this->values['mensaje'][] = "Se ha cambiado " . $okFactura . " factura.";
            // Cambiar albaran/es
            $albaranes = new AlbaranesCab();
            $filtro = "IDFactura='{$this->request['idFactura']}' AND IDCliente='{$this->request['idClienteAnterior']}'";
            $nAlbaranes = $albaranes->queryUpdate(array("IDCliente" => $this->request['idClienteNuevo']), $filtro);
            $this->values['errores'] = $albaranes->getErrores();
            $this->values['mensaje'][] = "Se han cambiado " . $nAlbaranes . " albaranes.";

            // Cambiar recibos
            $recibos = new RecibosClientes();
            $filtro = "IDFactura='{$this->request['idFactura']}' AND IDCliente='{$this->request['idClienteAnterior']}'";
            $nRecibos = $recibos->queryUpdate(array("IDCliente" => $this->request['idClienteNuevo']), $filtro);
            $this->values['errores'] = $recibos->getErrores();
            $this->values['mensaje'][] = "Se han cambiado " . $nRecibos . " recibos.";
        }
    }

    /**
     * Realiza la validación previa antes del cambio.
     * 
     * Concretamente que el cliente origen y el destino
     * no sean el mismo
     * 
     * @return boolean
     */
    private function valida() {

        if ($this->request['idClienteNuevo'] == '')
            $this->values['errores'][] = "Debe indicar el cliente nuevo";
        if ($this->request['numeroFactura'] == '')
            $this->values['errores'][] = "Debe indicar el número de factura";
        if ($this->request['idClienteNuevo'] == $this->request['idClienteAnterior'])
            $this->values['errores'][] = "El cliente origen y destino debe ser diferente";

        return (count($this->values['errores']) == 0);
    }

}