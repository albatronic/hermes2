<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 07.02.2013 15:15:14
 */

/**
 * @orm:Entity(Escaparates)
 */
class Escaparates extends EscaparatesEntity {

    public function __toString() {
        return $this->getId();
    }
    
    public function create() {
        
        $this->Publish = 1;
        return parent::create();
    }
   
    /**
     * Guardo la regla, borro los eventuales ordenes
     * que existieran y los vuelvo a crear.
     * 
     * @return boolean
     */
    public function save() {

        $ok = parent::save();
        if ($ok) {
            $ordenes = new OrdenesArticulos();
            $ordenes->borraOrdenesRegla($this->Id);
            unset($ordenes);
            $this->aplicaRegla();
        }
        return $ok;
    }

    /**
     * Marca de borrado la zona y borra fisícamente los
     * ordenes de articulos involucrados en dicha zona
     * 
     * @return boolean
     */
    public function erase() {

        $idRegla = $this->Id;

        $ok = parent::erase();

        if ($ok) {
            // Borrar los ordenes de los artículos involucrados
            // en la zona/regla borrada        
            $ordenes = new OrdenesArticulos();
            $ordenes->borraOrdenesRegla($idRegla);
            unset($ordenes);
        }

        return $ok;
    }

    public function validaLogico() {
        parent::validaLogico();

        if ($this->NItems < 0)
            $this->NItems = 0;
        if ($this->ItemsPagina < 0)
            $this->ItemsPagina = 0;
    }

    /**
     * Devuelve un array con los id's de reglas aplicables al artículo
     * 
     * @param int $idArticulo El id del articulo
     * @return array Array de reglas aplicables
     */
    public function getReglasArticulo($idArticulo) {

        $articulo = new Articulos($idArticulo);

        $array = array();

        $idEstado1 = $articulo->getIDEstado1()->getIDEstado();
        $idEstado2 = $articulo->getIDEstado2()->getIDEstado();
        $idEstado3 = $articulo->getIDEstado3()->getIDEstado();
        $idEstado4 = $articulo->getIDEstado4()->getIDEstado();
        $idEstado5 = $articulo->getIDEstado5()->getIDEstado();

        $idFabricante = $articulo->getIDFabricante()->getIDFabricante();
        $idCategoria = $articulo->getIDCategoria()->getIDFamilia();
        $idFamilia = $articulo->getIDFamilia()->getIDFamilia();
        $idSubfamilia = $articulo->getIDSubfamilia()->getIDFamilia();
        unset($articulo);

        $filtroLote = "IDLote='0'";
        $lotes = new LotesWeb();
        foreach($lotes->getArrayLotesArticulo($idArticulo) as $key=>$lote) {
            $filtroLote .= " OR IDLote='{$lote}'";
        }
        $filtroLote = "(" . $filtroLote . ")";
        
        $filtroEstado = "IDEstado='0'";
        if ($idEstado1 > 0)
            $filtroEstado .= " OR IDEstado='{$idEstado1}'";
        if ($idEstado2 > 0)
            $filtroEstado .= " OR IDEstado='{$idEstado2}'";
        if ($idEstado3 > 0)
            $filtroEstado .= " OR IDEstado='{$idEstado3}'";
        if ($idEstado4 > 0)
            $filtroEstado .= " OR IDEstado='{$idEstado4}'";
        if ($idEstado5 > 0)
            $filtroEstado .= " OR IDEstado='{$idEstado5}'";

        $filtroFabricante = "IDFabricante='0'";
        if ($idFabricante > 0)
            $filtroFabricante .= " OR IDFabricante='{$idFabricante}'";

        $filtroCategoria = "IDCategoria='0'";
        if ($idCategoria > 0)
            $filtroCategoria .= " OR IDCategoria='{$idCategoria}'";

        $filtroFamilia = "IDFamilia='0'";
        if ($idFamilia > 0)
            $filtroFamilia .= " OR IDFamilia='{$idFamilia}'";

        $filtroSubfamilia = "IDSubfamilia='0'";
        if ($idSubfamilia > 0)
            $filtroSubfamilia .= " OR IDSubfamilia='{$idSubfamilia}'";

        $filtro = "({$filtroLote}) AND ({$filtroEstado}) AND ({$filtroFabricante}) AND ({$filtroCategoria}) AND ({$filtroFamilia}) AND ({$filtroSubfamilia})";
        //echo $filtro;
        $reglas = $this->cargaCondicion("Id", $filtro);
        foreach ($reglas as $regla) {
            $array[] = $regla['Id'];
        }
        return $array;
    }

