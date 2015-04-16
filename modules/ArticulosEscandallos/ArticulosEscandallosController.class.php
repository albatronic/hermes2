<?php

/**
 * CONTROLLER FOR ArticulosEscandallos
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 24.09.2013 23:56:13

 * Extiende a la clase controller
 */
class ArticulosEscandallosController {

    var $entity = "ArticulosEscandallos";
    var $parentEntity = "Articulos";
    var $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function listAction($idArticuloOrigen = '') {

        if ($idArticuloOrigen == '')
            $idArticuloOrigen = $this->request[2];

        $articulo = new ArticulosEscandallos();
        $rows = $articulo->cargaCondicion("*", "IDArticuloOrigen='{$idArticuloOrigen}' and IDArticuloDestino>0","Id ASC");
        $totales = $articulo->cargaCondicion("*", "IDArticuloOrigen='{$idArticuloOrigen}' and IDArticuloDestino=0");
        unset($articulo);

        $escandallo[] = new ArticulosEscandallos();

        foreach ($rows as $row)
            $escandallo[] = new ArticulosEscandallos($row['Id']);

        $this->values['articulos'] = $escandallo;
        if ($totales[0]['Id'])
            $this->values['totales'] = new ArticulosEscandallos($totales[0]['Id']);
        $this->values['IDArticuloOrigen'] = $idArticuloOrigen;
        $this->values['articuloOrigen'] = new Articulos($idArticuloOrigen);
        
        // Si hay existencias del articulo origen, no permito cambiar su escandallo
        $existencias = new Existencias();
        $this->values['bloqueo'] = $existencias->hayRegistroExistencias($idArticuloOrigen);
        unset($existencias);

        return array(
            'template' => $this->entity . '/index.html.twig',
            'values' => $this->values
        );
    }

    public function editAction() {

        $datos = $this->request[$this->entity];

        $articulo = new Articulos($datos['IDArticuloDestino']);

        if ($articulo->getIDArticulo() != '') {

            $escan = new ArticulosEscandallos($datos['Id']);
            if ($escan->getId()) {
                switch ($this->request['accion']) {
                    case 'G':
                        $escan->setIDArticuloDestino($articulo->getIDArticulo());
                        $escan->setImporteCosto($articulo->getPmc() * $datos['Unidades']);
                        $escan->setImporteVenta($articulo->getPvp() * $datos['Unidades']);
                        $escan->setUnidades($datos['Unidades']);
                        $escan->setPeso($articulo->getPeso() * $datos['Unidades']);
                        $escan->setVolumen($articulo->getVolumen() * $datos['Unidades']);
                        $escan->save();
                        break;
                    case 'B':
                        $escan->erase();
                        break;
                }

                $this->recalcular($datos['IDArticuloOrigen']);
            }
            unset($escan);
        }

        return $this->listAction($datos['IDArticuloOrigen']);
    }

    public function newAction() {

        $datos = $this->request[$this->entity];

        $articulo = new Articulos($datos['IDArticuloDestino']);
        if ($articulo->getIDArticulo() != '') {
            $escandallo = new ArticulosEscandallos();
            $escandallo->bind($datos);
            $escandallo->setImporteCosto($articulo->getPmc() * $datos['Unidades']);
            $escandallo->setImporteVenta($articulo->getPvp() * $datos['Unidades']);
            $escandallo->setPeso($articulo->getPeso() * $datos['Unidades']);
            $escandallo->setVolumen($articulo->getVolumen() * $datos['Unidades']);
            $escandallo->create();
            unset($escandallo);
        }
        unset($articulo);

        $this->recalcular($datos['IDArticuloOrigen']);

        return $this->listAction($datos['IDArticuloOrigen']);
    }

    /**
     * Recalcular el escandallo y actualiza los precios
     * en el articulo padre (origen)
     * 
     * @param inter $idArticuloOrigen
     */
    private function recalcular($idArticuloOrigen) {

        $escan = new ArticulosEscandallos();
        $totales = $escan->cargaCondicion("sum(Unidades) as Unidades,sum(ImporteCosto) as Costo,sum(ImporteVenta) as Venta,sum(Peso) as Peso,sum(Volumen) as Volumen", "IDArticuloOrigen='{$idArticuloOrigen}' and IDArticuloDestino>0 GROUP BY IDArticuloOrigen");
        $totales = $totales[0];
        unset($escan);

        // Borrar la fila de totalización y volverla a crear
        $escan = new ArticulosEscandallos();

        $escan->queryDelete("IDArticuloOrigen='{$idArticuloOrigen}' and IDArticuloDestino='0'");

        $escan = new ArticulosEscandallos();
        $escan->setIDArticuloOrigen($idArticuloOrigen);
        $escan->setIDArticuloDestino(0);
        $escan->setUnidades($totales['Unidades']);
        $escan->setImporteCosto($totales['Costo']);
        $escan->setImporteVenta($totales['Venta']);
        $escan->setPeso($totales['Peso']);
        $escan->setVolumen($totales['Volumen']);
        $escan->create();

        unset($escan);

        // Actualizar el artículo padre
        // Leo el parametro 'ACTU_PRECIOS' para ver el comportamiento a seguir
        // en el cambio de precio de venta o margen. Si no estuviera definido,
        // se respeta el PVP a costa del MARGEN
        $parametro = $_SESSION['usuarioPortal']['actuPrecios'];
        if (($parametro != 'MARGEN') and ($parametro != 'PVP'))
            $parametro = 'MARGEN';

        $articulo = new Articulos($idArticuloOrigen);

        // Calculo el nuevo margen o el nuevo precio de venta (según el parámetro) sobre el PRECIO MEDIO DE COSTO
        $articulo->setPvd($totales['Costo']);
        $articulo->setPvp($totales['Venta']);
        if ($articulo->getPmc() == 0)
            $articulo->setPmc($articulo->getPvd());

        if ($articulo->getPmc() != 0)
            $articulo->setMargen(100 * ($articulo->getPvp() / $articulo->getPmc() - 1));
        else
            $articulo->setMargen(0);

        $articulo->setFechaUltimoPrecio(date('Y-m-d H:i:s'));

        $articulo->save();
        unset($articulo);
    }

}

?>
