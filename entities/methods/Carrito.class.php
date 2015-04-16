<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 08.02.2014 19:37:17
 */

/**
 * @orm:Entity(ErpCarrito)
 */
class Carrito extends CarritoEntity {

    public function __toString() {
        return $this->getId();
    }

    public function create() {
        $this->Publish = 1;
        return parent::create();
    }

    public function addProduct($idArticulo, $unidades) {

        $ok = false;

        $filtro = "session='{$_SESSION['IdSesion']}' and IDArticulo='{$idArticulo}'";
        $rows = $this->cargaCondicion("Id", $filtro);
        if ($rows[0]['Id']) {
            $carrito = new Carrito($rows[0]['Id']);
            $carrito->setUnidades($carrito->getUnidades() + $unidades);
            $carrito->setImporte($carrito->getUnidades() * $carrito->getPrecio() * (1 - $carrito->getDescuento() / 100));
            $ok = $carrito->save();
            unset($carrito);
        } else {
            $articulo = new Articulos($idArticulo);
            $this->sesion = $_SESSION['IdSesion'];
            $this->IpOrigen = $_SERVER['REMOTE_ADDR'];
            $this->UserAgent = $_SERVER['HTTP_USER_AGENT'];
            $this->IDArticulo = $idArticulo;
            $this->Descripcion = $articulo->getDescripcion();
            $this->Unidades = $unidades;
            $this->UnidadMedida = $articulo->getUnidadMedida("UMV");
            $this->Precio = $articulo->getPrecioVentaConImpuestos();
            $this->Descuento = 0;
            $this->Importe = $this->Unidades * $this->Precio * (1 - $this->Descuento / 100);
            $this->Iva = $articulo->getIDIva()->getIva();
            $this->Recargo = $articulo->getIDIva()->getRecargo();
            $this->Estado = 0;
            $ok = ($this->create() > 0 );
            unset($articulo);
        }

        return $ok;
    }

    public function getTotales() {
        
        $filtro = "session='{$_SESSION['IdSesion']}'";
        $rows = $this->cargaCondicion("sum(Unidades) as Unidades, sum(Importe) as Importe",$filtro);
        
        return $rows[0];
    }

}

?>