<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 20.06.2014 18:25:52
 */

/**
 * @orm:Entity(ErpLotesWeb)
 */
class LotesWeb extends LotesWebEntity {

    public function __toString() {
        return ($this->Id) ? $this->Titulo : '';
    }

    /**
     * Devuelve array de objetos articulos que
     * constituyen el lote
     * 
     * @return \Articulos
     */
    public function getArrayObjetosArticulos() {

        return $this->getObjetosRelacionados("Articulos");
    }

    /**
     * Devuelve array de id's de lotesweb 
     * en los que se encuentra el artículo $idArticulo
     * 
     * @param integer $idArticulo El id del artículo a buscas
     * @return array Array de id's de lotes
     */
    public function getArrayLotesArticulo($idArticulo) {

        $relacion = new CpanRelaciones();
        $lotes = $relacion->cargaCondicion("IdEntidadOrigen", "EntidadDestino='Articulos' AND IdEntidadDestino='{$idArticulo}' AND EntidadOrigen='LotesWeb'", "EntidadOrigen,SortOrder ASC");
        unset($relacion);
        foreach ($lotes as $lote) {
            $array[] = $lote['IdEntidadOrigen'];
        }

        return $array;
    }

}
