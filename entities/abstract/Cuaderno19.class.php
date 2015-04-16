<?php

/**
 * Clase para generar el CUADERNO 19 DEL CSB
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 14-10-2013
 *
 */
class Cuaderno19 extends Remesas {

    static $parametros;
    static $ordenante;
    static $fp;
    static $idRemesa;
    static $fileName;
    static $nRegistros = 2;
    static $nOrdenantes = 0;
    static $nDomiciliaciones = 0;
    static $total = 0;
    static $nRegistrosOrdenante = 2;
    static $nDomiciliacionesOrdenante = 0;
    static $totalOrdenante = 0;

    public function __construct($parametros) {
        self::$parametros = $parametros;
    }

    static function makeRemesa($parametros, $filtro) {

        self::$parametros = $parametros;

        $ficheroRemesa = '';

        if (self::valida()) {
            self::openCuaderno();

            $empresas = new PcaeEmpresas();
            $ordenantes = $empresas->cargaCondicion("*", "Id>='{$_SESSION['emp']}' and Id<='{$_SESSION['emp']}'", "Id ASC");
            unset($empresas);

            foreach ($ordenantes as $ordenante) {
                self::addOrdenante($ordenante);

                //RECORRO LOS RECIBOS DEL ORDENANTE EN CURSO.
                if (self::$parametros['agrupar'])
                    self::RecibosAgrupados($filtro);
                else
                    self::RecibosIndividuales($filtro);

                self::closeOrdenate();
            }
            $ficheroRemesa = self::closeCuaderno();
            self::escribeLog();
        }

        return $ficheroRemesa;
    }

    static function escribeLog() {

        $log[self::$idRemesa] = array(
            'parametros' => self::$parametros,
            'ficheroRemesa' => self::$fileName,
            'ordenante' => self::$ordenante,
            'nRegistros' => self::$nRegistros,
            'nOrdenantes' => self::$nOrdenantes,
            'nDomiciliaciones' => self::$nDomiciliaciones,
            'total' => self::$total,
            'nRegistrosOrdenante' => self::$nRegistrosOrdenante,
            'nDomiciliacionesOrdenante' => self::$nDomiciliacionesOrdenante,
            'totalOrdenante' => self::$totalOrdenante,
        );

        $archivo = "docs/docs{$_SESSION['emp']}/remesas/log.yml";
        if (!file_exists($archivo))
            $fp = fopen($archivo, "w");
        else
            $fp = fopen($archivo, "a");

        $yml = sfYaml::dump($log);

        fwrite($fp, $yml);
        fclose($fp);
    }

    static function valida() {

        $ok = true;

        return $ok;
    }

    static function openCuaderno() {

        //CREAR FICHERO DESTINO
        self::$idRemesa = date('YmdHis');
        $log = "Remesa" . self::$idRemesa;
        self::$fileName = Archivo::getTemporalFileName('remesas', 'txt'); //"docs/docs{$_SESSION['emp']}/remesas/$log";

        $ok = self::$fp = fopen(self::$fileName, 'w');
        if ($ok) {
            $reg = "";
            self::$nRegistros = 2;
            self::$nOrdenantes = 0;
            self::$nDomiciliaciones = 0;
            self::$total = 0;

            $fecha = new Fecha(self::$parametros['fechaRemesa']);
            self::$parametros['fechaRemesa'] = $fecha->getddmmaa();
            $fecha = new Fecha(self::$parametros['fechaCobro']);
            self::$parametros['fechaCobro'] = $fecha->getddmmaa();
            unset($fecha);

            //CABECERA PRESENTADOR
            self::$parametros['cif'] = str_pad(self::$parametros['cif'], 9, " ", STR_PAD_RIGHT);
            self::$parametros['razonSocial'] = str_pad(self::$parametros['razonSocial'], 40, " ", STR_PAD_RIGHT);
            $reg = "5180" . self::$parametros['cif'] . "000" . self::$parametros['fechaRemesa'] . self::Vacio(6) . self::$parametros['razonSocial'] . self::Vacio(20) . self::$parametros['entidad'] . self::$parametros['oficina'] . self::Vacio(66);
            $ok = self::Escribe(self::$fp, $reg);
        }

        return $ok;
    }

