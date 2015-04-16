<?php

/**
 * Description of Promociones
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class Promociones extends PromocionesEntity {

    Protected $Publish = 1;
    Protected $Privacy = 2;

    public function __toString() {
        return $this->getTitulo();
    }

    protected function validaLogico() {

        parent::validaLogico();

        if ($this->IDFamilia != '')
            $this->IDArticulo = NULL;
        if (($this->IDFamilia == '') and ( $this->IDArticulo == ''))
            $this->_errores[] = 'Debe indicar una familia o un articulo concreto';
    }

    /**
     * Devuelve array de objetos Articulos con los
     * artículos de la promoción en curso
     * 
     * @param type $nItems Cero o negativo, sin límite. Positivo indica el número de artículos a devolver
     * @return array Array de objetos Artículos
     */
    public function getArticulos($nItems = 0) {

        $limit = ($nItems <= 0) ? "" : "limit {$nItems}";

        $array = array();
        
        if ($this->getIDFamilia()->getPrimaryKeyValue()) {
            $articulo = new Articulos();          
            $rows = $articulo->cargaCondicion("IDArticulo","IDFamilia='{$this->getIDFamilia()->getPrimaryKeyValue()}'","PublishedAt DESC {$limit}");
            unset($articulo);
            foreach ($rows as $row) {
                $array[] = new Articulos($row['IDArticulo']);
            }         
        } else {
            $array[] = $this->getIDArticulo();
        }

        return $array();
    }

}
