<?php

/**
 * Description of Fabricantes
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class Fabricantes extends FabricantesEntity {

    public function __toString() {
        return $this->getTitulo();
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Titulo';
        return parent::fetchAll($column, $default);
    }

    /**
     * Devuelve el número de artículos del fabricante en curso
     * 
     * No tiene en cuenta los no vigentes
     * 
     * @return integer El número de artículos
     */
    public function getNArticulos() {

        $articulo = new Articulos();
        $rows = $articulo->cargaCondicion("count(IDArticulo) as nArticulos", "IDFabricante='{$this->IDFabricante}' and Vigente='1'");
        unset($articulo);

        return $rows[0]['nArticulos'];
    }

    /**
     * Devuelve un array de objetos familias que son categorias y que están relacionados
     * con el fabricante en curso
     * 
     * @return \Fabricantes
     */
    public function getCategorias() {

        $array = array();
        
        $articulo = new Articulos();
        $rows = $articulo->cargaCondicion("distinct IDCategoria", "IDFabricante='{$this->IDFabricante}' and Vigente='1'");
        unset($articulo);  

        foreach ($rows as $row)
            $array[] = new Familias($row['IDCategoria']);
        
        return $array;
    }    
}

?>
