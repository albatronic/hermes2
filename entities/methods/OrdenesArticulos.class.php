<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.02.2013 13:00:03
 */

/**
 * @orm:Entity(OrdenesArticulos)
 */
class OrdenesArticulos extends OrdenesArticulosEntity {

    public function __toString() {
        return $this->getId();
    }

    public function save() {
        $this->Publish = 1;
        return parent::save();
    }

    public function create() {
        $this->Publish = 1;
        return parent::create();
    }

    /**
     * Devuelve un array con los id's de las reglas a las
     * que está sujeto el artículo
     * 
     * @param int $idArticulo El id de articulo
     * @return array
     */
    public function getReglasArticulo($idArticulo) {

        $array = array();

        $reglas = $this->cargaCondicion("DISTINCT IDRegla", "IDArticulo='{$idArticulo}'");
        foreach ($reglas as $regla)
            $array[] = $regla['IDRegla'];

        return $array;
    }

    /**
     * Borra todas las entradas de la regla $idRegla en los
     * órdenes de articulos
     * 
     * @param int $idRegla El id de la regla
     */
    public function borraOrdenesRegla($idRegla) {
        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "delete from {$this->getDataBaseName()}.{$this->getTableName()} WHERE IDRegla='{$idRegla}'";
            $em->query($query);
            $em->desConecta();
        }
        unset($em);
    }

    /**
     * Borra todas las entradas del artículo $idArticulo en los
     * órdenes de artículos
     * 
     * @param int $idArticulo El id del articulo
     */
    public function borraOrdenesArticulo($idArticulo) {
        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "delete from {$this->getDataBaseName()}.{$this->getTableName()} WHERE IDArticulo='{$idArticulo}'";
            $em->query($query);
            $em->desConecta();
        }
        unset($em);
    }

    /**
     * Devuelve un array de objetos articulos que cumplen la
     * regla $idRegla, ordenados por SortOrder ASC
     * 
     * El array tendrá $nItems elementos.
     * 
     * @param int $idRegla El id de la regla
     * @param int $nItems El número máximo de elementos a devolver. Opcional, por defecto todos.
     * @return \Articulos array de objetos Articulos
     */
    public function getArticulos($idRegla, $nItems = 999999) {

        $array = array();

        if ($nItems <= 0) {
            $nItems = 999999;
        }

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            
            // Condición de vigencia
            $ahora = date("Y-m-d H:i:s");
            $filtro = "(a.Deleted='0') AND (a.Publish='1') AND (a.ActiveFrom<='{$ahora}') AND ( (a.ActiveTo>='{$ahora}') or (a.ActiveTo='0000-00-00 00:00:00') )";

            $query = "
                SELECT a.IDArticulo as Id
                FROM {$em->getDataBase()}.ErpOrdenesArticulos r, {$em->getDataBase()}.ErpArticulos a
                WHERE r.IDRegla='{$idRegla}' AND r.IDArticulo=a.IDArticulo AND a.Vigente='1' AND {$filtro}
                ORDER BY r.SortOrder ASC
                LIMIT {$nItems}";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $array[$row['Id']] = new Articulos($row['Id']);
            }
        }

        return $array;
    }

}