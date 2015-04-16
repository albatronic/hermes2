<?php

/**
 * CONTROLLER FOR FrecibidasLineas
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 00:45:16

 * Extiende a la clase controller
 */
class FrecibidasLineasController extends Controller {

    protected $entity = "FrecibidasLineas";
    protected $parentEntity = "FrecibidasCab";

    public function __construct($request) {
        // Cargar lo que viene en el request
        $this->request = $request;

        $this->permisos = new ControlAcceso();
        $this->values['permisos'] = $this->permisos->getPermisos();
        $this->values['request'] = $this->request;
        $this->values['linkBy'] = array(
            'id' => 'IDFactura',
            'value' => '',
        );
    }

    public function listAction($idFactura='') {

        if ($this->values['permisos']['permisosModulo']['CO']) {
            if ($idFactura == '')
                $idFactura = $this->request[2];

            $lis = new $this->entity();
            $rows = $lis->cargaCondicion('IDLinea', "IDFactura = '" . $idFactura . "'", 'IDLinea ASC');
            foreach ($rows as $row) {
                $lineas[] = new $this->entity($row['IDLinea']);
            }

            $template = $this->entity . '/list.html.twig';

            $this->values['linkBy']['value'] = $idFactura;
            $this->values['listado']['data'] = $lineas;

            unset($lis);
            unset($lineas);

            return array('template' => $template, 'values' => $this->values);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Edita, actualiza o borrar un registro
     *
     * Viene siempre por POST
     * Actualiza o Borrar según el valor de $this->request['accion']
     *
     * @return array con el template y valores a renderizar
     */
    public function editAction() {

        //COGER DEL REQUEST EL LINK A LA ENTIDAD PADRE
        if ($this->values['linkBy']['id'] != '') {
            $this->values['linkBy']['value'] = $this->request[$this->entity][$this->values['linkBy']['id']];
        }

        switch ($this->request['accion']) {
            case 'G': //GUARDAR DATOS
                if ($this->values['permisos']['permisosModulo']['UP']) {
                    $datos = new $this->entity($this->request[$this->entity]['IDLinea']);
                    $datos->bind($this->request[$this->entity]);
                    if ($datos->valida(array())) {
                        $datos->save();
                        $this->values['errores'] = $datos->getErrores();
                        $this->values['alertas'] = $datos->getAlertas();

                        //Recargo el objeto para refrescar las propiedas que
                        //hayan podido ser motivo de algun calculo durante el proceso
                        //de guardado.
                        $datos = new $this->entity($this->request[$this->entity][$datos->getPrimaryKeyName()]);
                    }

                    $this->values['datos'] = $datos;
                    unset($datos);
                    return $this->listAction($this->values['linkBy']['value']);
                } else {
                    return array('template' => '_global/forbiden.html.twig');
                }
                break;

        }
    }

}

?>