<?php

/**
 * Description of Familias
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class Familias extends FamiliasEntity {

    public function __toString() {
        return $this->getFamilia();
    }

    public function fetchAll($column = '', $default = true, $perteneceA = 0, $nivel = 1) {
        if ($column == '')
            $column = "Familia";

        $filtro = ($perteneceA < 0) ? " and (1)" : " and (BelongsTo='{$perteneceA}')";
        $filtro .= " and NivelJerarquico='{$nivel}'";

        $this->conecta();

        if (is_resource($this->_dbLink)) {
            $query = "SELECT " . $this->getPrimaryKeyName() . " as Id, $column as Value FROM `{$this->_dataBaseName}`.`{$this->_tableName}` WHERE (Deleted = '0') {$filtro} ORDER BY $column ASC";
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
            $this->setStatus($this->_em->numRows());
            $this->_em->desConecta();
            unset($this->_em);
        }

        if ($default == TRUE) {
            $rows[] = array('Id' => '', 'Value' => ':: Indique un Valor');
            sort($rows);
        }

        return $rows;
    }

    /**
     * Devuelve las categorias (las familias de nivel 1)
     * @param boolean $default
     * @return array
     */
    public function getCategorias($default = true) {
        return $this->fetchAll('Familia', $default);
    }

    /**
     * Devuelve un array (Id,Value) de las familias
     * que se muestran en el TPV y que pertenecen al padre $idPadre
     * 
     * @param integer $idPadre El id de la familia padre. Por defecto 0 (las categorías)
     * @return array
     */
    public function getFamiliasTpv($idPadre = 0) {
        return $this->cargaCondicion("IDFamilia as Id,Familia as Value", "BelongsTo='{$idPadre}' and MostrarEnTpv='1'", "OrdenTpv ASC");
    }

    /**
     * Devuelve las familias (las familias de nivel 2)
     * @param boolean $default
     * @param integer $perteneceA El id de la categoria. Negativo para todas las familias
     * @return array
     */
    public function getFamilias($default = true, $perteneceA = -1) {
        return $this->fetchAll('Familia', $default, $perteneceA, 2);
    }

    /**
     * Devuelve las subfamilias (las familias de nivel 3)
     * @param boolean $default
     * @param integer $perteneceA El id de la familia. Negativo para todas las subfamilias
     * @return array
     */
    public function getSubfamilias($default = true, $perteneceA = -1) {
        return $this->fetchAll('Familia', $default, $perteneceA, 3);
    }

    /**
     * Guardo la familia y actualizo sus articulos
     * haciendo que hereden ciertas características.
     * 
     * @return boolean
     */
    public function save() {

        $ok = parent::save();

        if ($ok) {
            $this->conecta();

            $articulo = new Articulos();
            $dbName = $articulo->getDataBaseName();
            $tableName = $articulo->getTableName();
            unset($articulo);

            if (is_resource($this->_dbLink)) {
                // Actualizar articulos
                $query = "UPDATE {$dbName}.{$tableName} set
                            Inventario='{$this->Inventario}',
                            Trazabilidad='{$this->Trazabilidad}',
                            Publish='{$this->Publish}',
                            BajoPedido='{$this->BajoPedido}',
                            BloqueoStock='{$this->BloqueoStock}',
                            ActiveFrom = CASE
                              WHEN ActiveFrom<'{$this->ActiveFrom}' THEN '{$this->ActiveFrom}' ELSE ActiveFrom
                            END,
                            ActiveTo = CASE
                              WHEN ActiveTo>'{$this->ActiveTo}' THEN '{$this->ActiveTo}' ELSE ActiveTo
                            END
                            where (IDCategoria='{$this->IDFamilia}') 
                                or (IDFamilia='{$this->IDFamilia}') 
                                or (IDSubfamilia='{$this->IDFamilia}');";
                $this->_em->query($query);

                // Actualizar familias y subfamilias
                $filtro = "BelongsTo='{$this->IDFamilia}'";
                $this->queryUpdate(array("MostrarPortada" => $this->MostrarPortada, "MostrarEnTpv" => $this->MostrarEnTpv), $filtro);

                //$this->_em->desConecta();
            }
            //unset($this->_em);
        }

        return $ok;
    }

    /**
     * Devuelve un array con los articulos correspondientes
     * a la familia indicada, o en su defecto a la familia actual.
     *
     * Si se indica $entidadRelacionada e $idEntidadRelacionada, se añade un elmento más que indica
     * si cada articulo está relacionado con $entidadRelacionada e $idEntidadRelacionada
     *
     * El array tiene los siguientes elementos:
     * 
     * - Id: El id del articulo
     * - Value: La descripcion del articulo
     * - Pvp: Precio de venta SIN impuestos
     * - PvpConImpuestos: Precio de venta CON impuestos
     * - PrimaryKeyMD5: la primarykey MD5
     * - Publish: TRUE/FALSE
     * - estaRelacionado: El id de la eventual relacion
     * 
     * @param integer $idFamilia El id de la familia
     * @param array $optArticulos Array de opciones de articulos
     * @param string $idEntidadRelacionada La entidad con la que existe una posible relación
     * @param integer $idEntidadRelacionada El id de entidad con la que existe una posible relación
     * @return array Array Id, Value de articulos
     */
    public function getArticulos($idFamilia = '', $optArticulos = array(), $entidadRelacionada = '', $idEntidadRelacionada = '') {

        if ($idFamilia == '') {
            $idFamilia = $this->IDFamilia;
        }

        $articulos = array();

        $em = new EntityManager($_SESSION['project']['conection']);
        if ($em->getDbLink()) {
            $select = "select a.IDArticulo as Id,a.Descripcion as Value,a.Pvp,a.Pvp*(1+i.Iva/100) as PvpConImpuestos,i.Iva,a.PrimaryKeyMD5,a.Publish from ErpArticulos a left join ErpTiposIva i on a.IDIva=i.IDIva";
            $where = "a.IDCategoria='{$idFamilia}' or a.IDFamilia='{$idFamilia}' or a.IDSubfamilia='{$idFamilia}'";
            $articulos = $em->getResult("a", $select, $where);

            if ($entidadRelacionada) {
                foreach ($articulos as $key => $articulo) {
                    $relacion = new CpanRelaciones();
                    $articulos[$key]['estaRelacionado'] = $relacion->getIdRelacion('ErpArticulos', $articulo['Id'], $entidadRelacionada, $idEntidadRelacionada);
                }
                unset($relacion);
            }

            if ($optArticulos['conImagenes']) {

                foreach ($articulos as $key => $item) {
                    $articulo = new Articulos($item['Id']);
                    $objetosImagen = $articulo->getDocuments();
                    if (count($objetosImagen)) {
                        foreach ($objetosImagen as $imagen) {
                            $articulos[$key]['imagenes'][] = $imagen->getPathName();
                        }
                    } else {
                        $articulos[$key]['imagenes'] = array();
                    }
                }
                unset($articulo);
            }
        }

        return $articulos;
    }

    /**
     * Devuelve un array con el árbol de N jerarquías de categorias, familias, subfamilias, etc.
     * 
     * Si se indican los parámetros $entidadRelacionada e $idEntidadRelacionada, en el array
     * 'estaRelacionado' se incluirá un elemento booleano que indica si cada elemento
     * está relacionado con el contenido cuyo valor es el parámetro.
     * 
     * El índice del array contiene el valor de la primaryKeyMD5 de cada sección y la estructura es:
     * 
     * - id => el id de la seccion
     * - titulo => el titulo de la seccion
     * - nivelJerarquico => el nivel jerárquico dentro del árbol de secciones
     * - publish => Publicar TRUE/FALSE
     * - belongsTo => El id del padre al que pertenece
     * - nHijos => El número de secciones hijas
     * - hijos => array de secciones hijas (belongsTo) con la misma estructura
     * - nArticulos => el número de artículos que posee la familia
     * - articulos => array de artículos de la familia
     * 
     * @param array $optArticulos. Opciones de mostrar articulos
     * @param string $entidadRelacionada. Por defecto nada
     * @param integer $idEntidadRelacionada. Por defecto nada
     * @param string $aditionalFilter Filtro adicional. Por defecto ninguno
     * @param string $sortOrder Criterio de orden. Por defecto SortOrder ASC 
     * @return array Array de familias y articulos
     */
    public function getArbolHijos($optArticulos = array('conArticulos' => false), $entidadRelacionada = '', $idEntidadRelacionada = '', $aditionalFilter = '1', $sortOrder = 'SortOrder ASC') {

        $arbol = array();

        $objeto = new $this();
        $rows = $objeto->cargaCondicion("IDFamilia as Id,PrimaryKeyMD5,NivelJerarquico,Publish,BelongsTo", "(BelongsTo='{$this->BelongsTo}') and ({$aditionalFilter})", $sortOrder);
        unset($objeto);

        foreach ($rows as $row) {
            $objeto = new $this($row['Id']);
            $arrayArticulos = ($optArticulos["conArticulos"]) ? $this->getArticulos($row['Id'], $optArticulos, $entidadRelacionada, $idEntidadRelacionada) : array();
            $arrayHijos = $objeto->getHijos('', $optArticulos, $entidadRelacionada, $idEntidadRelacionada, $aditionalFilter, $sortOrder);

            $arbol[$row['PrimaryKeyMD5']] = array(
                'id' => $row['Id'],
                'titulo' => $objeto->__toString(),
                'nivelJerarquico' => $row['NivelJerarquico'],
                'publish' => $row['Publish'],
                'belongsTo' => $row['BelongsTo'],
                'nHijos' => count($arrayHijos),
                'hijos' => $arrayHijos,
                'nArticulos' => count($arrayArticulos),
                'articulos' => $arrayArticulos,
            );
            if ($entidadRelacionada) {
                $relacion = new CpanRelaciones();
                $arbol[$row['PrimaryKeyMD5']]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada, 'Familias', $row['Id']);
            }
        }

        unset($objeto);
        return $arbol;
    }

    /**
     * Genera el árbol genealógico con las entidades hijas de la
     * entidad $idPadre.
     * 
     * El árbol se genera de forma recursiva sin límite de profundidad.
     * 
     * El array lleva valor únicamente en el índice, y dicho valor es el
     * id de las entidades.
     * 
     * @param integer $idPadre El id de la entidad padre
     * @return array
     */
    public function getHijos($idPadre = '', $optArticulos = array('conArticulos' => false), $entidadRelacionada = '', $idEntidadRelacionada = '', $aditionalFilter = '1', $sortOrder = "SortOrder ASC") {

        if ($idPadre == '') {
            $idPadre = $this->getPrimaryKeyValue();
        }

        $this->getChildrens($idPadre, $optArticulos, $entidadRelacionada, $idEntidadRelacionada, $aditionalFilter, $sortOrder);
        return $this->_hijos[$idPadre];
    }

    /**
     * Generar un árbol genealógico con las entidades hijas
     * de la entidad cuyo id es $idPadre
     *
     * @param integer $idPadre El id de la entidad padre
     * @param array $optArticulos Array de opciones de articulos
     * @return array Array con los objetos hijos
     */
    private function getChildrens($idPadre, $optArticulos, $entidadRelacionada, $idEntidadRelacionada, $aditionalFilter = '1', $sortOrder = "SortOrder ASC") {

        // Obtener todos los hijos del padre actual
        $hijos = $this->cargaCondicion('IDFamilia as Id,PrimaryKeyMD5,NivelJerarquico,Publish,BelongsTo', "(BelongsTo='{$idPadre}') and ({$aditionalFilter})", $sortOrder);

        foreach ($hijos as $hijo) {
            $aux = new $this($hijo['Id']);
            $arrayArticulos = ($optArticulos["conArticulos"]) ? $this->getArticulos($hijo['Id'], $optArticulos, $entidadRelacionada, $idEntidadRelacionada) : array();
            $arrayHijos = $this->getChildrens($hijo['Id'], $optArticulos, $entidadRelacionada, $idEntidadRelacionada);
            $this->_hijos[$idPadre][$hijo['PrimaryKeyMD5']] = array(
                'id' => $hijo['Id'],
                'titulo' => $aux->__toString(),
                'nivelJerarquico' => $hijo['NivelJerarquico'],
                'publish' => $hijo['Publish'],
                'belongsTo' => $hijo['BelongsTo'],
                'nHijos' => count($arrayHijos),
                'hijos' => $arrayHijos,
                'nArticulos' => count($arrayArticulos),
                'articulos' => $arrayArticulos,
            );
            if ($entidadRelacionada) {
                $relacion = new CpanRelaciones();
                $this->_hijos[$idPadre][$hijo['PrimaryKeyMD5']]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada, 'Familias', $hijo['Id']);
            }
            unset($hijo);
        }

        return $this->_hijos[$idPadre];
    }

    /**
     * Devuelve un array con las propiedades y valores asignadas
     * a la familia en curso.
     * 
     * Si el parámetro $todas es false se incluyen todas las propiedades pero
     * con una marca a false para las propiedades que no están asignadas.
     * 
     * Si el parámetro $todas es true se incluyen solo las propiedades asignadas
     * y con la marca a true
     * 
     * El indice el array es el id de la propiedad y tiene dos elementos:
     * 
     * - Titulo: el titulo de la propiedad
     * - Asignada: true o false
     * - Filtrable: true o false
     * - Valores: array de valores (Id,Value)
     * 
     * @param boolean $todas
     * @return array
     */
    public function getPropiedades($todas = false) {

        $propiedades = array();

        $propiedad = new Propiedades();
        // Cojo todas las propiedades
        $aux = $propiedad->fetchAll('Titulo', false);
        foreach ($aux as $item)
            $propiedadesTodas[$item['Id']] = $item['Value'];

        // Cojo las que están asignadas a la familia
        $familiaPropiedad = new FamiliasPropiedades();
        $rows = $familiaPropiedad->cargaCondicion("IDPropiedad, Filtrable", "IDFamilia='{$this->IDFamilia}'");

        $valores = new PropiedadesValores();
        foreach ($rows as $row)
            $propiedades[$row['IDPropiedad']] = array(
                'Id' => $row['IDPropiedad'],
                'Titulo' => $propiedadesTodas[$row['IDPropiedad']],
                'Asignada' => true,
                'Filtrable' => $row['Filtrable'],
                'Valores' => $valores->getValores($row['IDPropiedad']),
            );


        if ($todas)
            foreach ($propiedadesTodas as $key => $titulo)
                if (!isset($propiedades[$key]))
                    $propiedades[$key] = array(
                        'Id' => $key,
                        'Titulo' => $propiedadesTodas[$key],
                        'Asignada' => false,
                        'Valores' => $valores->getValores($key),
                    );

        return $propiedades;
    }

    /**
     * Devuelve true o false según la familia tenga o no propiedades asociadas
     * 
     * @return boolean True si la familia tiene propiedades
     */
    public function TienePropiedades() {

        $propiedad = new FamiliasPropiedades();
        $rows = $propiedad->cargaCondicion("Id", "IDFamilia='{$this->getPrimaryKeyValue()}'");
        unset($propiedad);

        return(count($rows) > 0);
    }

    /**
     * Devuelve el número de artículos de la categoria/familia/subfamilia en curso
     * 
     * No tiene en cuenta los no vigentes
     * 
     * @return integer El número de artículos
     */
    public function getNArticulos() {

        switch ($this->NivelJerarquico) {
            case 1:
                $campo = "IDCategoria";
                break;
            case 2:
                $campo = "IDFamilia";
                break;
            case 3:
                $campo = "IDSubfamilia";
                break;
        }

        $articulo = new Articulos();
        $rows = $articulo->cargaCondicion("count(IDArticulo) as nArticulos", "{$campo}='{$this->IDFamilia}' and Vigente='1'");
        unset($articulo);

        return $rows[0]['nArticulos'];
    }

    /**
     * Devuelve un array de objetos fabricantes que están relacionados
     * con la categoría/famila/subfamilia en curso
     * 
     * @return \Fabricantes
     */
    public function getFabricantes() {

        switch ($this->NivelJerarquico) {
            case 1:
                $campo = "IDCategoria";
                break;
            case 2:
                $campo = "IDFamilia";
                break;
            case 3:
                $campo = "IDSubfamilia";
                break;
        }

        $array = array();

        $articulo = new Articulos();
        $rows = $articulo->cargaCondicion("distinct IDFabricante", "{$campo}='{$this->IDFamilia}' and Vigente='1'");
        unset($articulo);

        foreach ($rows as $row)
            $array[] = new Fabricantes($row['IDFabricante']);

        return $array;
    }

}

?>
