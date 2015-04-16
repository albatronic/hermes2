<?php

/**
 * CONTROLLER FOR SldSliders
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 10.12.2012 17:38:33

 * Extiende a la clase controller
 */
class SldSlidersController extends Controller {

    protected $entity = "SldSliders";
    protected $parentEntity = "";

    public function __construct($request) {
        parent::__construct($request);

        $this->values['objetoController'] = $this;
    }

    public function IndexAction() {
        return parent::newAction();
    }

    /**
     * Devuelve un array anidado de zonas de sliders con sus sliders
     * 
     * @return array Array de zonas y sliders
     */
    public function getArbolZonasSliders() {

        $zona = new SldZonas();
        $zonas = $zona->cargaCondicion("Id,Titulo", "1", "SortOrder ASC");
        unset($zona);

        $arbol = array();

        foreach ($zonas as $zona) {
            $slider = new SldSliders();
            $sliders = $slider->cargaCondicion('Id', "IdZona='{$zona['Id']}'", "SortOrder ASC");
            unset($slider);

            $arbol[$zona['Id']]['titulo'] = $zona['Titulo'];
            $arbol[$zona['Id']]['nSliders'] = count($sliders);            
            foreach ($sliders as $slider)
                $arbol[$zona['Id']]['sliders'][] = new SldSliders($slider['Id']);
        }

        return $arbol;
    }

}

?>