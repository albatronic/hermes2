<?php

/**
 * CONTROLLER FOR BannBanners
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 09.12.2012 08:36:04

 * Extiende a la clase controller
 */
class BannBannersController extends Controller {

    protected $entity = "BannBanners";
    protected $parentEntity = "";

    public function __construct($request) {
        parent::__construct($request);

        $this->values['objetoController'] = $this;
    }

    public function IndexAction() {
        return parent::newAction();
    }
    
    /**
     * Devuelve un array anidado de zonas de banners con sus banners
     * 
     * El array tiene los siguientes elementos:
     * 
     * - titulo => el título de la zona
     * - nBanners => el número de banners que hay en la zona
     * - banners => array de objetos BannBanners, que son los banners que hay en la zona
     * 
     * @return array Array de zonas y banners
     */
    public function getArbolZonasBanners() {

        $zona = new BannZonas();
        $zonas = $zona->cargaCondicion("Id,Titulo", "1", "SortOrder ASC");
        unset($zona);

        $arbol = array();

        foreach ($zonas as $zona) {
            $banner = new BannBanners();
            $banners = $banner->cargaCondicion('Id', "IdZona='{$zona['Id']}'", "SortOrder ASC");
            unset($banner);

            $arbol[$zona['Id']]['titulo'] = $zona['Titulo'];
            $arbol[$zona['Id']]['nBanners'] = count($banners);
            foreach ($banners as $banner)
                $arbol[$zona['Id']]['banners'][] = new BannBanners($banner['Id']);
        }

        return $arbol;
    }

}

?>