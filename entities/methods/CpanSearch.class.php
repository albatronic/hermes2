<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 18.04.2013 13:10:03
 */

/**
 * @orm:Entity(CpanDocs)
 */
class CpanSearch extends CpanSearchEntity {

    protected $Publish = '1';
    
    public function actualiza($objeto) {
     
        $entidad = $objeto->getClassName();
        $idEntidad = $objeto->getPrimaryKeyValue();

        $this->queryDelete("Entity='{$entidad}' and IdEntity='$idEntidad'");

        if ( ($objeto->getPublish()->getIDTipo() == '1') and ($objeto->getDeleted()->getIDTipo() == '0') ) {
            $variables = new CpanVariables("Mod","Env",$entidad);
            foreach ($variables->getNode("columns") as $columna => $atributos)
                if ($atributos['searchable']) {
                    $texto = $objeto->{"get$columna"}();
                    if ($texto) {
                        $search = new CpanSearch();
                        $search->setTexto($texto);
                        $search->setEntity($entidad);
                        $search->setIdEntity($idEntidad);
                        $search->setPublish($objeto->getPublish()->getIDTipo());
                        $search->setChecked($objeto->getChecked()->getIDTipo());
                        $search->setPrivacy($objeto->getPrivacy()->getIDTipo());
                        $search->create();
                    }
                }
        }
    }

}

?>