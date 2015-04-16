<?php

/**
 * CONTROLLER FOR CpanTextos
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 01.02.2014 21:09:45

 * Extiende a la clase controller
 */
class CpanTextosController extends Controller {

    protected $entity = "CpanTextos";
    protected $parentEntity = "";

    public function __construct($request) {
        parent::__construct($request);

        $array = $this->getArrayIdiomas();
        foreach ($array as $idioma) {
            $idiomasDisponibles[$idioma['Id']] = $idioma['Value'];
        }
        $this->values['idiomas'] = $idiomasDisponibles;

        $urls = new CpanUrlAmigables();
        $rows = $urls->cargaCondicion("distinct Controller as Id,Controller as Value", "1", "Controller ASC");
        unset($urls);

        $rows[] = array("Id" => "", "Value" => "** Para todas **");
        $this->values['controllers'] = $rows;
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
                    $datos = new $this->entity();
                    $datos->bind($this->request[$this->entity]);
                    if ($datos->getPrimaryKeyValue())
                        $datos->save();
                    else
                        $datos->create();

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

        $item = new $this->entity();

        $items = array();
        $arrayIdiomas = $this->getArrayIdiomas();
        foreach ($arrayIdiomas as $idioma) {
            $items[0][''][$idioma['Id']] = array(
                "Id" => 0,
                //"Controller" => "",
                //"Lang" => $idioma['Id'],
                //"Clave" => "",
                "Observations" => "",
            );
        }
        $this->listado->makeQuery($aditionalFilter);

        $arrayQuery = $this->listado->getArrayQuery();
        $condicion = $arrayQuery['WHERE'];
        $rows = $item->cargaCondicion("Id,Controller,Lang,Clave,Observations", $condicion, "Controller ASC, Clave ASC, Lang ASC");
        unset($item);

        foreach ($rows as $row) {
            $items[$row['Controller']][$row['Clave']][$row['Lang']] = array(
                "Id" => $row["Id"],
                "Observations" => $row["Observations"],
            );
            foreach ($arrayIdiomas as $idioma) {
                if (!isset($items[$row['Controller']][$row['Clave']][$idioma["Id"]])) {
                    $items[$row['Controller']][$row['Clave']][$idioma["Id"]] = array(
                        "Id" => 0,
                        //"Controller" => $row['Controller'],
                        //"Lang" => $idioma["Id"],
                        //"Clave" => $row['Clave'],
                        "Observations" => '',
                    );
                }
            }
        }
        //print_r($items);
        $this->values['textos'] = $items;

        return array(
            'template' => $this->entity . "/list.html.twig",
            'values' => $this->values,
        );
    }

}

?>