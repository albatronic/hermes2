<?php

/**
 * CONTROLLER FOR TablaPortes
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 07.06.2011 00:45:18

 * Extiende a la clase controller
 */

class TablaPortesController extends Controller {

    protected $entity = "TablaPortes";
    protected $parentEntity = "Agencias";


    public function __construct($request) {
        // Cargar lo que viene en el request
        $this->request = $request;

        // Cargar la configuracion del modulo (modules/moduloName/config.yaml)
        $this->form = new Form($this->entity);

        $this->permisos = new ControlAcceso();
        $this->values['permisos'] = $this->permisos->getPermisos($this->parentEntity);
        $this->values['request'] = $this->request;
        $this->values['linkBy'] = array(
            'id' => 'IDAgencia',
            'value' => '',
        );
    }

    public function listAction($idAgencia = '') {

        if ($this->values['permisos']['permisosModulo']['CO']) {
            if ($idAgencia == '')
                $idAgencia = $this->request[2];

            $agencias = new Agencias($idAgencia);
            $zonas = new ZonasTransporte();
            $tramos = new TramosKilos();


            $this->values['idAgencia'] = $idAgencia;
            $this->values['tablaPortes'] = $agencias->getTablaPortes();
            $this->values['zonasTransporte'] = $zonas->fetchAll('Zona',false);
            $this->values['tramosKilos'] = $tramos->fetchAll(false);

            unset($agencias);
            unset($zonas);
            unset($tramos);

            return array('template' => $this->entity . '/form.html.twig', 'values' => $this->values);

        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }
}

?>