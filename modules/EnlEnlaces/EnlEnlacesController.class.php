<?php

/**
 * CONTROLLER FOR EnlEnlaces
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 05.12.2012 00:20:32

 * Extiende a la clase controller
 */
class EnlEnlacesController extends Controller {

    protected $entity = "EnlEnlaces";
    protected $parentEntity = "";

    public function __construct($request) {
        parent::__construct($request);

        $this->values['objetoController'] = $this;
    }

    public function IndexAction() {
        return parent::newAction();
    }

    /**
     * Devuelve un array anidado de secciones de enlaces con sus enlaces
     * 
     * @return array Array de secciones y enlaces
     */
    public function getArbolSeccionesEnlaces() {

        $seccion = new EnlSecciones();
        $secciones = $seccion->cargaCondicion("Id,Titulo", "1", "SortOrder ASC");
        unset($seccion);

        $arbol = array();

        foreach ($secciones as $seccion) {
            $enlace = new EnlEnlaces();
            $enlaces = $enlace->cargaCondicion('Id', "IdSeccion='{$seccion['Id']}'", "SortOrder ASC");
            unset($enlace);

            $arbol[$seccion['Id']]['titulo'] = $seccion['Titulo'];
            $arbol[$seccion['Id']]['nEnlaces'] = count($enlaces);
            foreach ($enlaces as $enlace) {
                $arbol[$seccion['Id']]['enlaces'][] = new EnlEnlaces($enlace['Id']);
            }
        }

        return $arbol;
    }

}

?>