<?php

/**
 * Description of FrecibidasLineas
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class FrecibidasLineas extends FrecibidasLineasEntity {

    public function __toString() {
        return $this->getIDLinea();
    }

    /**
     * Totaliza la línea antes de crearla
     *
     * @return boolean
     */
    public function create() {
        $this->totalizaLinea();
        return parent::create();
    }

    public function save() {

        $this->totalizaLinea();
        $ok = parent::save();

        if ($ok) {
            $factura = new FrecibidasCab($this->IDFactura);
            $factura->recalcula();
            $factura->creaVctos();
            $this->_errores[] = "AVISO: Se han vuelto a crear los vencimientos.";
        }

        return $ok;
    }

    /**
     * Borra una línea de factura recibida.
     * Si procede de una línea de pedido, la marca como no facturada y cambia lo pendiente de facturar
     *
     * @return boolean
     */
    public function erase() {
        
        $this->conecta();

        if (is_resource($this->_dbLink)) {
            //Volver a poner la linea de pedido como pendiente de facturar.
            $query = "DELETE FROM {$this->getDataBaseName()}.{$this->getTableName()} where (IDLinea='{$this->IDLinea}')";
            if ($this->_em->query($query)) {
                if ($this->IDPedido != 0) {
                    $lineasPedido = new PedidosLineas();
                    $tabla = $lineasPedido->getDataBaseName().".".$lineasPedido->getTableName();
                    unset($lineasPedido);
                    $query = "UPDATE {$tabla} SET UnidadesPtesFacturar = UnidadesPtesFacturar + {$this->Unidades} WHERE IDLinea='{$this->IDLineaPedido}';";
                    $this->_em->query($query);
                }
            } else
                $this->_errores[] = $this->_em->getError();

            $this->_em->desConecta();
        }
        unset($this->_em);

        return (count($this->_errores) == 0);
    }

    private function totalizaLinea() {
        $this->Importe = ($this->Unidades * $this->Precio) * ( 1 - $this->Descuento / 100);
    }

}

?>
