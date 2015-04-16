<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 25.12.2012 20:39:54
 */

/**
 * @orm:Entity(AlbmAlbumes)
 */
class AlbmAlbumes extends AlbmAlbumesEntity {

    public function __toString() {
        return $this->Titulo;
    }

    public function fetchAll($idSeccion='') {

        $array = array();
        
        $filtro = ($idSeccion == '') ? "(1)" : "(IdSeccion='{$idSeccion}')";
        
        $seccion = new AlbmSecciones();
        $secciones = $seccion->cargaCondicion("Id,Titulo",$filtro,"SortOrder ASC");
        unset($seccion);
        
        foreach ($secciones as $seccion) {
            $array[$seccion['Id']]['Titulo']=$seccion['Titulo'];
            $array[$seccion['Id']]['items'][] = array('Id' => 0,'Value'=> ':: Indique un valor');
            $album = new AlbmAlbumes();
            $albumes = $album->cargaCondicion("Id,Titulo","IdSeccion='{$seccion['Id']}'","SortOrder ASC");
            unset($album);
            foreach ($albumes as $album) {
                $array[$seccion['Id']]['items'][] = array('Id' => $album['Id'],'Value'=> $album['Titulo']);
            }
        }

        return $array;
    }

    /**
     * Pone el orden 'OrdenPortada'
     */
    public function validaLogico() {

        parent::validaLogico();

        if (count($this->_errores) == 0)
            $this->OrdenPortada = $this->SortOrder;
    }

}

?>