<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 16.10.2012 16:33:56
 */

/**
 * @orm:Entity(PcaeGruposEmpresas)
 */
class PcaeGruposEmpresas extends PcaeGruposEmpresasEntity {


    public function __toString() {
        return $this->getDescripcion();
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Descripcion';
        return parent::fetchAll($column, $default);
    }

    /**
     * Devuelve un array de objetos \PcaeEmpresas que
     * constituyen el grupo de empresas en curso.
     *
     * @return array Array de objetos \PcaeEmpresas
     */
    public function getEmpresas() {

        $empresas = array();

        $emp = new PcaeEmpresas();
        $rows = $emp->cargaCondicion("IdEmpresa", "IdGrupo='{$this->Id}'");
        unset($emp);

        foreach ($rows as $row)
            $empresas[] = new PcaeEmpresas($row['IdEmpresa']);

        return $empresas;
    }

}

?>