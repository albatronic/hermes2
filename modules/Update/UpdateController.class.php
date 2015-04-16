<?php

/*
 * TRAVASA LA INFORMACION DE LA BASE DE DATOS ANTIGUA (ppuerpxxx)
 * A LA NUEVA GESTIONADA POR HERMES
 *
 * @author Informática ALBATRONIC, SL <sergio.perez@albatronic.com>
 */

class UpdateController {

    //protected $dbOrigen = "aeroprint_gestion001";
    //protected $dbDestino = "albatro_aeroprint";
    //protected $dbOrigen = "albatro_ppuerp006";
    //protected $dbDestino = "albatro_carroceriasgranada";
    protected $dbOrigen = "albatro_gestion001";
    protected $dbDestino = "albatro_pacs";
    //protected $dbOrigen = "aeroprint_gestion003";
    //protected $dbDestino = "albatro_gastosaeroprint";  
    //protected $dbOrigen = "aeroprint_gestion004";
    //protected $dbDestino = "albatro_gastosmarcos";

    /**
     * Correspondencia entre los ids de la agentes antiguos y los nuevos
     * @var array
     */
    protected $agentes = array(
        //Albatronic
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '7' => '7',
            /**
              '0' => '8',
              '8' => '8',
              '14' => '14',
             */
    );
    protected $correspondenciaIva = array(
        '0.00' => 0,
        '4.00' => 4,
        '16.00' => 16,
        '18.00' => 21,
        '21.00' => 21,
    );

    public function IndexAction() {

        set_time_limit(0);

        $_SESSION['idiomas']['actual'] = -1;

        //$this->Agencias();
        //$this->FormasPago();
        //$this->Clientes();
        //$this->ClientesTipos();
        //$this->ClientesGrupos();
        //$this->Fabricantes();
        //$this->Familias();
        //$this->Subfamilias();
        //$this->Proveedores();
        //$this->Articulos();
        //$this->PstoCab();
        //$this->PstoLineas();
        //$this->AlbaranesCab();
        //$this->AlbaranesLineas();
        //$this->FemitidasCab();
        //$this->FemitidasLineas();
        //$this->FrecibidasCab();
        //$this->FrecibidasLineas();
        //$this->PedidosCab();
        //this->PedidosLineas();
        $this->RecibosClientes();
        //$this->RecibosProveedores();
        /*
          $this->Existencias();
          $this->Inventarios();
         */
    }

    public function Inventarios() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpInventariosCab";
        mysql_query($query);
        $query = "TRUNCATE {$this->dbDestino}.ErpInventariosLineas";
        mysql_query($query);

        $query = "select distinct IDSucursal,DATE_FORMAT(Fecha,'%Y-%m-%d') as FInventario, Cerrado FROM {$this->dbOrigen}.inventarios order by DATE_FORMAT(Fecha,'%Y-%m-%d') asc";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $i = new InventariosCab();
            $i->setIDAlmacen($row['IDSucursal']);
            $i->setFecha($row['FInventario']);
            $i->setCerrado($row['Cerrado']);

