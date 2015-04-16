<?php

/**
 * CONTROLLER FOR RecibosClientes
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 07.06.2011 00:45:18

 * Extiende a la clase controller
 */
class RecibosClientesController extends Controller {

    protected $entity = "RecibosClientes";
    protected $parentEntity = "";

    public function __construct($request) {

        $formasPago = new FormasPago();
        $this->values['formasPago'] = $formasPago;
        $this->values['estadosRecibos'] = new EstadosRecibos();
        $this->values['clientes'] = new Clientes();

        $acceso = new ControlAcceso('CajaArqueos');
        $permisos = $acceso->getPermisos();
        $this->values['permisosCajas'] = $permisos['permisosModulo'];
        unset($acceso);

        parent::__construct($request);
    }

    /**
     * Genera una listado por pantalla en base al filtro.
     * Puede recibir un filtro adicional
     *
     * @param string $aditionalFilter
     * @return array con el template y valores a renderizar
     */
    public function listAction($aditionalFilter = '') {

        $clientes = new Clientes();
        $recibos = new RecibosClientes();

        $idComercial = $this->request['filter']['valuesSelected']['5'];

        if ($this->values['permisos']['permisosModulo']['CO']) {

            if ($idComercial) {
                $this->listado->makeQuery($aditionalFilter);
                $this->listado->arrayQuery['FROM'] = str_replace(", {$clientes->getDataBaseName()}.{$clientes->getTableName()}", "", $this->listado->arrayQuery['FROM']);
                $this->listado->arrayQuery['FROM'] .= ", {$clientes->getDataBaseName()}.{$clientes->getTableName()}";
                $this->listado->arrayQuery['WHERE'] .= " AND {$recibos->getDataBaseName()}.{$recibos->getTableName()}.IDCliente={$clientes->getDataBaseName()}.{$clientes->getTableName()}.IDCliente ";
                $this->listado->arrayQuery['WHERE'] .= "AND {$clientes->getDataBaseName()}.{$clientes->getTableName()}.IDComercial='{$idComercial}'";
                $this->listado->buildQuery();
            }

            $this->values['listado'] = $this->listado->getAll($aditionalFilter);
            $this->values['filtroRemesa'] = ($this->values['listado']['filter']['valuesSelected'][11]);

            // Obtener total recibos y total a remesar
            $em = new EntityManager($recibos->getConectionName());
            if ($em->getDbLink()) {
                $query = "select sum(Importe) as Importe from {$this->listado->arrayQuery['FROM']} where {$this->listado->arrayQuery['WHERE']}";
                $em->query($query);
                $total = $em->fetchResult();

                $query1 = "select sum(Importe) as Importe from {$this->listado->arrayQuery['FROM']} where {$this->listado->arrayQuery['WHERE']} and Remesar='1'";
                $em->query($query1);
                $remesa = $em->fetchResult();
                $em->desConecta();
            }
            unset($em);

            $this->values['listado']['importeRecibos'] = $total[0]['Importe'];
            $this->values['listado']['importeRemesa'] = $remesa[0]['Importe'];

            $template = $this->entity . '/list.html.twig';
        } else {
            $template = "_global/forbiden.html.twig";
        }

        unset($clientes);
        unset($recibos);

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Guarda todos los recibos que le vienen en el request y
     * comprueba el cuadre entre el importe total de todos los
     * recibos de cada factura y el importe total de la factura
     *
     * @return array
     */
    public function guardarAction() {

        if ($this->values['permisos']['permisosModulo']['UP']) {
            $arrayFacturas = array();

            foreach ($this->request['RecibosClientes'] as $recibo) {
                $objeto = new RecibosClientes($recibo['IDRecibo']);
                $objeto->setVencimiento($recibo['Vencimiento']);
                $objeto->setImporte($recibo['Importe']);
                $objeto->setIban($recibo['Iban']);
                $objeto->setConcepto($recibo['Concepto']);
                $objeto->setIDRemesa($recibo['IDRemesa']);
                $objeto->setIDEstado($recibo['IDEstado']);
                $objeto->save();

                // Guardo temporalmente todas las facturas afectadas
                $arrayFacturas[] = $objeto->getIDFactura()->getIDFactura();
            }
            unset($objeto);

            // Comprobar cuadre recibos-factura
            $arrayFacturas = array_unique($arrayFacturas);
            foreach ($arrayFacturas as $idFactura) {
                $factura = new FemitidasCab($idFactura);
                $totalFactura = $factura->getTotal();
                $sumaRecibos = $factura->getSumaRecibos();
                if ($totalFactura != $sumaRecibos) {
                    $diferencia = $totalFactura - $sumaRecibos;
                    $this->values['errores'][] = "Descuadre en factura {$factura->getNumeroFactura()} -> Total Factura: {$totalFactura} Suma Recibos {$sumaRecibos}. Diferencia {$diferencia}";
                }
            }
            unset($factura);

            return $this->listAction();
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Genera un recibo en base a otro.
     * Se utiliza para fraccionar un recibo cuando se
     * producen pagos parciales del mismo
     *
     * @return array
     */
    public function DesdoblarAction() {

        if ($this->values['permisos']['permisosModulo']['IN']) {
            if ($this->request['idReciboDesdoblar']) {
                $recibo = new RecibosClientes($this->request['idReciboDesdoblar']);
                $reciboNuevo = $recibo;
                $reciboNuevo->setIDRecibo('');
                $reciboNuevo->setRecibo('9999');
                $reciboNuevo->setPrimaryKeyMD5('');
                $reciboNuevo->create();
                unset($recibo);
                unset($reciboNuevo);
            }
            return $this->listAction();
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Cambia de estado al conjunto de recibos recibidos
     *
     * Actualiza su estado, la fecha de vencimiento y la cuenta contable
     * Además genera apunte de caja si procede
     *
     * @return array
     */
    public function CobrarAction() {

        if ($this->values['permisos']['permisosModulo']['UP']) {

            $formaPago = new FormasPago($this->request['idFP']);
            $anotarEnCaja = ($formaPago->getAnotarEnCaja()->getIDTipo() == '1');
            $estadoRecibo = $formaPago->getEstadoRecibo()->getIDTipo();
            $cContable = $formaPago->getCContable();

            $caja = new CajaArqueos();

            foreach ($this->request['RecibosClientes'] as $recibo) {
                $objeto = new RecibosClientes($recibo['IDRecibo']);
                $objeto->setVencimiento($this->request['fechaCobro']);
                $objeto->setIDEstado($estadoRecibo);
                $objeto->setCContable($cContable);
                if (($objeto->save()) and ( $anotarEnCaja)) {
                    $caja->anotaEnCaja($objeto, $this->request['idFP']);
                }
                if (count($objeto->getErrores) > 0) {
                    print_r($objeto->getErrores());
                }
            }
            unset($objeto);
            unset($formaPago);
            unset($caja);

            return $this->listAction();
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    public function RemesarAction() {

        $remesa = $this->request['remesa'];

        // Construir el filtro
        $fecha = new Fecha($remesa['desdeFecha']);
        $desde = $fecha->getaaaammdd();
        $fecha = new Fecha($remesa['hastaFecha']);
        $hasta = $fecha->getaaaammdd();
        unset($fecha);
        $filtro = "(r.Vencimiento>='{$desde}') and (r.Vencimiento<='{$hasta}')";

        if ($remesa['idCliente'] != '') {
            $filtro .= " and (r.IDCliente='{$remesa['idCliente']}')";
        }
        
        if ($remesa['idEstado'] != '') {
            $filtro .= " and (r.IDEstado='{$remesa['idEstado']}')";
        }

        foreach ($this->request['filter']['valuesSelected'] as $key => $value)
            if (($value != '') and ( !in_array($key, array('6', '7', '8', '9')))) {
                if ($key == '3')
                    $filtro .= " and c.RazonSocial like '{$value}'";
                else if ($key == '4')
                    $filtro .= " and c.NombreComercial like '{$value}'";
                else
                    $filtro .= " and (r.{$this->request['filter']['columnsSelected'][$key]}='{$value}')";
            }

        //echo $filtro;exit;
        //$ficheroRemesa = Cuaderno19::makeRemesa($remesa, $filtro);
        $ficheroRemesa = Cuaderno19SepaXml::makeRemesa($remesa, $filtro);

        if ($ficheroRemesa) {
            $this->values['alertas'][] = "Se ha generado la remesa {$ficheroRemesa}";
        } else {
            $this->values['alertas'][] = "No se ha generado la remesa";
        }

        return $this->IndexAction();
    }

}
