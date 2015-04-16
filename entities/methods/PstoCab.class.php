<?php

/**
 * Description of PstoCab
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class PstoCab extends PstoCabEntity {

    public function __toString() {
        return $this->getIDPsto();
    }

    /**
     * Devuelve la fecha del albaran en formato aaaa-mm-dd
     * @return string La fecha en formato aaaa-mm-dd
     */
    public function getDate() {
        return $this->Fecha;
    }

    protected function load($showDeleted = FALSE) {
        if ($this->IDPsto == '') {
            //Si el nº de presupuesto está vacio (se ha instanciado un objeto vacio),
            //asigno valores por defecto (agente,comercial,sucursal y cliente).
            $this->setIDAgente($_SESSION['usuarioPortal']['Id']);

            $agente = new Agentes($_SESSION['usuarioPortal']['Id']);
            $esComercial = ($agente->getEsComercial());
            if ($esComercial) {
                $idComercial = $_SESSION['usuarioPortal']['Id'];
                $this->setIDComercial($idComercial);
            }

            $rows = $agente->getSucursales($_SESSION['emp']);
            $idSucursal = $rows[0]['Id'];
            $this->setIDSucursal($idSucursal);

            $rows = $agente->getAlmacenes($_SESSION['emp']);
            $idAlmacen = $rows[0]['Id'];
            $this->setIDAlmacen($idAlmacen);

            unset($agente);
        }

        parent::load();
    }

    public function create() {

        // Calcular el Numero de albaran en base al contador
        $contador = new Contadores($this->IDContador);
        $this->setNumeroPsto($contador->asignaContador());
        unset($contador);

        return parent::create();
    }

    /**
     * Guarda la informacion (update)
     */
    public function save() {

        $this->recalcula();
        parent::save();
    }

    /**
     * Borra un presupuesto y sus líneas
     * Siempre que esté en estado 0 (no aceptado)
     *
     * @return boolean
     */
    public function erase() {
        $this->conecta();

        $ok = $this->queryDelete("`IDPsto`='{$this->IDPsto}' AND IDEstado='0'");
        if ($ok) {
            //Borrar líneas de presupuestos
            $lineas = new PstoLineas();
            $lineas->queryDelete("`IDPsto`='{$this->IDPsto}'");
        }

        return (count($this->_errores) == 0);
    }

    /**
     * Recalcula los importes del presupuesto en base a sus lineas
     * Actualiza las propiedades de totales pero no salva los datos.
     * IMPORTANTE: Para que los calculos tomen efecto hay que llamar al método save()
     */
    public function recalcula() {

        //Fuerzo el almacen y el comercial de las líneas de presupuesto al de la cabecera del presupuesto
        $lineas = new PstoLineas();
        $lineas->queryUpdate(
                array("IDComercial" => $this->IDComercial, "IDAlmacen" => $this->IDAlmacen), "`IDPsto`='{$this->IDPsto}'"
        );
        unset($lineas);

        //Si la version es CRISTAL calculo el eventual recargo energetico
        if ($_SESSION['ver'] == 1)
            $this->calculaRecargoEnergetico();

        //Si el cliente no está sujeto a iva
        //pongo el iva a cero en las líneas para evitar que por cambio
        //de cliente se aplique indebidamente
        $cliente = new Clientes($this->IDCliente);
        if ($cliente->getIva()->getIDTipo() == '0') {
            $lineas = new PstoLineas();
            $lineas->queryUpdate(
                    array("Iva" => 0, "Recargo" => 0), "`IDPsto`='{$this->IDPsto}'"
            );
            unset($lineas);
        }
        //Si el cliente no está sujeto a recargo de equivalencia
        //lo pongo a cero en las líneas para evitar que por cambio
        //de cliente se aplique indebidamente
        elseif ($cliente->getRecargoEqu()->getIDTipo() == '0') {
            $lineas = new PstoLineas();
            $lineas->queryUpdate(
                    array("Recargo" => 0), "`IDPsto`='{$this->IDPsto}'"
            );
            unset($lineas);
        }
        unset($cliente);

        //SI TIENE DESCUENTO, CALCULO EL PORCENTAJE QUE SUPONE RESPECTO AL IMPORTE BRUTO
        //PARA REPERCUTUIRLO PORCENTUALMENTE A CADA BASE
        $pordcto = 0;
        if ($this->getDescuento() != 0)
            $pordcto = round(100 * ($this->getDescuento() / $this->getImporte()), 2);

        //Calcular los totales, desglosados por tipo de iva.
        $this->conecta();
        if (is_resource($this->_dbLink)) {
            $lineas = new PstoLineas();
            $tableLineas = "{$lineas->getDataBaseName()}.{$lineas->getTableName()}";
            $articulos = new Articulos();
            $tableArticulos = "{$articulos->getDataBaseName()}.{$articulos->getTableName()}";
            unset($lineas);
            unset($articulos);
            
            $query = "select sum(Importe) as Bruto,sum(ImporteCosto) as Costo from {$tableLineas} where (IDPsto='" . $this->getIDPsto() . "')";
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
            $bruto = $rows[0]['Bruto'];

            $query = "select Iva,Recargo, sum(Importe) as Importe from {$tableLineas}  where (IDPsto='" . $this->getIDPsto() . "') group by Iva,Recargo order by Iva";
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
            $totbases = 0;
            $totiva = 0;
            $totrec = 0;
            $bases[0] = $bases[1] = $bases[2] = array('b' => 0, 'i' => 0, 'ci' => 0, 'r' => 0, 'cr' => 0);            

            foreach ($rows as $key => $row) {
                $importe = $row['Importe'] * (1 - $pordcto / 100);
                $cuotaiva = round($importe * $row['Iva'] / 100, 2);
                $cuotarecargo = round($importe * $row['Recargo'] / 100, 2);
                $totbases += $importe;
                $totiva += $cuotaiva;
                $totrec += $cuotarecargo;

                $bases[$key] = array(
                    'b' => $importe,
                    'i' => $row['Iva'],
                    'ci' => $cuotaiva,
                    'r' => $row['Recargo'],
                    'cr' => $cuotarecargo
                );
            }

            $subtotal = $totbases + $totiva + $totrec;

            // Calcular el recargo financiero según la forma de pago
            $formaPago = new FormasPago($this->IDFP);
            $recFinanciero = $formaPago->getRecargoFinanciero();
            $cuotaRecFinanciero = $subtotal * $recFinanciero / 100;
            unset($formaPago);

            $total = $subtotal + $cuotaRecFinanciero;

            //Calcular el peso, volumen y n. de bultos de los productos inventariables
            switch ($_SESSION['ver']) {
                case '1': //Cristal
                    $columna = "MtsAl";                
                case '0': //Estandar
                default:
                    $columna = "Unidades";
            }
            $em = new EntityManager($this->getConectionName());
            $query = "select sum(a.Peso*l.{$columna}) as Peso,
                        sum(a.Volumen*l.{$columna}) as Volumen,
                        sum(Unidades) as Bultos 
                      from {$tableArticulos} as a,{$tableLineas} as l
                      where (l.IDArticulo=a.IDArticulo)
                        and (a.Inventario='1')
                        and (l.IDPsto='{$this->IDPsto}')";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();

            $this->setImporte($bruto);
            $this->setBaseImponible1($bases[0]['b']);
            $this->setIva1($bases[0]['i']);
            $this->setCuotaIva1($bases[0]['ci']);
            $this->setRecargo1($bases[0]['r']);
            $this->setCuotaRecargo1($bases[0]['cr']);
            $this->setBaseImponible2($bases[1]['b']);
            $this->setIva2($bases[1]['i']);
            $this->setCuotaIva2($bases[1]['ci']);
            $this->setRecargo2($bases[1]['r']);
            $this->setCuotaRecargo2($bases[1]['cr']);
            $this->setBaseImponible3($bases[2]['b']);
            $this->setIva3($bases[2]['i']);
            $this->setCuotaIva3($bases[2]['ci']);
            $this->setRecargo3($bases[2]['r']);
            $this->setCuotaRecargo3($bases[2]['cr']);
            $this->setTotalBases($totbases);
            $this->setTotalIva($totiva);
            $this->setTotalRecargo($totrec);
            $this->setRecargoFinanciero($recFinanciero);
            $this->setCuotaRecargoFinanciero($cuotaRecFinanciero);
            $this->setTotal($total);
            if ($this->Peso == 0)
                $this->setPeso($rows[0]['Peso']);
            if ($this->Volumen == 0)
                $this->setVolumen($rows[0]['Volumen']);
            if ($this->Bultos == 0)
                $this->setBultos($rows[0]['Bultos']);
        }
    }

    /**
     * Confirma el presupuesto, que consiste en aprobarlo
     * y generar un albaran en base al mismo.
     * Devuelve el id del albaran generado
     * IMPORTANTE: APLICA LA TARIFA Y PROMOCIONES VIGENTES
     *
     * @return integer El id del albaran generado
     */
    public function confirma() {

        //Crear cabecera de albaran.
        //No pongo los totales porque los recalculo una vez
        //creadas las lineas del albaran.

        $contador = new Contadores();

        $albaran = new AlbaranesCab();
        $albaran->setIDSucursal($this->IDSucursal);
        $albaran->setIDContador($contador->dameContador($this->IDSucursal, 1));
        $albaran->setIDAlmacen($this->IDAlmacen);
        $albaran->setIDAgente($_SESSION['usuarioPortal']['Id']);
        $albaran->setIDComercial($this->IDComercial);
        $albaran->setFecha(date('d-m-Y'));
        $albaran->setIDCliente($this->IDCliente);
        $albaran->setIDDirec($this->IDDirec);
        $albaran->setDescuento($this->Descuento);
        $albaran->setObservaciones($this->Observaciones . "Proviene del Psto N. " . $this->NumeroPsto);
        $albaran->setIDAgencia($this->IDAgencia);
        $albaran->setIDFP($this->IDFP);
        $albaran->setIDPsto($this->IDPsto);
        $idAlbaran = $albaran->create();

        unset($contador);

        if ($idAlbaran) {
            // Marco el presupuesto como confirmado (Estado=1)
            $this->setIDEstado(1);
            $this->setIDAlbaran($idAlbaran);
            $this->setFechaAceptacion(date('d-m-Y'));
            $this->save();

            // Crear las lineas de albaran
            $lineas = new PstoLineas();
            $rows = $lineas->cargaCondicion('*', "IDPsto='{$this->IDPsto}'", "IDLinea ASC");
            unset($lineas);
            foreach ($rows as $row) {
                $linea = new AlbaranesLineas();
                $linea->setIDAlbaran($idAlbaran);
                $linea->setIDArticulo($row['IDArticulo']);
                $linea->setDescripcion($row['Descripcion']);
                $linea->setUnidades($row['Unidades']);
                $linea->setPrecio($row['Precio']);
                $linea->setDescuento($row['Descuento']);
                $linea->setImporte($row['Importe']);
                $linea->setImporteCosto($row['ImporteCosto']);
                $linea->setIDAlmacen($this->IDAlmacen);
                $linea->setIva($row['Iva']);
                $linea->setRecargo($row['Recargo']);
                $linea->setIDAgente($_SESSION['usuarioPortal']['Id']);
                $linea->setIDComercial($this->IDComercial);
                $linea->setAltoAl($row['AltoAl']);
                $linea->setAnchoAl($row['AnchoAl']);
                $linea->setIDEstado(0);
                if ($linea->valida()) {
                    $linea->create();

                    // Marco la línea de psto como confirmado (Estado=1)
                    $lineaPsto = new PstoLineas($row['IDLinea']);
                    $lineaPsto->setIDEstado(1);
                    $lineaPsto->save();
                    unset($lineaPsto);
                }
            }

            // Recalcular el albarán
            $albaran->recalcula();
            $albaran->save();
            // Confirmar el albarán
            $albaran->confirma();

            $this->_alertas[] = "Se ha generado el albarán n. " . $albaran->getNumeroAlbaran();
        } else {
            $this->_alertas[] = "No se ha generado albarán";
        }

        return $idAlbaran;
    }

    /**
     * Anula la confirmacion del presupuesto
     * Pone el presupuesto y sus lineas en el estado 0
     */
    public function anulaConfirmacion() {

        $this->setIDEstado(0);
        $this->setIDAlbaran(0);
        $this->FechaAceptacion = 0;
        $this->save();

        $lineas = new PstoLineas();
        $lineas->queryUpdate(array("IDEstado" => 0), "IDPsto='{$this->IDPsto}'");
        unset($lineas);
    }

    /**
     * Hace una copia del presupuesto.
     * Genera otro presupuesto en base al actual.
     * IMPORTANTE: SE TOMAN LOS PRECIOS ACTUALES DE LOS ARTICULOS.
     *
     * @return integer El id del presupuesto generado
     */
    public function duplica() {

        $idOrigen = $this->IDPsto;

        // Crear la cabecera del presupuesto
        $destino = $this;
        $destino->setIDPsto('');
        $destino->setIDAgente($_SESSION['usuarioPortal']['Id']);
        $destino->setIDEstado(0);
        $destino->setFecha(date('d-m-Y'));
        $destino->setFechaAceptacion('00-00-0000');
        $destino->setIDAlbaran(0);
        $destino->setObservaciones('Proviene del Psto n. ' . $idOrigen);
        $destino->setPrimaryKeyMD5('');
        $idDestino = $destino->create();

        // Crear las líneas de presupuesto
        if ($idDestino) {
            $linea = new PstoLineas();
            $rows = $linea->cargaCondicion("IDLinea", "IDPsto='{$idOrigen}'", "IDLinea ASC");
            unset($linea);
            foreach ($rows as $row) {
                $lineaDestino = new PstoLineas($row['IDLinea']);
                $lineaDestino->setIDPsto($idDestino);
                $lineaDestino->setIDAgente($_SESSION['usuarioPortal']['Id']);
                $lineaDestino->setIDEstado(0);
                $lineaDestino->setPrimaryKeyMD5('');
                $lineaDestino->valida(); // Toma los precios vigentes (tarifa, promociones, etc)
                $lineaDestino->create();
            }
            unset($lineaDestino);
        } else {
            $this->_errores[] = "Hubo un error al duplicar el presupuesto. Revise los contadores.";
        }

        return $idDestino;
    }

    /**
     * CÁLCULO DEL RECARGO ENERGÉTICO.
     * Si hay artículos que están sujetos a recargo energético, hay que calcular
     * el importe del impuesto e incluir una línea automática con dicho valor.
     * Si la línea ya está incluida, se actualiza.
     *
     * El articulo que se factura para recargo energetico viene definido en el parametro REART
     * Y el precio kilo en el parametro REIMP
     */
    public function calculaRecargoEnergetico() {

        $var = new CpanVariables("Mod", "Web", "PcaeEmpresas");
        $parametro = $var->getNode("especificas");
        $reArticulo = $parametro['reArticulo'];
        unset($var);

        // Ver si existe el articulo que se ha definido en parametros
        // para facturar el recargo energetico
        $articulo = new Articulos();
        $articulo = $articulo->find('Codigo', $reArticulo);

        if ($articulo->getIDArticulo()) {
            // Se ha definido el parametro con el codigo del articulo
            // para aplicar el recargo energetico. Ahora hay que ver si en el presupuesto
            // en curso hay articulos sujetos a recargo energético.
            $em = new EntityManager($this->getConectionName());
            $tablaArticulos = "{$articulo->getDataBaseName()}.{$articulo->getTableName()}";
            $lineas = new PstoLineas();
            $tablaLineas = "{$lineas->getDataBaseName()}.{$lineas->getTableName()}";
            unset($lineas);
            $query = "select t1.*,t2.Peso from {$tablaLineas} as t1, {$tablaArticulos} as t2 where t1.IDPsto='{$this->IDPsto}' and t1.IDArticulo=t2.IDArticulo and t2.RecargoEnergetico='1'";
            $em->query($query);
            $rows = $em->fetchResult();
            if ($rows) {
                //Hay articulos sujetos a recargo energetico
                $reKilos = 0;
                foreach ($rows as $row) {
                    $reKilos += $row['MtsFa'] * $row['Peso'];
                }

                $reImporte = $reKilos * $articulo->getPrecioVenta();
                //Ver si ya está la linea de presupuesto creada.
                $query = "select IDLinea from {$tablaLineas} where IDPsto='{$this->IDPsto}' and IDArticulo='{$articulo->getIDArticulo()}'";
                $em->query($query);
                $rows = $em->fetchResult();
                if ($rows) {
                    // Ya estaba, actualizar
                    $query = "update {$tablaLineas} set Unidades='{$reKilos}',Precio='{$articulo->getPrecioVenta()}',Importe='{$reImporte}' where IDLinea='{$rows[0]['IDLinea']}'";
                    $em->query($query);
                } else {
                    // No está, crear
                    $lineaPsto = new PstoLineas();
                    $lineaPsto->setIDPsto($this->IDPsto);
                    $lineaPsto->setIDArticulo($articulo->getIDArticulo());
                    $lineaPsto->setDescripcion($articulo->getDescripcion());
                    $lineaPsto->setUnidades($reKilos);
                    $lineaPsto->setPrecio($articulo->getPrecioVenta());
                    $lineaPsto->setImporte($reImporte);
                    $lineaPsto->setIDAgente($_SESSION['usuarioPortal']['Id']);
                    $lineaPsto->setIDComercial($this->IDComercial);
                    $lineaPsto->setIDAlmacen($this->IDAlmacen);
                    $lineaPsto->setIva($articulo->getIDIva()->getIva());
                    $lineaPsto->create();
                    unset($lineaPsto);
                }
            } else {
                // No hay articulos con recargo energetico.
                // Borro el eventual cargo de recargo energetico.
                $lineas = new PstoLineas();
                $lineas->queryDelete("IDPsto='{$this->IDPsto}' and IDArticulo='{$articulo->getIDArticulo()}'");
                unset($lineas);
            }
            $em->desConecta();
            unset($em);
        }
    }

    /**
     * Calcula el beneficio del presupuesto
     *
     * Devuelve un array con el precio de venta, el costo (bases imponibles)
     * y el beneficio del presupuesto
     *
     * array (
     *  'Venta' => Importe total del presupuesto base imponible,
     *  'Costo' => Importe total del costo del presupuesto base imponible
     *  'Beneficio' => Venta - Costo
     * )
     *
     * @param integer Id del presupuesto
     * @return array
     */
    public function getBeneficio($idPsto = '') {
        if ($idPsto == '')
            $idPsto = $this->getIDPsto();

        $lineas = new PstoLineas();
        $rows = $lineas->cargaCondicion("sum(ImporteCosto) as Costo", "IDPsto='{$idPsto}'");
        unset($lineas);

        $beneficio = array(
            'Venta' => $this->TotalBases,
            'Costo' => $rows[0]['Costo'],
            'Beneficio' => $this->TotalBases - $rows[0]['Costo'],
        );

        return $beneficio;
    }

    /**
     * Comprueba el riesdo del cliente
     */
    protected function validaLogico() {

        parent::validaLogico();

        $cliente = new Clientes($this->IDCliente);
        $riesgo = $cliente->getRiesgo();
        unset($cliente);
        if ($riesgo['RE']['Recibos'] > 0) {
            $this->_alertas[] = "Albaranes Ptes Facturar " . $riesgo['AL']['Albaranes'] . " por " . $riesgo['AL']['Importe'] . "€";
            $this->_alertas[] = "Recibos Ptes Cobro " . $riesgo['RE']['Recibos'] . " por " . $riesgo['RE']['Importe'] . "€";
            $this->_alertas[] = "Límite de Riesgo " . $riesgo['RI'] . "€";
        }
    }

}

?>
