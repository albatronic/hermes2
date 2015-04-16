<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.02.2013 19:23:25
 */

/**
 * @orm:Entity(ServFamilias)
 */
class ServFamilias extends ServFamiliasEntity {

    public function __toString() {
        return $this->Titulo;
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Titulo';
        return parent::fetchAll($column, $default);
    }

    /**
     * Devuelve un array con los servicios correspondientes
     * a la familia indicada, o en su defecto a la familia actual.
     *
     * Si se indica $entidadRelacionada e $idEntidadRelacionada, se añade un elmento más que indica
     * si cada articulo está relacionado con $entidadRelacionada e $idEntidadRelacionada
     *
     * El array tiene los siguientes elementos:
     * 
     * - Id: El id del servicio
     * - Value: La descripcion del servicio
     * - PrimaryKeyMD5: la primarykey MD5
     * - Publish: TRUE/FALSE
     * - estaRelacionado: El id de la eventual relacion
     * 
     * @param integer $idFamilia El id de la familia
     * @param string $idEntidadRelacionada La entidad con la que existe una posible relación
     * @param integer $idEntidadRelacionada El id de entidad con la que existe una posible relación
     * @return array Array Id, Value de articulos
     */
    public function getServicios($idFamilia = '', $entidadRelacionada = '', $idEntidadRelacionada = '') {

        if ($idFamilia == '')
            $idFamilia = $this->Id;

        $servicio = new ServServicios();
        $servicios = $servicio->cargaCondicion('Id as Id,Titulo as Value,PrimaryKeyMD5,Publish', "IdFamilia='{$idFamilia}'", "SortOrder ASC");
        unset($servicio);

        if ($entidadRelacionada) {
            foreach ($servicios as $key => $servicio) {
                $relacion = new CpanRelaciones();
                $servicios[$key]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada,'ServServicios', $servicio['Id']);
            }
            unset($relacion);
        }
        return $servicios;
    }

    /**
     * Devuelve un array con el árbol de familias y servicios
     * 
     * Si se indica valor para el parámetro $idContenidoRelacionado, en el array
     * de contenidos se incluirá un elemento booleano que indica si cada servicio
     * está relacionado con el servicio cuyo valor es el parámetro.
     * 
     * El índice del array contiene el valor de la primaryKeyMD5 de cada familia y la estructura es:
     * 
     * - id => el id de la familia
     * - titulo => el titulo de la familia
     * - nivelJerarquico => el nivel jerárquico dentro del árbol de familias
     * - publish => Publicar TRUE/FALSE
     * - belongsTo => El id del padre al que pertenece
     * - nHijos => El número de familias hijas
     * - hijos => array de familias hijas (belongsTo) con la misma estructura
     * - nServicios => el número de servicios que posee la familia
     * - servicios => array de servicios de la familia
     * 
     * @param boolean $conServicios
     * @param string $entidadRelacionada
     * @param integer $idEntidadRelacionada 
     * @return array Array de familias y servicios
     */
    public function getArbolHijos($conServicios = false, $entidadRelacionada = '', $idEntidadRelacionada = '') {

        $arbol = array();

        $objeto = new $this();
        $rows = $objeto->cargaCondicion("Id,PrimaryKeyMD5,NivelJerarquico,Publish,BelongsTo", "BelongsTo='0'", "SortOrder ASC");
        unset($objeto);

        foreach ($rows as $row) {
            $objeto = new $this($row['Id']);
            $arrayServicios = ($conServicios) ? $this->getServicios($row['Id'], $entidadRelacionada, $idEntidadRelacionada) : array();
            $arrayHijos = $objeto->getHijos('',$conServicios, $entidadRelacionada, $idEntidadRelacionada);

            $arbol[$row['PrimaryKeyMD5']] = array(
                'id' => $row['Id'],
                'titulo' => $objeto->getTitulo(),
                'nivelJerarquico' => $row['NivelJerarquico'],
                'publish' => $row['Publish'],
                'belongsTo' => $row['BelongsTo'],                
                'nHijos' => count($arrayHijos),
                'hijos' => $arrayHijos,
                'nServicios' => count($arrayServicios),
                'servicios' => $arrayServicios,
            );
            if ($entidadRelacionada) {
                $relacion = new CpanRelaciones(); 
                $arbol[$row['PrimaryKeyMD5']]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada,'ServFamilia', $row['Id']);
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
    public function getHijos($idPadre = '', $conServicios = FALSE, $entidadRelacionada = '', $idEntidadRelacionada = '') {

        if ($idPadre == '')
            $idPadre = $this->getPrimaryKeyValue();

        $this->getChildrens($idPadre, $conServicios, $entidadRelacionada, $idEntidadRelacionada);
        return $this->_hijos[$idPadre];
    }

    /**
     * Generar un árbol genealógico con las entidades hijas
     * de la entidad cuyo id es $idPadre
     *
     * @param integer $idPadre El id de la entidad padre
     * @return array Array con los objetos hijos
     */
    private function getChildrens($idPadre, $conServicios, $entidadRelacionada, $idEntidadRelacionada) {

        // Obtener todos los hijos del padre actual
        $hijos = $this->cargaCondicion('Id,PrimaryKeyMD5,NivelJerarquico,Publish,BelongsTo', "BelongsTo='{$idPadre}'", "SortOrder ASC");

        foreach ($hijos as $hijo) {
            $aux = new $this($hijo['Id']);
            $arrayServicios = ($conServicios) ? $this->getServicios($hijo['Id']) : array();
            $arrayHijos = $this->getChildrens($hijo['Id'], $conServicios, $entidadRelacionada, $idEntidadRelacionada);
            $this->_hijos[$idPadre][$hijo['PrimaryKeyMD5']] = array(
                'id' => $hijo['Id'],
                'titulo' => $aux->getTitulo(),
                'nivelJerarquico' => $hijo['NivelJerarquico'],
                'publish' => $hijo['Publish'],
                'belongsTo' => $hijo['BelongsTo'],                
                'nHijos' => count($arrayHijos),
                'hijos' => $arrayHijos,
                'nServicios' => count($arrayServicios),
                'servicios' => $arrayServicios,
            );
            if ($entidadRelacionada) {
                $relacion = new CpanRelaciones(); 
                $this->_hijos[$idPadre][$hijo['PrimaryKeyMD5']]['estaRelacionado'] = $relacion->getIdRelacion($entidadRelacionada, $idEntidadRelacionada,'ServFamilias', $hijo['Id']);
            }              
            unset($hijo);
        }

        return $this->_hijos[$idPadre];
    }
    
}

?>