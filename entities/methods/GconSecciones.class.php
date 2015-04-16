<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 29.10.2012 20:07:59
 */

/**
 * @orm:Entity(GconSecciones)
 */
class GconSecciones extends GconSeccionesEntity {

    public function __toString() {
        return $this->getTitulo();
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Titulo';
        return parent::fetchAll($column, $default);
    }

    public function validaLogico() {

        parent::validaLogico();

        if (count($this->_errores) == 0) {
            for ($i = 1; $i <= 5; $i++) {
                // Poner las etiquetas web
                if ($this->{"EtiquetaWeb$i"} == '')
                    $this->{"setEtiquetaWeb$i"}($this->Titulo);
                // Poner las subetiquetas web                    
                if ($this->{"SubetiquetaWeb$i"} == '')
                    $this->{"setSubetiquetaWeb$i"}($this->Titulo);
                // Poner el orden de los menus                    
                if ($this->{"OrdenMenu$i"} == 0)
                    $this->{"setOrdenMenu$i"}($this->SortOrder);
            }         
        }
    }

    /**
     * Devuelve un array con los contenidos correspondientes
     * a la sección indicada, o en su defecto a la sección actual.
     *
     * Si se indica $entidadRelacionada e $idEntidadRelacionada, se añade un elmento más que indica
     * si cada contenido está relacionado con $entidadRelacionada e $idEntidadRelacionada
     *
     * El array tiene los siguientes elementos:
     * 
     * - Id: El id del contenido
     * - Value: El titulo del contenido
     * - PrimaryKeyMD5: la primarykey MD5
     * - Publish: TRUE/FALSE
     * - estaRelacionado: El id de la eventual relacion
     * 
     * @param integer $idSeccion El id de la seccion
     * @param string $idEntidadRelacionada La entidad con la que existe una posible relación
     * @param integer $idEntidadRelacionada El id de entidad con la que existe una posible relación
     * @return array Array Id, Value de contenidos
     */
    public function getContenidos($idSeccion = '', $entidadRelacionada = '', $idEntidadRelacionada = '') {

        if ($idSeccion == '')
            $idSeccion = $this->Id;

        $contenido = new GconContenidos();
        $contenidos = $contenido->cargaCondicion('Id as Id,Titulo as Value,PrimaryKeyMD5,Publish', "IdSeccion='{$idSeccion}'", "SortOrder ASC");
        unset($contenido);

        if ($entidadRelacionada) {
            foreach ($contenidos as $key => $contenido) {
                $relacion = new CpanRelaciones();
                $contenidos[$key]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada, 'GconContenidos', $contenido['Id']);               
            }
            unset($relacion);
        }
        return $contenidos;
    }

    /**
     * Devuelve un array con el árbol de secciones y contenidos
     * 
     * Si se indica valor para el parámetro $idContenidoRelacionado, en el array
     * de contenidos se incluirá un elemento booleano que indica si cada contenido
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
     * - nContenidos => el número de contenidos que posee la sección
     * - contenidos => array de contenidos de la seccion
     * 
     * @param boolean $conContenidos
     * @param string $entidadRelacionada
     * @param integer $idEntidadRelacionada 
     * @return array Array de secciones
     */
    public function getArbolHijos($conContenidos = FALSE, $entidadRelacionada = '', $idEntidadRelacionada = '') {

        $arbol = array();

        $objeto = new $this();
        $rows = $objeto->cargaCondicion("Id,PrimaryKeyMD5,NivelJerarquico,Publish,BelongsTo", "BelongsTo='0'", "SortOrder ASC");
        unset($objeto);

        foreach ($rows as $row) {
            $objeto = new $this($row['Id']);
            $arrayContenidos = ($conContenidos) ? $this->getContenidos($row['Id'], $entidadRelacionada, $idEntidadRelacionada) : array();
            $arrayHijos = $objeto->getHijos('', $conContenidos, $entidadRelacionada, $idEntidadRelacionada);
            $arbol[$row['PrimaryKeyMD5']] = array(
                'id' => $row['Id'],
                'titulo' => $objeto->getTitulo(),
                'nivelJerarquico' => $row['NivelJerarquico'],
                'publish' => $row['Publish'],
                'belongsTo' => $row['BelongsTo'],
                'nHijos' => count($arrayHijos),
                'hijos' => $arrayHijos,
                'nContenidos' => count($arrayContenidos),
                'contenidos' => $arrayContenidos,
            );
            if ($entidadRelacionada) {
                $relacion = new CpanRelaciones();
                $arbol[$row['PrimaryKeyMD5']]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada, 'GconSecciones', $row['Id']);
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
    public function getHijos($idPadre = '', $conContenidos = FALSE, $entidadRelacionada = '', $idEntidadRelacionada = '') {

        if ($idPadre == '')
            $idPadre = $this->getPrimaryKeyValue();

        $this->getChildrens($idPadre, $conContenidos, $entidadRelacionada, $idEntidadRelacionada);
        return $this->_hijos[$idPadre];
    }

    /**
     * Generar un árbol genealógico con las entidades hijas
     * de la entidad cuyo id es $idPadre
     *
     * @param integer $idPadre El id de la entidad padre
     * @return array Array con los objetos hijos
     */
    private function getChildrens($idPadre, $conContenidos, $entidadRelacionada, $idEntidadRelacionada) {

        // Obtener todos los hijos del padre actual
        $hijos = $this->cargaCondicion('Id,PrimaryKeyMD5,NivelJerarquico,Publish,BelongsTo', "BelongsTo='{$idPadre}'", "SortOrder ASC");

        foreach ($hijos as $hijo) {
            $aux = new $this($hijo['Id']);
            $arrayContenidos = ($conContenidos) ? $this->getContenidos($hijo['Id'],$entidadRelacionada, $idEntidadRelacionada) : array();
            $arrayHijos = $this->getChildrens($hijo['Id'], $conContenidos, $entidadRelacionada, $idEntidadRelacionada);
            $this->_hijos[$idPadre][$hijo['PrimaryKeyMD5']] = array(
                'id' => $hijo['Id'],
                'titulo' => $aux->getTitulo(),
                'nivelJerarquico' => $hijo['NivelJerarquico'],
                'publish' => $hijo['Publish'],
                'belongsTo' => $hijo['BelongsTo'],
                'nHijos' => count($arrayHijos),
                'hijos' => $arrayHijos,
                'nContenidos' => count($arrayContenidos),
                'contenidos' => $arrayContenidos,
            );
            if ($entidadRelacionada) {
                $relacion = new CpanRelaciones();
                $this->_hijos[$idPadre][$hijo['PrimaryKeyMD5']]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada, 'GconSecciones', $hijo['Id']);
            }
            unset($hijo);
        }

        return $this->_hijos[$idPadre];
    }

    /**
     * ESTOS MÉTODOS SON PARA TWIG
     */
    public function getMostrarEnMenuN($n) {
        return $this->{"MostrarEnMenu$n"};
    }

    public function getEtiquetaWebN($n) {
        return $this->{"EtiquetaWeb$n"};
    }

    public function getSubetiquetaWebN($n) {
        return $this->{"SubetiquetaWeb$n"};
    }

}

?>