    /**
     * Cierra el fichero y devuelve el fullpath
     * 
     * @return string EL nombre del fichero generado
     */
    static function closeCuaderno() {

        self::$nOrdenantes = self::Ceros(self::$nOrdenantes, 4);
        self::$nDomiciliaciones = self::Ceros(self::$nDomiciliaciones, 10);
        self::$total = self::Ceros(str_replace('.', '', number_format(self::$total, 2, '.', '')), 10);
        self::$nRegistros = self::Ceros(self::$nRegistros, 10);
        $reg = "5980" . self::$parametros['cif'] . "000" . self::Vacio(52) . self::$nOrdenantes . self::Vacio(16) . self::$total . self::Vacio(6) . self::$nDomiciliaciones . self::$nRegistros . self::Vacio(38);
        self::Escribe(self::$fp, $reg);

        fclose(self::$fp);
        return self::$fileName;
    }

    static function addOrdenante($ordenante) {
        self::$totalOrdenante = 0;
        self::$nDomiciliacionesOrdenante = 0;
        self::$nRegistrosOrdenante = 2;

        //CABECERA ORDENANTE
        self::$ordenante = $ordenante;
        self::$ordenante['RazonSocial'] = self::Rellena(utf8_decode(self::$ordenante['RazonSocial']), 40);
        $reg = "5380" . self::$ordenante['Cif'] . self::$ordenante['SufijoRemesas'] . self::$parametros['fechaRemesa'] . self::$parametros['fechaCobro'] . self::$ordenante['RazonSocial'] . self::$ordenante['Banco'] . self::$ordenante['Oficina'] . self::$ordenante['Digito'] . self::$ordenante['Cuenta'] . self::Vacio(8) . "01" . self::Vacio(64);
        self::Escribe(self::$fp, $reg);
    }

    static function closeOrdenate() {
        //TOTAL ORDENANTE
        self::$nOrdenantes+=1;
        self::$nDomiciliaciones+=self::$nDomiciliacionesOrdenante;
        self::$total+=self::$totalOrdenante;
        self::$nRegistros+=self::$nRegistrosOrdenante;

        self::$nDomiciliacionesOrdenante = self::Ceros(self::$nDomiciliacionesOrdenante, 10);
        self::$totalOrdenante = self::Ceros(str_replace('.', '', number_format(self::$totalOrdenante, 2, '.', '')), 10);
        self::$nRegistrosOrdenante = self::Ceros(self::$nRegistrosOrdenante, 10);
        $reg = "5880" . self::$ordenante['Cif'] . self::$ordenante['SufijoRemesas'] . self::Vacio(72) . self::$totalOrdenante . self::Vacio(6) . self::$nDomiciliacionesOrdenante . self::$nRegistrosOrdenante . self::Vacio(38);
        self::Escribe(self::$fp, $reg);
    }

