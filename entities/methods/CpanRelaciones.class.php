<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 01.02.2013 20:16:29
 */

/**
 * @orm:Entity(CpanRelaciones)
 */
class CpanRelaciones extends CpanRelacionesEntity {

    public function __toString() {
        return $this->getId();
    }

    /**
     * Devuelve el id de la eventual relación entre una entidad-idEntidad origen y 
     * otra entidad-idEntidad destino
     * 
     * @param string $entidadOrigen
     * @param integer $idOrigen
     * @param string $entidadDestino
     * @param integer $idDestino
     * @return integer
     */
    public function getIdRelacion($entidadOrigen, $idOrigen, $entidadDestino, $idDestino) {

        $filtro = "EntidadOrigen='{$entidadOrigen}' AND IdEntidadOrigen='{$idOrigen}' AND EntidadDestino='{$entidadDestino}' AND IdEntidadDestino='{$idDestino}'";

        $row = $this->cargaCondicion('Id', $filtro);

        return ($row[0]['Id']);
    }

    /**
     * Borrar todas las relaciones existentes con la entidad e
     * id de entidad origen
     * 
     * @param integer $entidadOrigen El nombre la entidad origen
     * @param integer $idOrigen El id de la entidad origen
     */
    public function eraseRelaciones($entidadOrigen, $idOrigen) {

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "delete from {$this->getDataBaseName()}.{$this->getTableName()} WHERE EntidadOrigen='{$entidadOrigen}' AND IdEntidadOrigen='{$idOrigen}'";
            $em->query($query);
            $em->desConecta();
        }
        unset($em);
    }

    /**
     * Devuelve un array con objetos relacionados
     * 
     * @param string $entidadOrigen El nombre de la entidad origen
     * @param integer $idOrigen El id de la entidad origen
     * @param string $entidadDestino El nombre de la entidad destino. Opcional. Por defecto todas
     * @return array Array de objetos relacionados
     */
    public function getObjetosRelacionados($entidadOrigen, $idOrigen, $entidadDestino = '') {

        $array = array();

        $filtroDestino = ($entidadDestino != '') ? "AND EntidadDestino='{$entidadDestino}'" : "";

        $relaciones = $this->cargaCondicion("EntidadDestino,IdEntidadDestino", "EntidadOrigen='{$entidadOrigen}' AND IdEntidadOrigen='{$idOrigen}' {$filtroDestino}", "EntidadDestino,SortOrder ASC");

        foreach ($relaciones as $relacion) {
            $array[] = new $relacion['EntidadDestino']($relacion['IdEntidadDestino']);
        }

        return $array;
    }

    /**
     * Devuelve un array con objetos relacionados
     * 
     * @param string $entidadDestino El nombre de la entidad destino
     * @param integer $idDestino El id de la entidad destino
     * @param string $entidadOrigen El nombre de la entidad origen. Opcional. Por defecto todas
     * @return array Array de objetos relacionados
     */
    public function getObjetosRelacionadosInverso($entidadDestino, $idDestino, $entidadOrigen = '') {

        $array = array();

        if ($entidadOrigen != '')
            $filtroOrigen = "AND EntidadOrigen='{$entidadOrigen}'";

        $relaciones = $this->cargaCondicion("EntidadOrigen,IdEntidadOrigen,CreatedAt", "EntidadDestino='{$entidadDestino}' AND IdEntidadDestino='{$idDestino}' {$filtroOrigen}", "EntidadOrigen,SortOrder ASC");

        foreach ($relaciones as $relacion)
            $array[] = array(
                'objeto' => new $relacion['EntidadOrigen']($relacion['IdEntidadOrigen']),
                'fechaRelacion' => $relacion['CreatedAt'],
            );

        return $array;
    }

}

?>