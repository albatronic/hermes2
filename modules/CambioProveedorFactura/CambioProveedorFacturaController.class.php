<?php

/**
 * Description of CambioProveedorFacturaController
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 29-Mayo-2014
 *
 */
class CambioProveedorFacturaController extends Controller {

    protected $entity = "CambioProveedorFactura";
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
     * Busca un factura recibida por número de factura
     * @return array
     */
    public function BuscarAction() {

        switch ($this->request["METHOD"]) {
            case 'POST':
                if ($this->values['permisos']['permisosModulo']['UP']) {
                    $fEmitida = new FrecibidasCab();
                    $rows = $fEmitida->cargaCondicion('IDFactura,Asiento', "NumeroFactura='{$this->request['numeroFactura']}'");
                    unset($fEmitida);

                    if ($rows[0]['IDFactura']) {
                        $this->values['factura'] = new FrecibidasCab($rows[0]['IDFactura']);
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
                    if ($this->valida())
                        $this->cambiarProveedor();

                    return $this->IndexAction();
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
     * Realiza el cambio de proveedor en la factura, pedidos y recibos
     */
    private function cambiarProveedor() {

        $ok = false;

        $em = new EntityManager($this->form->getConection());
        if ($em->getDbLink()) {

            // Cambiar factura
            $filtro = "NumeroFactura='{$this->request['numeroFactura']}' AND IDProveedor='{$this->request['idProveedorAnterior']}'";
            $query = "update frecibidas_cab set IDProveedor='{$this->request['idProveedorNuevo']}' where {$filtro}";
            $em->query($query);
            $this->values['errores'] = $em->getError();
            $okFactura = $em->getAffectedRows();

            if ($okFactura) {
                
                $this->values['mensaje'][] = "Se ha cambiado " . $okFactura . " factura.";
                // Cambiar pedido/s
                $filtro = "IDFactura='{$this->request['idFactura']}' AND IDProveedor='{$this->request['idProveedorAnterior']}'";
                $query = "update pedidos_cab set IDProveedor='{$this->request['idProveedorNuevo']}' where {$filtro}";
                $em->query($query);
                $this->values['errores'] = $em->getError();
                $nPedidos = $em->getAffectedRows();
                $this->values['mensaje'][] = "Se han cambiado " . $nPedidos . " pedidos.";
                
                // Cambiar recibos
                $filtro = "IDFactura='{$this->request['idFactura']}' AND IDProveedor='{$this->request['idProveedorAnterior']}'";
                $query = "update recibos_proveedores set IDProveedor='{$this->request['idProveedorNuevo']}' where {$filtro}";
                $em->query($query);
                $this->values['errores'] = $em->getError();
                $nRecibos = $em->getAffectedRows();
                $this->values['mensaje'][] = "Se han cambiado " . $nRecibos . " recibos.";
                
            }
        }

        $em->desConecta();
        unset($em);
    }

    /**
     * Realiza la validación previa antes del cambio.
     * 
     * Concretamente que el proveedor origen y el destino
     * no sean el mismo
     * 
     * @return boolean
     */
    private function valida() {

        if ($this->request['idProveedorNuevo'] == '')
            $this->values['errores'][] = "Debe indicar el proveedor nuevo";
        if ($this->request['numeroFactura'] == '')
            $this->values['errores'][] = "Debe indicar el número de factura";
        if ($this->request['idProveedorNuevo'] == $this->request['idProveedorAnterior'])
            $this->values['errores'][] = "El proveedor origen y destino debe ser diferente";

        return (count($this->values['errores']) == 0);

    }

}

?>