    /**
     * GENERA UN RECIBO BANCARIO POR CADA RECIBO DE CLIENTE    
     */
    static function RecibosIndividuales($filtro) {
        
        $fecha = new Fecha(self::$parametros['fechaCobro']);
        $fCargo = $fecha->getaaaammdd();
        unset($fecha);

        $recibos = new RecibosClientes();
        $clientes = new Clientes();
        $facturas = new FemitidasCab();
        $tablaRecibos = $recibos->getDataBaseName() . "." . $recibos->getTableName();
        $tablaClientes = $clientes->getDataBaseName() . "." . $clientes->getTableName();
        $tablaFacturas = $facturas->getDataBaseName() . "." . $facturas->getTableName();

        $em = new EntityManager($recibos->getConectionName());
        if ($em->getDbLink()) {
            $filtro .= " and (Remesar='1') and (CHAR_LENGTH(r.Iban)>23) and (r.Iban<>'ES8200000000000000000000') and (r.Importe>0)";
            $query = "select r.*,c.RazonSocial,f.NumeroFactura from {$tablaRecibos} as r
            left join {$tablaClientes} as c on r.IDCliente=c.IDCliente
            left join {$tablaFacturas} as f on r.IDFactura=f.IDFactura
            where {$filtro}
            order by c.RazonSocial,r.Vencimiento ASC";
            $em->query($query);//echo $query;
            $rows = $em->fetchResult();
        }
        unset($em);
        unset($clientes);
        unset($facturas);

        foreach ($rows as $recibo) {
            $codclie = self::Rellena($recibo['IDCliente'], 12);
            $titular = self::Rellena($recibo['RazonSocial'], 40);
            $importe = self::Ceros(str_replace(".", "", $recibo['Importe']), 10);
            $concepto = self::Rellena("Factura " . $recibo['NumeroFactura'] . "/" . $recibo['Recibo'], 40);

            $reg = "5680" . self::$ordenante['Cif'] . self::$ordenante['SufijoRemesas'] . $codclie . $titular . substr($recibo['Iban'], 4, strlen($recibo['Iban'])) . $importe . self::Vacio(16) . $concepto . self::Vacio(8);
            self::Escribe(self::$fp, $reg);

            self::$nRegistrosOrdenante+=1;
            self::$totalOrdenante+=$recibo['Importe'];
            self::$nDomiciliacionesOrdenante+=1;

            //Marcar el recibo con el ID de la remesa y el Vencimiento con la fecha de Cargo de la Remesa
            $recibos->queryUpdate(array('IDRemesa' => self::$idRemesa, 'Vencimiento' => $fCargo, 'IDEstado' => self::$parametros['idEstado']), "IDRecibo='{$recibo['IDRecibo']}'");
        }
    }

    public function RecibosAgrupados($filtro) {

        //$aux = substr(self::$parametros['fechaCobro'], 0, 4) . '20' . substr(self::$parametros['fechaCobro'], -2);
        $fecha = new Fecha(self::$parametros['fechaCobro']);
        $fCargo = $fecha->getaaaammdd();
        unset($fecha);

        $recibos = new RecibosClientes();
        $clientes = new Clientes();
        $tablaRecibos = $recibos->getDataBaseName() . "." . $recibos->getTableName();
        $tablaClientes = $clientes->getDataBaseName() . "." . $clientes->getTableName();

        $em = new EntityManager($recibos->getConectionName());
        if ($em->getDbLink()) {
            $filtro .= " and (Remesar='1') and (CHAR_LENGTH(r.Iban)>23) and (r.Iban<>'ES8200000000000000000000')";
            $query = "select r.IDCliente,sum(r.Importe) as Importe,r.Iban,c.RazonSocial from {$tablaRecibos} as r
            left join {$tablaClientes} as c on r.IDCliente=c.IDCliente
            where {$filtro}
            group by r.IDCliente,r.Iban,c.RazonSocial 
            having sum(r.Importe)>0
            order by c.RazonSocial,r.Vencimiento ASC";
            $em->query($query);
            $rows = $em->fetchResult();
            echo $query;
        }
        unset($em);
        unset($clientes);

        foreach ($rows as $recibo) {
            $codclie = self::Rellena($recibo['IDCliente'], 12);
            $titular = self::Rellena($recibo['RazonSocial'], 40);
            $importe = self::Ceros(str_replace(".", "", $recibo['Importe']), 10);
            $concepto = self::Rellena("Facturas", 40);

            $reg = "5680" . self::$ordenante['Cif'] . self::$ordenante['SufijoRemesas'] . $codclie . $titular . substr($recibo['Iban'], 4, strlen($recibo['Iban'])) . $importe . self::Vacio(16) . $concepto . self::Vacio(8);
            self::Escribe(self::$fp, $reg);

            self::$nRegistrosOrdenante+=1;
            self::$totalOrdenante+=$recibo['Importe'];
            self::$nDomiciliacionesOrdenante+=1;

            //Marcar los recibos con el ID de la remesa
            $filtro = str_replace("r.", "", $filtro);
            $recibos->queryUpdate(array('IDRemesa' => self::$idRemesa, 'Vencimiento' => $fCargo, 'IDEstado' => self::$parametros['idEstado']), "IDCliente='{$recibo['IDCliente']}' and {$filtro}");
        }
    }

}

?>