    /**
     * Genera los ordenes del articulo $idArticulo en base
     * a todas las reglas aplicables al mismo
     * 
     * @param int $idArticulo El id del artículo
     * @return void
     */
    public function aplicaReglasArticulo($idArticulo) {

        $articulo = new Articulos($idArticulo);

        $reglas = $this->getReglasArticulo($idArticulo);

        foreach ($reglas as $regla) {
            $orden = new OrdenesArticulos();
            $filtro = "IDRegla='{$regla}' AND IDArticulo='{$articulo->getIDArticulo()}'";
            $rows = $orden->cargaCondicion("Id", $filtro);
            if (!$rows[0]["Id"]) {
                $orden->setIDRegla($regla);
                $orden->setIDArticulo($articulo->getIDArticulo());
                $orden->setObservations($articulo->getDescripcion());
                $orden->create();
            }
        }
        unset($articulo);
    }

    /**
     * Aplica la regla $idRegla, que consiste
     * en crear las entradas en la tabla ErpOrdenesArticulos de los articulos
     * que cumplan las condiciones de la regla
     * 
     * La regla no se aplicará a los artículos que no estén vigentes
     * La regla se aplicará a los artículos publish si y publish no
     * 
     * Si no se indica $idRegla, se aplicará la regla en curso
     * 
     * @param int $idRegla El id de la regla a aplicar
     * @return void
     */
    public function aplicaRegla($idRegla = '') {

        $regla = ($idRegla == '') ? $this : new Escaparates($idRegla);

        if ($regla->IDLote > 0) {
            $lote = new LotesWeb($regla->IDLote);

            foreach($lote->getArrayObjetosArticulos() as $articulo) {
                $filtroLote .= "IDArticulo='{$articulo->getPrimaryKeyValue()}' OR ";
            }
            $filtroLote = "(" . substr($filtroLote, 0,-4) . ")";
        } else {
            $filtroLote = "(1)";
        }
        $filtroEstado = ($regla->IDEstado > 0) ?
                "(IDEstado1='{$regla->IDEstado}' OR IDEstado2='{$regla->IDEstado}' OR IDEstado3='{$regla->IDEstado}'  OR IDEstado4='{$regla->IDEstado}'  OR IDEstado5='{$regla->IDEstado}')" :
                "(1)";
        $filtroMarca = ($regla->IDFabricante > 0) ?
                "(IDFabricante='{$regla->IDFabricante}')" :
                "(1)";
        $filtroCategoria = ($regla->IDCategoria > 0) ?
                "(IDCategoria='{$regla->IDCategoria}')" :
                "(1)";
        $filtroFamilia = ($regla->IDFamilia > 0) ?
                "(IDFamilia='{$regla->IDFamilia}')" :
                "(1)";
        $filtroSubfamilia = ($regla->IDSubfamilia > 0) ?
                "(IDSubfamilia='{$regla->IDSubfamilia}')" :
                "(1)";
        $filtroAdicional = ($regla->Filtro != '') ?
                "({$filtroAdicional})" :
                "(1)";

        $filtro = "(Vigente='1') AND {$filtroLote} AND {$filtroEstado} AND {$filtroMarca} AND {$filtroCategoria} AND {$filtroFamilia} AND {$filtroSubfamilia} AND {$filtroAdicional}";
        //echo $filtro;
        $articulo = new Articulos();
        $rows = $articulo->cargaCondicion("IDArticulo,Descripcion", $filtro);
        unset($articulo);

        foreach ($rows as $row) {
            $orden = new OrdenesArticulos();
            $orden->setIdRegla($regla->Id);
            $orden->setIDArticulo($row["IDArticulo"]);
            $orden->setObservations($row['Descripcion']);
            $orden->create();
        }
        unset($orden);
    }

}