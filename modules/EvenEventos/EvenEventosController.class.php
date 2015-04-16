<?php

/**
 * CONTROLLER FOR EvenEventos
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 08.12.2012 01:14:51

 * Extiende a la clase controller
 */
class EvenEventosController extends Controller {

    protected $entity = "EvenEventos";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::listAction();
    }

    /**
     * Crea un registro nuevo
     *
     * Siempre viene por POST
     * Si viene por POST crea un registro
     *
     * @return array con el template y valores a renderizar
     */
    public function newAction() {

        if ($this->values['permisos']['permisosModulo']['IN']) {
            switch ($this->request["METHOD"]) {

                case 'GET':
                case 'POST': //CREAR NUEVO REGISTRO
                    $datos = new $this->entity();
                    $datos->bind($this->request[$this->entity]);

                    if ($datos->create()) {
                        $this->values['alertas'] = $datos->getAlertas();
                    } else {
                        $this->values['errores'] = $datos->getErrores();
                    }
                    unset($datos);
                    return $this->listPopupAction($this->request[$this->entity]['Entidad'], $this->request[$this->entity]['IdEntidad']);
                    break;
            }
        } else {
            return array(
                'template' => '_global/forbiden.html.twig',
                'values' => $this->values);
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

        switch ($this->request['accion']) {
            case 'G': //GUARDAR DATOS
                if ($this->values['permisos']['permisosModulo']['UP']) {
                    $datos = new $this->entity($this->request[$this->entity]['Id']);
                    $datos->bind($this->request[$this->entity]);
                    if ($datos->save()) {
                        $this->values['errores'] = $datos->getErrores();
                        $this->values['alertas'] = $datos->getAlertas();
                    } else
                        $this->values['errores'] = $datos->getErrores();

                    unset($datos);
                    return $this->listPopupAction($this->request[$this->entity]['Entidad'], $this->request[$this->entity]['IdEntidad']);
                } else {
                    return array('template' => '_global/forbiden.html.twig');
                }
                break;

            case 'B': //BORRAR DATOS
                if ($this->values['permisos']['permisosModulo']['DE']) {
                    $datos = new $this->entity($this->request[$this->entity]['Id']);

                    if ($datos->erase()) {
                        $this->values['errores'] = array();
                    } else {
                        $this->values['errores'] = $datos->getErrores();
                    }
                    unset($datos);
                    return $this->listPopupAction($this->request[$this->entity]['Entidad'], $this->request[$this->entity]['IdEntidad']);
                } else {
                    return array('template' => '_global/forbiden.html.twig');
                }
                break;
        }
    }

    public function listPopupAction($entidad = '', $idEntidad = '') {

        if ($entidad == '')
            $entidad = $this->request[2];
        if ($idEntidad == '')
            $idEntidad = $this->request[3];

        $evento = new EvenEventos();

        if ($this->values['permisos']['permisosModulo']['IN']) {
            $evento->setEntidad($entidad);
            $evento->setIdEntidad($idEntidad);
            $evento->setFecha(date('d-m-Y'));
            $lineas[] = $evento;
        }

        $eventos = $evento->cargaCondicion("Id", "Entidad='{$entidad}' AND IdEntidad='{$idEntidad}'", "Fecha DESC,HoraInicio DESC");
        unset($evento);

        foreach ($eventos as $evento) {
            $lineas[] = new EvenEventos($evento['Id']);
        }

        $this->values['eventos'] = $lineas;

        return array(
            'template' => $this->entity . '/listPopup.html.twig',
            'values' => $this->values,
        );
    }

}

?>