            $id = $i->create();
            if (!$id) {
                $errores[] = $i->getErrores();
                $nErrores++;
            } else {
                $i->setIDInventario($id);
                $i->setPrimaryKeyMD5(md5($id));
                $i->save();
                $nItems++;

                $this->LineasInventario($row['FInventario'], $id, $dbLink);
            }
        }

        echo "Inventarios cabeceras {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    private function LineasInventario($fecha, $id, $dbLink) {

        $query = "select i.*,a.IDArticulo as id,a.Descripcion from {$this->dbOrigen}.inventarios i LEFT JOIN {$this->dbDestino}.ErpArticulos a on i.IDArticulo=a.Codigo WHERE DATE_FORMAT(Fecha,'%Y-%m-%d')='{$fecha}' and a.IDArticulo>0";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $l = new InventariosLineas();
            $l->setIDInventario($id);
            $l->setIDArticulo($row['id']);
            $l->setIDLote(0);
            $l->setIDUbicacion(0);
            $l->setDescripcion($row['Descripcion']);
            $l->setStock($row['Stock']);
            $pk = $l->create();
            if (!$id) {
                $errores[] = $l->getErrores();
                $nErrores++;
            } else {
                $l->setIDLinea($pk);
                $l->setPrimaryKeyMD5(md5($pk));
                $l->save();
                $nItems++;
            }
        }
        print_r($errores);
    }

    public function Agencias() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpAgencias";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.agencias";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $a = new Agencias();
            $a->setIDAgencia($row['IDAgencia']);
            $a->setAgencia($row['Agencia']);
            $a->setTelefono($row['Telefono']);
            $a->setFax($row['Fax']);
            $a->setWeb($row['Web']);
            $a->setEmail($row['Email']);
            $a->setPrimaryKeyMD5(md5($row['IDAgencia']));
            $id = $a->create();
            if (!$id) {
                $arrores[] = $a->getErrores();
                $nErrores++;
            } else {

                $nItems++;
            }
        }

        echo "Agencias de transporte {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function Existencias() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpExistencias";
        mysql_query($query);

        $query = "select e.*,a.IDArticulo as id from {$this->dbOrigen}.existencias e left join {$this->dbDestino}.ErpArticulos a on e.IDArticulo=a.Codigo";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $e = new Existencias();
            $e->setIDAlmacen($row['IDSucursal']);
            $e->setIDArticulo($row['id']);
            $e->setReales($row['Reales']);
            $e->setReservadas($row['Reservadas']);
            $e->setEntrando($row['Entrando']);
            $e->setMaximo($row['Maximo']);
            $e->setMinimo($row['Minimo']);

            $id = $e->create();
            if (!$id) {
                $errores[] = $e->getErrores();
                $nErrores++;
            } else {
                $e->setPrimaryKeyMD5(md5($id));
                $e->save();
                $nItems++;
            }
        }

        echo "Existencias {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function RecibosProveedores() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpRecibosProveedores";
        mysql_query($query);

        // Correspondencia entre número de factura e id de factura
        $query = "SELECT r.NumeroFactura as Numero, f.IDFactura as Id FROM {$this->dbOrigen}.recibos_proveedores AS r LEFT JOIN {$this->dbOrigen}.frecibidas_cab AS f ON r.NumeroFactura = f.NumeroFactura";
        $result = mysql_query($query, $dbLink);
        while ($row1 = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row1['Numero']] = $row1['Id'];

        $query = "select * from {$this->dbOrigen}.recibos_proveedores";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new RecibosProveedores();
            $c->setIDRecibo($row['IDRecibo']);
            $c->setRecibo($row['Recibo']);
            $c->setIDSucursal(1);
            $c->setIDFactura($correspondencia[$row['NumeroFactura']]);
            $c->setIDProveedor($row['IDProveedor']);
            $c->setFecha($row['Fecha']);
            $c->setVencimiento($row['Vencimiento']);
            $c->setImporte($row['Importe']);
            $c->setIban(Utils::iban($row['CBanco']));
            $c->setMandato($row['IDProveedor']);
            $c->setFechaMandato('2013-01-01');
            $c->setIDRemesa($row['IDRemesa']);
            $c->setCContable($row['CContable']);            
            $c->setAsiento($row['Asiento']);
            $c->setConcepto($row['Concepto']);

            if ($row['Estado'] == 'C')
                $c->setIDEstado(6);
            else
                $c->setIDEstado(0);

            $c->setPrimaryKeyMD5(md5($row['IDRecibo']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Recibos proveedores creados {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function RecibosClientes() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpRecibosClientes";
        mysql_query($query);

        // Correspondencia entre número de factura e id de factura
        $query = "SELECT r.NumeroFactura as Numero, f.IDFactura as Id FROM {$this->dbOrigen}.recibos_clientes AS r LEFT JOIN {$this->dbOrigen}.femitidas_cab AS f ON r.NumeroFactura = f.NumeroFactura";
        $result = mysql_query($query, $dbLink);
        while ($row1 = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row1['Numero']] = $row1['Id'];

        $query = "select * from {$this->dbOrigen}.recibos_clientes";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new RecibosClientes();
            $c->setIDRecibo($row['IDRecibo']);
            $c->setRecibo($row['Recibo']);
            $c->setIDSucursal(1);
            $c->setIDFactura($correspondencia[$row['NumeroFactura']]);
            $c->setIDCliente($row['IDCliente']);
            $c->setIDComercial(8);
            $c->setFecha($row['Fecha']);
            $c->setVencimiento($row['Vencimiento']);
            $c->setImporte($row['Importe']);
            $c->setIban(Utils::iban($row['CBanco']));
            $c->setMandato($row['IDCliente']);
            $c->setFechaMandato('2013-01-01'); 
            $c->setIDRemesa($row['IDRemesa']);
            $c->setCContable($row['CContable']);
            $c->setAsiento($row['Asiento']);
            $c->setConcepto($row['Concepto']);

            if ($row['Estado'] == 'C')
                $c->setIDEstado(6);
            else
                $c->setIDEstado(0);

            $c->setPrimaryKeyMD5(md5($row['IDRecibo']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Recibos clientes creados {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function PedidosCab() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpPedidosCab";
        mysql_query($query);

        $query = "select distinct IDPedido,IDFactura from {$this->dbOrigen}.frecibidas_lineas";
        $result = mysql_query($query, $dbLink);
        while ($row1 = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row1['IDPedido']] = $row1['IDFactura'];

        $query = "select * from {$this->dbOrigen}.pedidos_cab";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new PedidosCab();
            $c->setIDPedido($row['IDPedido']);
            $c->setIDSucursal(1);
            $c->setIDAlmacen(1);
            $c->setIDAgente(2);
            $c->setSuPedido($row['SuPedido']);
            $c->setFecha($row['Fecha']);
            $c->setFechaEntrega($row['FechaEntrega']);
            $c->setFechaEntrada($row['FechaEntrada']);
            $c->setIDProveedor($row['IDProveedor']);
            $c->setImporte($row['Importe']);
            $c->setDescuento($row['Descuento']);
            $c->setBaseImponible1($row['BaseImponible1']);
            $c->setIva1($row['Iva1']);
            $c->setCuotaIva1($row['CuotaIva1']);
            $c->setRecargo1($row['Recargo1']);
            $c->setCuotaRecargo1($row['CuotaRecargo1']);
            $c->setBaseImponible2($row['BaseImponible2']);
            $c->setIva2($row['Iva2']);
            $c->setCuotaIva2($row['CuotaIva2']);
            $c->setRecargo2($row['Recargo2']);
            $c->setCuotaRecargo2($row['CuotaRecargo2']);
            $c->setBaseImponible3($row['BaseImponible3']);
            $c->setIva3($row['Iva3']);
            $c->setCuotaIva3($row['CuotaIva3']);
            $c->setRecargo3($row['Recargo3']);
            $c->setCuotaRecargo3($row['CuotaRecargo3']);
            $c->setTotalBases($row['TotalBases']);
            $c->setTotalIva($row['TotalIva']);
            $c->setTotalRecargo($row['TotalRecargo']);
            $c->setTotal($row['Total']);
            $c->setObservaciones($row['Observaciones']);
            $c->setIDFP($row['IDFP']);
            $c->setIDAgencia(1);
            if ($row['Recepcionado'] == 'S') {
                $c->setIDEstado(2);
                $c->setIDFactura(isset($correspondencia[$row['IDPedido']]) ? $correspondencia[$row['IDPedido']] : 0);
            } else {
                $c->setIDEstado(0);
                $c->setIDFactura(0);
            }
            $c->setPrimaryKeyMD5(md5($row['IDPedido']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Pedidos creados {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function PedidosLineas() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpPedidosLineas";
        mysql_query($query);

        $query = "select Codigo,IDArticulo from {$this->dbDestino}.ErpArticulos";
        $result = mysql_query($query, $dbLink);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row['Codigo']] = $row['IDArticulo'];

        $query = "select c.Fecha, l.* from {$this->dbOrigen}.pedidos_cab as c LEFT JOIN {$this->dbOrigen}.pedidos_lineas as l ON c.IDPedido=l.IDPedido where l.IDPedido>0";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new PedidosLineas();
            $c->setIDPedido($row['IDPedido']);
            $c->setIDLinea($row['IDLinea']);
            $c->setIDArticulo($correspondencia[$row['IDArticulo']]);
            $c->setDescripcion($row['Descripcion']);
            $c->setUnidades($row['Unidades']);
            $c->setUnidadMedida("UMC");
            $c->setPrecio($row['Precio']);
            $c->setDescuento($row['Descuento']);
            $c->setImporte($row['Importe']);
            if ($row['Fecha'] >= '2012-09-01')
                $c->setIva($this->correspondenciaIva[$row['Iva']]);
            else
                $c->setIva($row['Iva']);
            $c->setUnidadesRecibidas($row['Unidades'] - $row['PteFacturar']);
            $c->setUnidadesPtesFacturar($row['PteFacturar']);

            $c->setPrimaryKeyMD5(md5($row['IDLinea']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Líneas de Pedido creadas {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function FrecibidasCab() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpFrecibidasCab";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.frecibidas_cab";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new FrecibidasCab();
            $c->setIDFactura($row['IDFactura']);
            $c->setIDContador(4);
            $c->setNumeroFactura($row['NumeroFactura']);
            $c->setIDSucursal(1);
            $c->setSuFactura($row['SuFactura']);
            $c->setFecha($row['Fecha']);
            $c->setIDProveedor($row['IDProveedor']);
            $c->setImporte($row['Importe']);
            $c->setDescuento($row['Descuento']);
            $c->setBaseImponible1($row['BaseImponible1']);
            $c->setIva1($row['Iva1']);
            $c->setCuotaIva1($row['CuotaIva1']);
            $c->setRecargo1($row['Recargo1']);
            $c->setCuotaRecargo1($row['CuotaRecargo1']);
            $c->setBaseImponible2($row['BaseImponible2']);
            $c->setIva2($row['Iva2']);
            $c->setCuotaIva2($row['CuotaIva2']);
            $c->setRecargo2($row['Recargo2']);
            $c->setCuotaRecargo2($row['CuotaRecargo2']);
            $c->setBaseImponible3($row['BaseImponible3']);
            $c->setIva3($row['Iva3']);
            $c->setCuotaIva3($row['CuotaIva3']);
            $c->setRecargo3($row['Recargo3']);
            $c->setCuotaRecargo3($row['CuotaRecargo3']);
            $c->setTotalBases($row['TotalBases']);
            $c->setTotalIva($row['TotalIva']);
            $c->setTotalRecargo($row['TotalRecargo']);
            $c->setTotal($row['Total']);
            $c->setCuentaCompras($row['CuentaCompras']);
            $c->setObservaciones($row['Observaciones']);
            $c->setIDFP($row['IDFP']);
            $c->setPrimaryKeyMD5(md5($row['IDFactura']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Facturas recibidas creadas {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function FrecibidasLineas() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpFrecibidasLineas";
        mysql_query($query);

        $query = "select Codigo,IDArticulo from {$this->dbDestino}.ErpArticulos";
        $result = mysql_query($query, $dbLink);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row['Codigo']] = $row['IDArticulo'];

        $query = "select c.Fecha, l.* from {$this->dbOrigen}.frecibidas_cab as c LEFT JOIN {$this->dbOrigen}.frecibidas_lineas as l ON c.IDFactura=l.IDFactura where l.IDFactura>0";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new FrecibidasLineas();
            $c->setIDFactura($row['IDFactura']);
            $c->setIDLinea($row['IDLinea']);
            $c->setIDArticulo($correspondencia[$row['IDArticulo']]);
            $c->setDescripcion($row['Descripcion']);
            $c->setUnidades($row['Unidades']);
            $c->setPrecio($row['Precio']);
            $c->setDescuento($row['Descuento']);
            $c->setImporte($row['Importe']);
            if ($row['Fecha'] >= '2012-09-01')
                $c->setIva($this->correspondenciaIva[$row['Iva']]);
            else
                $c->setIva($row['Iva']);
            $c->setIDPedido($row['IDPedido']);
            $c->setIDLineaPedido($row['IDLineaPedido']);

            $c->setPrimaryKeyMD5(md5($row['IDLinea']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Líneas Factura recibidas creadas {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function FemitidasCab() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpFemitidasCab";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.femitidas_cab";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new FemitidasCab();
            $c->setIDFactura($row['IDFactura']);
            $c->setIDContador(2);
            $c->setNumeroFactura($row['NumeroFactura']);
            $c->setIDSucursal($row['IDSucursal']);
            $c->setIDAgente(2);
            $c->setIDComercial(2);
            $c->setFecha($row['Fecha']);
            $c->setIDCliente($row['IDCliente']);
            $c->setImporte($row['Importe']);
            $c->setDescuento($row['Descuento']);
            $c->setBaseImponible1($row['BaseImponible1']);
            $c->setIva1($row['Iva1']);
            $c->setCuotaIva1($row['CuotaIva1']);
            $c->setRecargo1($row['Recargo1']);
            $c->setCuotaRecargo1($row['CuotaRecargo1']);
            $c->setBaseImponible2($row['BaseImponible2']);
            $c->setIva2($row['Iva2']);
            $c->setCuotaIva2($row['CuotaIva2']);
            $c->setRecargo2($row['Recargo2']);
            $c->setCuotaRecargo2($row['CuotaRecargo2']);
            $c->setBaseImponible3($row['BaseImponible3']);
            $c->setIva3($row['Iva3']);
            $c->setCuotaIva3($row['CuotaIva3']);
            $c->setRecargo3($row['Recargo3']);
            $c->setCuotaRecargo3($row['CuotaRecargo3']);
            $c->setTotalBases($row['TotalBases']);
            $c->setTotalIva($row['TotalIva']);
            $c->setTotalRecargo($row['TotalRecargo']);
            $c->setTotal($row['Total']);
            $c->setCuentaVentas($row['CuentaVentas']);
            $c->setObservaciones($row['Observaciones']);
            $c->setPeso($row['Peso']);
            $c->setVolumen($row['Volumen']);
            $c->setBultos($row['Bultos']);
            $c->setExpedicion($row['Expedicion']);
            $c->setIDAgencia($row['IDAgencia']);
            $c->setIDFP($row['IDFP']);
            $c->setPrimaryKeyMD5(md5($row['IDFactura']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Facturas emitidas creadas {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function FemitidasLineas() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpFemitidasLineas";
        mysql_query($query);

        $query = "select Codigo,IDArticulo from {$this->dbDestino}.ErpArticulos";
        $result = mysql_query($query, $dbLink);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row['Codigo']] = $row['IDArticulo'];

        $query = "select c.Fecha, l.* from {$this->dbOrigen}.femitidas_cab as c LEFT JOIN {$this->dbOrigen}.femitidas_lineas as l ON c.IDFactura=l.IDFactura where l.IDFactura>0";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new FemitidasLineas();
            $c->setIDFactura($row['IDFactura']);
            $c->setIDLinea($row['IDLinea']);
            $c->setIDArticulo($correspondencia[$row['IDArticulo']]);
            $c->setDescripcion($row['Descripcion']);
            $c->setUnidades($row['Unidades']);
            $c->setPrecio($row['Precio']);
            $c->setDescuento($row['Descuento']);
            $c->setImporte($row['Importe']);
            $c->setImporteCosto($row['ImporteCosto']);
            $c->setIDAlbaran($row['IDAlbaran']);
            if ($row['Fecha'] >= '2012-09-01')
                $c->setIva($this->correspondenciaIva[$row['Iva']]);
            else
                $c->setIva($row['Iva']);
            $c->setIDAgente(2);
            $c->setIDComercial(2);

            $c->setPrimaryKeyMD5(md5($row['IDLinea']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Líneas Factura emitidas {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function Articulos() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpArticulos";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.articulos";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            $f = new Familias();
            $f = $f->find("Observations", $row['IDSubfamilia']);
            $idFamilia = $f->getIDFamilia();

            $c = new Articulos();
            $c->setCodigo($row['IDArticulo']);
            $c->setDescripcion($row['Descripcion']);
            $c->setIDCategoria($row['IDFamilia']);
            $c->setIDFamilia($idFamilia ? $idFamilia : 0);
            $c->setIDFabricante($row['IDFabricante']);
            $c->setPvd($row['Pvd']);
            $c->setPvp($row['Pvp']);
            $c->setMargen($row['Margen']);
            $c->setPmc($row['Pmc']);
            $c->setIDIva($row['IDIva']);
            $c->setEtiqueta($row['Etiqueta']);
            $c->setCodigoEAN($row['CodigoEAN']);
            $c->setGarantia($row['Garantia']);
            $c->setPeso($row['Peso']);
            $c->setVolumen($row['Volumen']);
            $c->setCaracteristicas($row['Caracteristicas']);
            $c->setFechaUltimoPrecio($row['FechaUltimoPrecio']);
            $c->setVigente($row['Vigente']);

            $c->setPrimaryKeyMD5(md5($row['IDArticulo']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Artículos {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function ClientesGrupos() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpClientesGrupos";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.clientes_grupos";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            $c = new ClientesGrupos();
            $c->setIDGrupo($row['IDGrupo']);
            $c->setGrupo($row['Grupo']);
            $c->setPrimaryKeyMD5(md5($row['IDGrupo']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Clientes Grupos  {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function ClientesTipos() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpClientesTipos";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.clientes_tipos";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            $c = new ClientesTipos();
            $c->setIDTipo($row['IDTipo']);
            $c->setTipo($row['Tipo']);
            $c->setPrimaryKeyMD5(md5($row['IDTipo']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Clientes Tipos  {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function Proveedores() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpProveedores";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.proveedores";
        $result = mysql_query($query, $dbLink);

        $poblaciones = new Municipios();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            //if ($row['NombreComercial'] == '')
            //    $row['NombreComercial'] = $row['RazonSocial'];

            $poblacion = $poblaciones->cargaCondicion("IDMunicipio,IDProvincia", "Municipio='{$row['Poblacion']}'");

            $c = new Proveedores();
            $c->setIDProveedor($row['IDProveedor']);
            $c->setRazonSocial(utf8_decode($row['RazonSocial']));
            $c->setNombreComercial(utf8_decode($row['NombreComercial']));
            $c->setCif($row['Cif']);
            $c->setDireccion(utf8_decode($row['Direccion']));
            $c->setIDPais(68);
            if ($poblacion[0]['IDMunicipio']) {
                $c->setIDProvincia($poblacion[0]['IDProvincia']);
                $c->setIDPoblacion($poblacion[0]['IDMunicipio']);
            } else {
                $c->setIDProvincia($row['IDProvincia']);
            }
            $c->setCodigoPostal($row['CodigoPostal']);
            $c->setTelefono($row['Telefono']);
            $c->setFax($row['Fax']);
            $c->setMovil($row['Movil']);
            $c->setEMail($row['EMail']);
            $c->setWeb($row['Web']);
            $c->setCContable($row['CContable']);
            $c->setBanco($row['IDBanco']);
            $c->setOficina($row['IDOficina']);
            $c->setDigito($row['Digito']);
            $c->setCuenta($row['Cuenta']);
            $c->setIban(Utils::iban($row['IDBanco'].$row['IDOficina'].$row['Digito'].$row['Cuenta']));
            $c->setMandato($row['IDProveedor']);
            $c->setFechaMandato('2013-01-01');            
            $c->setIDFP($row['IDFP']);
            $c->setObservaciones($row['Observaciones']);
            $c->setPrimaryKeyMD5(md5($row['IDProveedor']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Proveedores {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function Familias() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpFamilias";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.familias";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            $c = new Familias();
            $c->setIDFamilia($row['IDFamilia']);
            $c->setFamilia($row['Familia']);
            $c->setInventario(($row['Inventario'] == 'S') ? 1 : 0 );
            $c->setTrazabilidad(($row['ConNumeroSerie'] == 'S') ? 1 : 0 );
            $c->setMargenWeb($row['MargenWeb']);
            $c->setMargenMinimo($row['Minimo']);
            $c->setMostrarEnTpv(($row['Ticket'] == 'S') ? 1 : 0 );
            $c->setPublish(($row['PublicarWeb'] == 'S') ? 1 : 0 );
            $c->setPrimaryKeyMD5(md5($row['IDFamilia']));

            if ($c->create() == NULL) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Familias  {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function Subfamilias() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        //$query = "TRUNCATE {$this->dbDestino}.ErpFamilias";
        //mysql_query($query);

        $query = "select * from {$this->dbOrigen}.subfamilias";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            $f = new Familias($row['IDFamilia']);

            $c = new Familias();
            $c->setFamilia($row['Subfamilia']);
            $c->setInventario($f->getInventario()->getIDTipo());
            $c->setTrazabilidad($f->getTrazabilidad()->getIDTipo());
            $c->setMargenWeb($f->getMargenWeb());
            $c->setMargenMinimo($f->getMargenMinimo());
            $c->setMostrarEnTpv($f->getMostrarEnTpv()->getIDTipo());
            $c->setPublish(($row['PublicarWeb'] == 'S') ? 1 : 0 );
            $c->setBelongsTo($f->getIDFamilia());
            $c->setObservations($row['IDSubfamilia']);

            $id = $c->create();
            if ($id == NULL) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else {
                $c->setIDFamilia($id);
                $c->setPrimaryKeyMD5(md5($id));
                $c->save();
                $nItems++;
            }
        }

        //mysql_close($dbLink);

        echo "Subfamilias  {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function Fabricantes() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpFabricantes";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.fabricantes";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            $c = new Fabricantes();
            $c->setIDFabricante($row['IDFabricante']);
            $c->setTitulo($row['Fabricante']);
            $c->setTelefono($row['Telefono']);
            $c->setWeb($row['Web']);
            $c->setEmail($row['EMail']);
            $c->setPrimaryKeyMD5(md5($row['IDFabricante']));

            if ($c->create() == NULL) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Fabricantes {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function FormasPago() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpFormasPago";
        mysql_query($query);

        $query = "SELECT * FROM {$this->dbOrigen}.formas_pago";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            $c = new FormasPago();
            $c->setIDFP($row['IDFP']);
            $c->setDescripcion($row['Descripcion']);
            $c->setNumeroVctos($row['NumeroVctos']);
            $c->setDiaPrimerVcto($row['DiaPrimerVcto']);
            $c->setDiasIntervalo($row['DiasIntervalo']);
            $c->setPrimaryKeyMD5(md5($row['IDFP']));

            if ($c->create() == NULL) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Formas de pago {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function Clientes() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpClientes";
        mysql_query($query);
        $query = "TRUNCATE {$this->dbDestino}.ErpClientesDentrega";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.clientes";
        $result = mysql_query($query, $dbLink);

        $poblaciones = new Municipios();

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $row = $this->utf($row);

            //if ($row['NombreComercial'] == '')
            //    $row['NombreComercial'] = $row['RazonSocial'];

            $poblacion = $poblaciones->cargaCondicion("IDMunicipio,IDProvincia", "Municipio='{$row['Poblacion']}'");

            $c = new Clientes();
            $c->setIDCliente($row['IDCliente']);
            $c->setRazonSocial(utf8_decode($row['RazonSocial']));
            $c->setNombreComercial(utf8_decode($row['NombreComercial']));
            $c->setCif($row['Cif']);
            $c->setDireccion(utf8_decode($row['Direccion']));
            $c->setIDPais(68);
            if ($poblacion[0]['IDMunicipio']) {
                $c->setIDProvincia($poblacion[0]['IDProvincia']);
                $c->setIDPoblacion($poblacion[0]['IDMunicipio']);
            } else {
                $c->setIDProvincia($row['IDProvincia']);
                $row['Avisos'] = $row['Poblacion'];
            }
            $c->setCodigoPostal($row['CodigoPostal']);
            $c->setTelefono($row['Telefono']);
            $c->setFax($row['Fax']);
            $c->setMovil($row['Movil']);
            $c->setEMail($row['EMail']);
            $c->setWeb($row['Web']);
            $c->setCContable($row['CContable']);
            $c->setBanco($row['IDBanco']);
            $c->setOficina($row['IDOficina']);
            $c->setDigito($row['Digito']);
            $c->setCuenta($row['Cuenta']);
            $c->setIban(Utils::iban($row['IDBanco'].$row['IDOficina'].$row['Digito'].$row['Cuenta']));
            $c->setMandato($row['IDCliente']);
            $c->setFechaMandato('2013-01-01');
            $c->setIDTipo($row['IDTipo']);
            $c->setIDGrupo($row['IDGrupo']);
            $c->setIDFP($row['IDFP']);
            $c->setDiaDePago($row['DiaDePago']);
            $c->setRecargoEqu($row['RecargoEqu']);
            $c->setIDTarifa(1);
            $c->setIDZona(1);
            $c->setObservaciones($row['Observaciones']);
            $c->setAvisos($row['Avisos']);
            $c->setVigente($row['Vigente']);
            $c->setIDComercial($this->agentes[$row['IDAgente']]);
            $c->setLimiteRiesgo($row['LimiteRiesgo']);
            $c->setFechaNacimiento($row['FechaNacimiento']);
            $c->setPrimaryKeyMD5(md5($row['IDCliente']));

            $c->setIDZona(1);

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Clientes {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function PstoCab() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpPstoCab";
        mysql_query($query);

        $query = "select * from {$this->dbOrigen}.psto_cab";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new PstoCab();
            $c->setIDPsto($row['IDPsto']);
            $c->setIDContador(1);
            //$c->setNumeroPsto($row['IDPsto']);
            $c->setIDSucursal($row['IDSucursal']);
            $c->setIDAlmacen(1);
            $c->setIDAgente(2);
            $c->setIDComercial(2);
            $c->setFecha($row['Fecha']);
            $c->setIDCliente($row['IDCliente']);

            $c->setIDDirec(0);

            $c->setImporte($row['Importe']);
            $c->setDescuento($row['Descuento']);
            if ($this->dbOrigen != 'albatro_gestion001') {
                $c->setBaseImponible1($row['BaseImponible1']);
                $c->setIva1($row['Iva1']);
                $c->setCuotaIva1($row['CuotaIva1']);
                $c->setRecargo1($row['Recargo1']);
                $c->setCuotaRecargo1($row['CuotaRecargo1']);
                $c->setBaseImponible2($row['BaseImponible2']);
                $c->setIva2($row['Iva2']);
                $c->setCuotaIva2($row['CuotaIva2']);
                $c->setRecargo2($row['Recargo2']);
                $c->setCuotaRecargo2($row['CuotaRecargo2']);
                $c->setBaseImponible3($row['BaseImponible3']);
                $c->setIva3($row['Iva3']);
                $c->setCuotaIva3($row['CuotaIva3']);
                $c->setRecargo3($row['Recargo3']);
                $c->setCuotaRecargo3($row['CuotaRecargo3']);
                $c->setTotalBases($row['TotalBases']);
                $c->setTotalIva($row['TotalIva']);
                $c->setTotalRecargo($row['TotalRecargo']);
            } else {
                $c->setBaseImponible1($row['BaseImponible']);
                $c->setIva1($row['Iva']);
                $c->setCuotaIva1($row['CuotaIva']);
                $c->setRecargo1($row['Recargo']);
                $c->setCuotaRecargo1($row['CuotaRecargo']);
                $c->setTotalBases($row['BaseImponible']);
                $c->setTotalIva($row['CuotaIva']);
                $c->setTotalRecargo($row['CuotaRecargo']);
            }
            $c->setTotal($row['Total']);
            $c->setIDEstado($row['Estado']);
            $c->setObservaciones($row['Observaciones']);
            $c->setIDAgencia($row['IDAgencia']);
            $c->setIDFP($row['IDFP']);
            $c->setPrimaryKeyMD5(md5($row['IDPsto']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Presupuestos {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function PstoLineas() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpPstoLineas";
        mysql_query($query);

        $query = "select Codigo,IDArticulo from {$this->dbDestino}.ErpArticulos";
        $result = mysql_query($query, $dbLink);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row['Codigo']] = $row['IDArticulo'];

        $query = "select l.*, c.Estado, c.Fecha from {$this->dbOrigen}.psto_lineas as l LEFT JOIN {$this->dbOrigen}.psto_cab as c ON l.IDPsto=c.IDPsto where c.IDPsto>0";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new PstoLineas();
            $c->setIDPsto($row['IDPsto']);
            $c->setIDLinea($row['IDLinea']);
            $c->setIDArticulo($correspondencia[$row['IDArticulo']]);
            $c->setDescripcion($row['Descripcion']);
            $c->setUnidades($row['Unidades']);
            $c->setPrecio($row['Precio']);
            $c->setDescuento($row['Descuento']);
            $c->setImporte($row['Importe']);
            $c->setImporteCosto($row['ImporteCosto']);
            $c->setIDAlmacen(1);
            if ($row['Fecha'] >= '2012-09-01')
                $c->setIva($this->correspondenciaIva[$row['Iva']]);
            else
                $c->setIva($row['Iva']);
            $c->setIDAgente(2);
            $c->setIDComercial(2);

            $c->setIDEstado($row['Estado']);

            $c->setPrimaryKeyMD5(md5($row['IDLinea']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Líneas Presupuestos {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function AlbaranesCab() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpAlbaranesCab";
        mysql_query($query);

        // Correspondencia entre número de factura e id de factura
        $query = "SELECT a.IDFactura as Numero, f.IDFactura as Id FROM {$this->dbOrigen}.albaranes_cab AS a LEFT JOIN {$this->dbOrigen}.femitidas_cab AS f ON a.IDFactura = f.NumeroFactura ORDER BY IDAlbaran";
        $result = mysql_query($query, $dbLink);
        while ($row1 = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row1['Numero']] = $row1['Id'];

        $query = "select * from {$this->dbOrigen}.albaranes_cab";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new AlbaranesCab();
            $c->setIDAlbaran($row['IDAlbaran']);
            $c->setIDContador(2);
            $c->setNumeroAlbaran($row['IDAlbaran']);
            $c->setIDSucursal($row['IDSucursal']);
            $c->setIDAlmacen(1);
            $c->setIDAgente($this->agentes[$row['IDAgente']]);
            $c->setIDComercial($this->agentes[$row['IDAgente']]);
            $c->setFecha($row['Fecha']);
            $c->setFechaEntrega($row['FechaEntrega']);
            $c->setIDCliente($row['IDCliente']);

            $c->setIDDirec(0);

            $c->setImporte($row['Importe']);
            $c->setDescuento($row['Descuento']);
            $c->setBaseImponible1($row['BaseImponible1']);
            $c->setIva1($row['Iva1']);
            $c->setCuotaIva1($row['CuotaIva1']);
            $c->setRecargo1($row['Recargo1']);
            $c->setCuotaRecargo1($row['CuotaRecargo1']);
            $c->setBaseImponible2($row['BaseImponible2']);
            $c->setIva2($row['Iva2']);
            $c->setCuotaIva2($row['CuotaIva2']);
            $c->setRecargo2($row['Recargo2']);
            $c->setCuotaRecargo2($row['CuotaRecargo2']);
            $c->setBaseImponible3($row['BaseImponible3']);
            $c->setIva3($row['Iva3']);
            $c->setCuotaIva3($row['CuotaIva3']);
            $c->setRecargo3($row['Recargo3']);
            $c->setCuotaRecargo3($row['CuotaRecargo3']);
            $c->setTotalBases($row['TotalBases']);
            $c->setTotalIva($row['TotalIva']);
            $c->setTotalRecargo($row['TotalRecargo']);

            $c->setTotal($row['Total']);
            if ($row['Expedido'] == 0)
                $c->setIDEstado(0);
            elseif ($row['IDFactura'])
                $c->setIDEstado(3);
            else
                $c->setIDEstado(2);

            if (isset($correspondencia[$row['IDFactura']]))
                $c->setIDFactura($correspondencia[$row['IDFactura']]);
            else
                $c->setIDFactura(0);

            $c->setObservaciones($row['Observaciones']);
            $c->setPeso($row['Peso']);
            $c->setVolumen($row['Volumen']);
            $c->setBultos($row['Bultos']);
            $c->setExpedicion($row['Expedicion']);
            $c->setIDAgencia($row['IDAgencia']);
            $c->setIDFP($row['IDFP']);
            $c->setPrimaryKeyMD5(md5($row['IDAlbaran']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Albaranes {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    public function AlbaranesLineas() {

        $nItems = 0;
        $nErrores = 0;

        $dbLink = mysql_connect("localhost", "root", "albatronic");

        $query = "TRUNCATE {$this->dbDestino}.ErpAlbaranesLineas";
        mysql_query($query);

        $query = "select Codigo,IDArticulo from {$this->dbDestino}.ErpArticulos";
        $result = mysql_query($query, $dbLink);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
            $correspondencia[$row['Codigo']] = $row['IDArticulo'];

        $query = "select l.*, c.IDAgente, c.Expedido, c.IDFactura, c.Fecha from {$this->dbOrigen}.albaranes_lineas as l LEFT JOIN {$this->dbOrigen}.albaranes_cab as c ON l.IDAlbaran=c.IDAlbaran where c.IDAlbaran>0";
        $result = mysql_query($query, $dbLink);

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $c = new AlbaranesLineas();
            $c->setIDAlbaran($row['IDAlbaran']);
            $c->setIDLinea($row['IDLinea']);
            $c->setIDArticulo($correspondencia[$row['IDArticulo']]);
            $c->setDescripcion($row['Descripcion']);
            $c->setUnidades($row['Unidades']);
            $c->setUnidadMedida("UMV");
            $c->setPvpVigente($row['Precio']);
            $c->setPrecio($row['Precio']);
            $c->setDescuento($row['Descuento']);
            $c->setImporte($row['Importe']);
            $c->setImporteCosto($row['ImporteCosto']);
            $c->setIDAlmacen(1);
            if ($row['Fecha'] >= '2012-09-01')
                $c->setIva($this->correspondenciaIva[$row['Iva']]);
            else
                $c->setIva($row['Iva']);
            $c->setIDAgente($this->agentes[$row['IDAgente']]);
            $c->setIDComercial($this->agentes[$row['IDAgente']]);
            if ($row['Expedido'] == 0)
                $c->setIDEstado(0);
            elseif ($row['IDFactura'])
                $c->setIDEstado(3);
            else
                $c->setIDEstado(2);

            $c->setPrimaryKeyMD5(md5($row['IDLinea']));

            if (!$c->create()) {
                $errores[] = $c->getErrores();
                $nErrores++;
            } else
                $nItems++;
        }

        //mysql_close($dbLink);

        echo "Líneas Albaranes {$nItems}<br/>";
        if (count($errores)) {
            echo "<pre>";
            print_r($errores);
            echo "</pre>";
        }
    }

    private function utf($row) {
        foreach ($row as $key => $value)
            $row[$key] = utf8_encode($value);

        return $row;
    }

}

?>
