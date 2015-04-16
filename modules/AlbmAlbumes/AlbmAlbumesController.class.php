<?php

/**
 * CONTROLLER FOR AlbmAlbumes
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 05.12.2012 00:51:11

 * Extiende a la clase controller
 */
class AlbmAlbumesController extends Controller {

    protected $entity = "AlbmAlbumes";
    protected $parentEntity = "";

    public function __construct($request) {
        parent::__construct($request);

        $this->values['objetoController'] = $this;
    }

    public function IndexAction() {
        return parent::newAction();
    }
    
    /**
     * Devuelve un array anidado de secciones de albumes con sus albumes
     * 
     * @return array Array de secciones y albumes
     */
    public function getArbolSeccionesAlbumes() {

        $seccion = new AlbmSecciones();
        $secciones = $seccion->cargaCondicion("Id,Titulo", "1", "SortOrder ASC");
        unset($seccion);

        $arbol = array();

        foreach ($secciones as $seccion) {
            $album = new AlbmAlbumes();
            $albumes = $album->cargaCondicion('Id', "IdSeccion='{$seccion['Id']}'", "SortOrder ASC");
            unset($album);

            $arbol[$seccion['Id']]['titulo'] = $seccion['Titulo'];
            $arbol[$seccion['Id']]['nAlbumes'] = count($albumes);
            foreach ($albumes as $album)
                $arbol[$seccion['Id']]['albumes'][] = new AlbmAlbumes($album['Id']);
        }

        return $arbol;
    }

}

?>