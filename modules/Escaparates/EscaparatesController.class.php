<?php

/**
 * CONTROLLER FOR Escaparates
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 07.02.2013 15:32:56

 * Extiende a la clase controller
 */
class EscaparatesController extends Controller {

    protected $entity = "Escaparates";
    protected $parentEntity = "";

    public function __construct($request) {
        parent::__construct($request);
    
        $urls = new CpanUrlAmigables();
        $rows = $urls->cargaCondicion("distinct Controller","1","Controller ASC");
        $this->values['controllers'] = $rows;
        unset($urls);
        
    }
    
    public function IndexAction() {
        return $this->listAction();
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

                case 'POST': //CREAR NUEVO REGISTRO
                    $datos = new $this->entity();
                    $datos->bind($this->request[$this->entity]);

                    if ($datos->valida($this->form->getRules())) {
                        $datos->create();
                    }

                    $this->values['alertas'] = $datos->getAlertas();
                    $this->values['errores'] = $datos->getErrores();
                    unset($datos);
                    return $this->listAction();
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
                    if ($datos->valida($this->form->getRules()))
                        $datos->save();

                    $this->values['errores'] = $datos->getErrores();
                    $this->values['alertas'] = $datos->getAlertas();

                    unset($datos);
                    return $this->listAction();
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
                    return $this->listAction();
                } else {
                    return array('template' => '_global/forbiden.html.twig');
                }
                break;
        }
    }

    /**
     * Devuelve un array con los objetos zonas web
     * añadiendo uno vacio para crear una zona nueva
     * 
     * @return array Array template, values
     */
    public function listAction($aditionalFilter = '') {

        $item = new Escaparates();

        $items[] = $item;

        $rows = $item->cargaCondicion("Id", "1", "Controller ASC, Zona ASC, Id ASC");
        unset($item);

        foreach ($rows as $row)
            $items[] = new Escaparates($row['Id']);

        $this->values['zonas'] = $items;

        return array(
            'template' => $this->entity . "/list.html.twig",
            'values' => $this->values,
        );
    }

}