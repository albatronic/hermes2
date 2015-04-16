<?php

/**
 * CONTROLLER FOR Lotes
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 00:45:16

 * Extiende a la clase controller
 */
class LotesController extends Controller {

    protected $entity = "Lotes";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

    /**
     * Descatalogar los lotes (poner no vigentes) que no tengan existencias
     * entre todos los almacenes de la empresa
     */
    public function DescatalogarAction() {
        if ($this->values['permisos']['permisosModulo']['UP']) {

            $lote = new Lotes();
            $tablaLotes = $lote->getDataBaseName() . "." . $lote->getTableName();
            $existencias = new Existencias();
            $tablaExistencias = $existencias->getDataBaseName() . "." . $existencias->getTableName();
            unset($existencias);

            $em = new EntityManager($lote->getConectionName());
            if ($em->getDbLink()) {
                $query = "
                UPDATE {$tablaLotes} SET Vigente='0'
                WHERE IDLote not in (
                    SELECT IDLote
                    FROM {$tablaExistencias} e
                    WHERE (
                        IDLote>0 AND
                        (
                            Reales>0 OR
                            Reservadas>0 OR
                            Entrando>0
                        )
                    )
                    GROUP BY IDLote
                    ORDER BY IDLote
                );";
                $em->query($query);
                $em->desConecta();
            }
            unset($lote);
            
            return $this->indexAction();
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Catalogar (poner vigentes) todos los lotes
     */
    public function CatalogarAction() {

        if ($this->values['permisos']['permisosModulo']['UP']) {

            $lote = new Lotes();
            $lote->queryUpdate(array("Vigente" => 1));
            unset($lote);

            return $this->indexAction();
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

}

?>