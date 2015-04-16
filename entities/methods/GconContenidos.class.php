<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 29.10.2012 20:09:46
 */

/**
 * @orm:Entity(GconContenidos)
 */
class GconContenidos extends GconContenidosEntity {

    public function __toString() {
        return $this->getTitulo();
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Titulo';
        return parent::fetchAll($column, $default);
    }

    /**
     * Marco de borrado el contenido y borro físicamente
     * sus eventuales eventos
     * 
     * @return boolean
     */
    public function delete() {
        
        $id = $this->getId();
        $ok = parent::delete();
        
        if ($ok) {
            $eventos = new EvenEventos();
            $em = new EntityManager($this->getConectionName());
            if (is_resource($em->getDbLink())) {
                $query = "delete from {$eventos->getDataBaseName()}.{$eventos->getTableName()} where Entidad='GconContenidos' and IdEntidad='{$id}'";
                $em->query($query);
                $em->desConecta();               
            }
            unset($eventos);
            unset($em);          
        }
        
        return $ok;
    }
    
    public function validaLogico() {

        parent::validaLogico();

        if (count($this->_errores) == 0) {
            if ($this->BlogOrden == 0)
                $this->BlogOrden = $this->SortOrder;
            if ($this->NoticiaOrden == 0)
                $this->NoticiaOrden = $this->SortOrder;

            // Si no es evento, borrar los posibles eventos asociados
            if (($this->Id) and (!$this->EsEvento)) {
                $em = new EntityManager($this->getConectionName());
                if ($em->getDbLink()) {
                    $query = "delete from EvenEventos where Entidad='GconContenidos' and IdEntidad='{$this->Id}'";
                    $em->query($query);
                    $em->desConecta();
                }
                unset($em);
            }
        }
    }

    /**
     * Devuelve un array anidado de secciones de contenidos
     * 
     * @return array Array de zonas y banners
     */
    public function getArbolHijos($conContenidos = true, $entidadRelacionada = '', $idEntidadRelacionada = '') {

        if ($conContenidos == '') $conContenidos = true;
        
        $secciones = new GconSecciones();
        $arbolSecciones = $secciones->getArbolHijos($conContenidos, $entidadRelacionada, $idEntidadRelacionada);
        unset($secciones);

        return $arbolSecciones;
    }

}

?>