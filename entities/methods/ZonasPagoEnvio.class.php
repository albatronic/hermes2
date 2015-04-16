<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 14.03.2014 23:46:54
 */

/**
 * @orm:Entity(ErpZonasPagoEnvio)
 */
class ZonasPagoEnvio extends ZonasPagoEnvioEntity {

    public function __toString() {
        return ($this->Id) ? $this->PlazoEntrega : '';
    }

    public function getCombinaciones($uso = '0') {

        $query = "select c.IDZona,c.IDFP,c.IDAgencia,c.PlazoEntrega,c.Gastos,z.Zona,p.Descripcion,a.Agencia"
                . " FROM {$this->getDataBaseName()}.ErpZonasPagoEnvio c"
                . " JOIN {$this->getDataBaseName()}.ErpZonasTransporte z ON z.IDZona=c.IDZona"
                . " JOIN {$this->getDataBaseName()}.ErpFormasPago p ON p.IDFP=c.IDFP"
                . " JOIN {$this->getDataBaseName()}.ErpAgencias a ON a.IDAgencia=c.IDAgencia"
                . " WHERE (c.Uso='0' OR c.Uso='{$uso}') "
                . " ORDER BY c.IDZona ASC, c.IDFP ASC, c.IDAgencia";
        //echo $query;
        $this->conecta();
        if (is_resource($this->_dbLink)) {
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
        }

        $array = array();
        foreach ($rows as $row) {
            $array[$row['IDZona']]['nombre'] = $row['Zona'];
            $array[$row['IDZona']][$row['IDFP']]['nombre'] = $row['Descripcion'];
            $array[$row['IDZona']][$row['IDFP']][$row['IDAgencia']] = array(
                'nombre' => $row['Agencia'],
                'plazoEntrega' => $row['PlazoEntrega'],
                'gastos' => $row['Gastos'],
            );
        }
        return $array;
    }
}

?>