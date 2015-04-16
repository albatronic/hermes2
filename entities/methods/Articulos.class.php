<?php

/**
 * Description of Articulos
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class Articulos extends ArticulosEntity {

    public function __toString() {
        return $this->getDescripcion();
    }

    /**
     * Devuelve un array con todos los articulos de la familia indicada, sujetos a trazabilidad e inventario
     * Cada elemento tiene la primarykey y el valor de $column
     *
     * @param integer $idFamilia El id de familia (opcional)
     * @param integer $trazabilidad Flag trazabilidad (por defecto 1 => Si)
     * @param integer $inventario Flag control inventario ( por defecto 1 => Si)
     * @param string $colum La columna para rellenar el value del array
     * @return array Array de artículos
     */
    public function fetchAll($idFamilia = '', $trazabilidad = '1', $inventario = '1', $column = 'Descripcion') {
        $this->conecta();

        if (is_resource($this->_dbLink)) {
            $filtro = "WHERE (Vigente='1') AND (Trazabilidad='{$trazabilidad}') AND (Inventario='{$inventario}')";
            if ($idFamilia != '')
                $filtro .= " AND (IDFamilia='{$idFamilia}')";
            $query = "SELECT IDArticulo as Id,$column as Value FROM {$this->getDataBaseName()}.{$this->getTableName()} $filtro ORDER BY $column ASC;";
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
            $this->_em->desConecta();
            unset($this->_em);
        }
        $rows[] = array('Id' => '', Value => ':: Articulo');
        return $rows;
    }

    /**
     * Pongo el precio medio de compra igual al precio de costo y
     * Aplico las reglas de ordenacion si está vigente
     * 
     * @return integer El id del ultimo articulo creado
     */
    public function create() {

        $this->Pmc = $this->Pvd;

        if (trim($this->CodigoEAN == ''))
            $this->CodigoEAN = $this->Codigo;
        if (trim($this->Subtitulo == ''))
            $this->Subtitulo = $this->Descripcion;
        if (trim($this->Resumen == ''))
            $this->Resumen = $this->Descripcion;
        if (trim($this->Etiqueta == ''))
            $this->Etiqueta = substr($this->Descripcion, 0, 29);

        $id = parent::create();
        if (($id) and ( $this->Vigente == '1')) {
            $reglas = new Escaparates();
            $reglas->aplicaReglasArticulo($id);
            unset($reglas);
        }
        return $id;
    }

    /**
     * Guardo y aplico las reglas de ordenación si está vigente
     * 
     * @return boolean
     */
    public function save() {

        $reglas = new Escaparates();
        // reglas antes de guardar
        $reglasAntes = $reglas->getReglasArticulo($this->IDArticulo);

        $ok = parent::save();
        if ($ok) {
            // Se recalculan las reglas solo en el caso en que haya algún cambio.
            // Para ello comparo las reglas del artículo antes y despues de guardar.
            $reglasNuevas = $reglas->getReglasArticulo($this->IDArticulo);
            $diferenciaReglas1 = array_diff($reglasAntes, $reglasNuevas);
            $diferenciaReglas2 = array_diff($reglasNuevas, $reglasAntes);

            if ((count($diferenciaReglas1) + count($diferenciaReglas2)) > 0) {

                // Borro los eventuales ordenes que existieran para el artículo
                $ordenes = new OrdenesArticulos();

                $ordenes->borraOrdenesArticulo($this->IDArticulo);
                unset($ordenes);
                // Aplico las reglas de ordenes 
                if ($this->Vigente == '1') {
                    $reglas = new Escaparates();
                    $reglas->aplicaReglasArticulo($this->IDArticulo);
                    unset($reglas);
                }
            }

            // Borro el eventual escandallo
            if ($this->AllowsChildren == '0') {
                $escan = new ArticulosEscandallos();
                $escan->queryDelete("IDArticuloOrigen='{$this->IDArticulo}'");
                unset($escan);
            }
        }
        return $ok;
    }

    /**
     * Marco de borrado el artículo y borro sus eventuales órdenes.
     * 
     * Cuando borramos un artículo cambiamos el código para poder reutilizarlo.
     * De forma que si el código era 043, ahora se llama deleted043,
     * y entonces podemos volver a dar de alta otro artículo con código 043. 
     * 
     * @return boolean
     */
    public function delete() {

        $ok = $this->validaBorrado();

        if ($ok) {
            $id = $this->IDArticulo;

            $this->setCodigo("deleted_{$this->IDArticulo}_{$this->Codigo}");
            $this->save();

            $ok = parent::delete();

            if ($ok) {
                // Borro los eventuales ordenes que existieran para el artículo
                $ordenes = new OrdenesArticulos();
                $ordenes->borraOrdenesArticulo($id);
                unset($ordenes);

                // Borro las eventuales propiedades
                $propiedades = new ArticulosPropiedades();
                $propiedades->queryDelete("IDArticulo='{$id}'");
                unset($propiedades);

                // Borro el eventual escandallo
                if ($this->AllowsChildren == '0') {
                    $escan = new ArticulosEscandallos();
                    $escan->queryDelete("IDArticuloOrigen='{$id}'");
                    unset($escan);
                }
            }
        }

        return $ok;
    }

    /**
     * Si hay stock no se puede cambiar el estado de inventario ni las unidades de medida
     * Si las unidades de media son iguales, los factores de conversión los pongo a 1
     * Calculo el precio de venta sobre EL PRECIO MEDIO DE COSTO
     * Si columna 'etiqueta' está vacía, le pongo valor de la descripción del artículo
     */
    protected function validaLogico() {

        parent::validaLogico();

        // Si hay stock no se puede cambiar:
        // - ni el estado de inventario
        // - ni las unidades de medida
        // - ni si es compuesto o no
        $exi = new Existencias();

        if ($exi->hayRegistroExistencias($this->IDArticulo)) {
            // Hay registro de existencias
            $articulo = new Articulos($this->IDArticulo);
            $this->setInventario($articulo->getInventario()->getIDTipo());
            $this->setUMA($articulo->getUMA()->getId());
            $this->setUMC($articulo->getUMC()->getId());
            $this->setUMV($articulo->getUMV()->getId());
            $this->setAllowsChildren($articulo->getAllowsChildren()->getIDTipo());
            unset($articulo);
            $this->_alertas[] = "Hay Stock, no puede cambiar ni el estado de Inventario ni las Unidades de Medida, ni si es compuesto";
        } else {
            // Si no es inventariable, pongo los valores relativos
            // al inventario a 0.
            if ($this->Inventario == 0) {
                $this->Trazabilidad = 0;
                $this->BloqueoStock = 0;
                $this->StockMinimo = 0;
                $this->StockMaximo = 0;
                $this->Caducidad = 0;
            }

            // Si las unidades de media son iguales, los factores de conversión los pongo a 1
            if ($this->UMC == $this->UMB)
                $this->CUMC = 1;
            if ($this->UMA == $this->UMB)
                $this->CUMA = 1;
            if ($this->UMV == $this->UMB)
                $this->CUMV = 1;
        }

        // Calculo el precio de venta sobre EL PRECIO MEDIO DE COSTO
        if ($this->Pmc == 0)
            $this->Pmc = $this->Pvd;
        if ($this->Pmc != 0)
            $this->Pvp = $this->Pmc * ( 1 + $this->Margen / 100);

        // Pongo valores por defecto
        if (trim($this->CodigoEAN == ''))
            $this->CodigoEAN = $this->Codigo;
        if (trim($this->Subtitulo == ''))
            $this->Subtitulo = $this->Descripcion;
        if (trim($this->Resumen == ''))
            $this->Resumen = $this->Descripcion;
        if (trim($this->Etiqueta == ''))
            $this->Etiqueta = substr($this->Descripcion, 0, 29);

        // Valido que no se dupliquen los estados
        $valida = array();
        for ($i = 1; $i <= 5; $i++) {
            $idEstado = $this->{"IDEstado$i"};
            if (isset($valida[$idEstado]))
                $this->{"IDEstado$i"} = 0;
            else
                $valida[$idEstado] = '1';
        }

        // Si no está vigente, entonces tampoco se publica
        if (!$this->Vigente)
            $this->Publish = 0;

        unset($exi);
    }

    /**
     * Devuelve el PVP SIN impuestos correspondiente a la unidad de medida indicada.
     * Por defecto la Unidad de Venta.
     *
     * Devuelve el importe redondeado a $decimales cifras decimales (por defecto 2)
     *
     * @param string La Unidad de Medida.Por defecto la de venta
     * @param integer El número de decimales
     * @return decimal El PVP sin impuestos.
     */
    public function getPrecioVenta($um = 'UMV', $decimales = 2) {

        $um = strtoupper($um);

        $pvp = $this->Pvp * $this->{"getC$um"}();

        return round($pvp, $decimales);
    }

    /**
     * Devuelve el precio de venta mínimo calculado en base
     * al Precio Medio de costo aplicándole el porcentaje de margén mínimo
     * indicado en la familia a la que pertenece este artículo
     *
     * @param string $um La unidad de medida en la que se expresará el precio
     * @param integer $decimales El número de decimales con los que se expresará el precio
     * @return decimal El precio de venta minimo
     */
    public function getPrecioMinimoVenta($um = 'UMV', $decimales = 2) {

        $um = strtoupper($um);

        $familia = new Familias($this->getIDFamilia()->getIDFamilia());
        $margenMinimo = $familia->getMargenMinimo();
        unset($familia);

        $pvpMinimo = $this->Pmc * ( 1 + $margenMinimo / 100);
        $pvpMinimo = $pvpMinimo * $this->{"getC$um"}();

        return round($pvpMinimo, $decimales);
    }

    /**
     * Devuelve el PVP CON impuestos correspondiente a la unidad de medida indicada.
     * Por defecto la Unidad de Venta.
     *
     * Devuelve el importe redondeado a $decimales cifras decimales (por defecto 2)
     *
     * @param string La Unidad de Medida.Por defecto la de venta
     * @param integer El número de decimales
     * @return decimal El PVP con impuestos.
     */
    public function getPrecioVentaConImpuestos($um = 'UMV', $decimales = 2) {

        $um = strtoupper($um);

        $pvp = $this->Pvp * $this->{"getC$um"}() * (1 + $this->getIDIva()->getIva() / 100);

        return round($pvp, $decimales);
    }

    /**
     * Devuelve el PVP para la web con o sin iva incluido
     * según la variable de entorno de proyecto shop.ivaIncluido
     * 
     * @param string La Unidad de Medida.Por defecto la de venta
     * @param integer El número de decimales
     * @return decimal El PVP.
     */
    public function getPrecioWeb($um = 'UMV', $decimales = 2) {
        
        $pvp = ($_SESSION['varEnv']['Pro']['shop']['ivaIncluido'] === '1') ?
            $this->getPrecioVentaConImpuestos($um, $decimales) :
            $this->getPrecioVenta($um, $decimales);

        return $pvp;
    }
    
    /**
     * Devuelve el Pmc sin impuestos correspondiente a la unidad de medida indicada.
     * Por defecto la Unidad de Compra.
     *
     * @param string La Unidad de Medida. Por defecto la de compra
     * @return float El Precio Medio de Compra sin impuestos.
     */
    public function getPrecioCosto($um = 'UMC') {

        $um = strtoupper($um);

        return $this->Pmc * $this->{"getC$um"}();
    }

    /**
     * Recibe un objeto AlbaranCab o PstoCab y devuelve un array
     * con siete elementos:
     *
     *  [Promocion] -> El objeto promocion (si hay)
     *  [Promo][Precio] -> El precio de la promocion
     *  [Promo][Descuento] -> El descuento de la promocion
     *  [Promo][PrecioConImpuestos] -> El precio de la promocion con Impuestos
     *  [Tarifa][Precio] -> El precio de la tarifa
     *  [Tarifa][Descuento] -> El descuento de la tarifa
     *  [Tarifa][PrecioConImpuestos] -> El de la tarifa con impuestos
     *
     * Para ello consulta promociones vigentes para el artículo, cliente
     * y si no hay, aplica la tarifa asociada al cliente.
     *
     * @param objeto $objetoVenta Un objeto albaran o presupuesto
     * @return array Array con el objeto promocion, el precio y el descuento de la tarifa y de la promocion
     */
    public function cotizar($objetoVenta, $unidades) {
        $precios = array();

        // ---------------------------------------------------------------------
        // Coger el precio de tarifa
        // ---------------------------------------------------------------------
        $tarifa = $objetoVenta->getIDCliente()->getIDTarifa();
        if ($tarifa->getTipo()->getIDTipo() == '0') {
            //Descuento sobre el PVP
            $precios['Tarifa']['Precio'] = $this->getPrecioVenta();
            $precios['Tarifa']['Descuento'] = $tarifa->getValor();
        } else {
            //Margen sobre el PVD
            $precios['Tarifa']['Precio'] = $this->getPrecioCosto() * ( 1 + $tarifa->getValor() / 100 );
            $precios['Tarifa']['Descuento'] = 0;
        }
        $precios['Tarifa']['PrecioConImpuestos'] = $precios['Tarifa']['Precio'] * (1 + $this->getIDIva()->getIva() / 100);

        // -------------------------------------------------------------------------
        //  Buscar promociones. En caso de haber promo para artículo y para familia,
        //  prevalecen los promos a nivel de artículo sobre
        //  las promos a nivel de familia 
        // -------------------------------------------------------------------------
        $promocion = new Promociones();
        $filtro = "( 
                (IDArticulo='{$this->getIDArticulo()}') or 
                (IDFamilia='{$this->getIDFamilia()->getIDFamilia()}')
            ) AND (CantidadMinima<='{$unidades}')
              AND (FinPromocion>='{$objetoVenta->getDate()}') 
              AND (IDFP='{$objetoVenta->getIDFP()->getIDFP()}')";
        $promociones = $promocion->cargaCondicion("*", $filtro, "IDArticulo DESC");

        if (!count($promociones)) {
            $filtro = "( 
                (IDArticulo='{$this->getIDArticulo()}') or 
                (IDFamilia='{$this->getIDFamilia()->getIDFamilia()}')
            ) AND (CantidadMinima<='{$unidades}')
              AND (FinPromocion>='{$objetoVenta->getDate()}') 
              AND (IDFP='0')";
            $promociones = $promocion->cargaCondicion("*", $filtro, "IDArticulo DESC");
        }

        $hayPromo = false;
        if (count($promociones)) {
            // Recorro las promos-clientes a ver si hay alguna especifica para el cliente
            while ((!$hayPromo) and ( list(, $promocion) = each($promociones))) {

                // El articulo está en promocion, ver si aplica al cliente
                $promoCliente = new PromocionesClientes();
                $filtro = "IDPromocion='{$promocion['IDPromocion']}' and (IDCliente='{$objetoVenta->getIDCliente()->getIDCliente()}')";
                $promoClientes = $promoCliente->cargaCondicion("Id,IdPromocion", $filtro);
                if ($promoClientes[0]['Id'] != '') {
                    // La promo aplica
                    $hayPromo = true;
                    $promocion = new Promociones($promoClientes[0]['IdPromocion']);
                }
            }

            if (!$hayPromo) {
                // Recorro los promos-clientes a ver si hay alguna para el grupo de clientes
                reset($promociones);
                while ((!$hayPromo) and ( list(, $promocion) = each($promociones))) {
                    $promoCliente = new PromocionesClientes();
                    $filtro = "IDPromocion='{$promocion['IDPromocion']}' and (IDGrupo='{$objetoVenta->getIDCliente()->getIDGrupo()->getIDGrupo()}')";
                    $promoClientes = $promoCliente->cargaCondicion("Id,IdPromocion", $filtro);
                    if ($promoClientes[0]['Id'] != '') {
                        // La promo aplica
                        $hayPromo = true;
                        $promocion = new Promociones($promoClientes[0]['IdPromocion']);
                    }
                }
                unset($promoCliente);
            }
        }

        if ($hayPromo) {
            // Hay promo, coger precios de promo
            $precios['Promocion'] = $promocion;
            switch ($promocion->getTipoPromocion()->getIDTipo()) {
                case '0': //Promocion de descuento en PVP
                    $precios['Promo']['Precio'] = $this->getPrecioVenta();
                    $precios['Promo']['Descuento'] = $promocion->getValor();
                    break;
                case '1': //Promocion en Margen sobre PVD
                    $precios['Promo']['Precio'] = $this->getPrecioCosto() * ( 1 + $promocion->getValor() / 100 );
                    $precios['Promo']['Descuento'] = 0;
                    break;
                case '2': //Promocion en precio neto
                    $precios['Promo']['Precio'] = $promocion->getValor();
                    $precios['Promo']['Descuento'] = 0;
                    break;
            }
            $precios['Promo']['PrecioConImpuestos'] = $precios['Promo']['Precio'] * (1 + $this->getIDIva()->getIva() / 100);
        }

        unset($promocion);
        return $precios;
    }

    /**
     * Recalcula y actualiza:
     * 
     *  1) el precio medio de compra (Pmc)
     *  2) el precio de compra (Pvd)
     *  3) el margen o el pvp dependiendo del valor del parámetro ACTU_PRECIOS, en cualquier caso
     *     estos valores se calculan respecto el precio medio de compra
     *  4) la fecha del último precio
     *
     * NOTA: Este método sobreentiende que primero se han acutalizado las existencias
     * por lo tanto las existencias anteriores son las actuales menos las compradas.
     *
     * La fórmula que se utiliza es:
     *
     *   nuevoPmc = ( (existenciasAnteriores * PMC) + (unidadesCompradas * ultimoPrecioCompra) ) / (existenciasAnteriores + unidadesCompradas)
     *
     * @param float $unidadesCompradas La unidades compradas expresadas en la unidad de medida de compra
     * @param float $ultimoPrecioCompra El último precio de compra expresado en la unidad de medida de compra
     * @return boolean
     */
    public function actualizaPrecios($unidadesCompradas, $ultimoPrecioCompra) {

        $exi = new Existencias();
        $stock = $exi->getStock($this->IDArticulo, '', '', '', '', 'UMC');
        unset($exi);

        $existenciasAnteriores = ( $stock['RE'] - $unidadesCompradas );

        $pmcUnidadCompra = $this->Pmc * $this->CUMC;

        if (($existenciasAnteriores + $unidadesCompradas) != 0) {
            $nuevoPmc = ( ($existenciasAnteriores * $pmcUnidadCompra) + ($unidadesCompradas * $ultimoPrecioCompra) ) / ($existenciasAnteriores + $unidadesCompradas);
            $nuevoPmc = $nuevoPmc / $this->CUMC;
            $this->setPmc($nuevoPmc);
        }

        // Leo el parametro 'ACTU_PRECIOS' para ver el comportamiento a seguir
        // en el cambio de precio de venta o margen. Si no estuviera definido,
        // se respeta el PVP a costa del MARGEN
        $parametro = $_SESSION['usuarioPortal']['actuPrecios'];
        if (($parametro != 'MARGEN') and ( $parametro != 'PVP'))
            $parametro = 'MARGEN';

        // Calculo el nuevo margen o el nuevo precio de venta (según el parámetro) sobre el PRECIO MEDIO DE COSTO
        if ($this->Pmc == 0)
            $this->Pmc = $this->Pvd;

        if ($parametro == 'PVP')
            $this->Pvp = $this->Pmc * ( 1 + $this->Margen / 100);
        else {
            if ($this->Pmc != 0)
                $this->Margen = 100 * ($this->Pvp / $this->Pmc - 1);
            else
                $this->Margen = 0;
        }



        $this->setPvd($ultimoPrecioCompra / $this->CUMC);
        $this->setFechaUltimoPrecio(date('Y-m-d H:i:s'));

        return $this->save();
    }

    /**
     * Transforma una cantidad expresada en la unidad de medida origen a
     * otra cantidad expresada en la unidad de medida destino redondeada
     * a los dígitos de precisión indicados (por defecto 3 dígitos decimales)
     *
     * @param <type> $unidadMediaOrigen Valores Posibles (UMC,UMV,UMA)
     * @param <type> $unidadMedidaDestino  Valores Posibles (UMC,UMV,UMA)
     * @param float $cantidad Cantidad a transformar
     * @param integer Digitos de precision del redondeo (defecto = 3)
     * @return float La cantidad transformada
     */
    public function convertUnit($unidadMedidaOrigen, $unidadMedidaDestino, $cantidad, $precision = 3) {

        $unidadMedidaOrigen = strtoupper($unidadMedidaOrigen);
        $unidadMedidaDestino = strtoupper($unidadMedidaDestino);

        if ($unidadMedidaOrigen == $unidadMedidaDestino)
            return $cantidad;
        else {
            $cantidadUnidadMedidaBasica = $cantidad * $this->{"getC$unidadMedidaOrigen"}();
            $cantidadUnidadMedidaDestino = $cantidadUnidadMedidaBasica / $this->{"getC$unidadMedidaDestino"}();
            return round($cantidadUnidadMedidaDestino, $precision);
        }
    }

    /**
     * Devuelve el literal correspondiente al tipo de unidad de medida
     * $tipoUm del artículo
     *
     * @param string $tipoUm Tipo de unidad de medida (UMB,UMC,UMV,UMA)
     * @return string
     */
    public function getUnidadMedida($tipoUm) {

        $tipoUm = strtoupper($tipoUm);

        return $this->{"get$tipoUm"}();
    }

    /**
     * Devuelve el factor de conversión a unidad de medida básica
     * que siempre es 1
     *
     * @return int El factor de conversión
     */
    public function getCUMB() {
        return 1;
    }

    /**
     * Devuelve un array con las ubicaciones del $idAlmacen
     * donde hay existencias del artículo y le añade al final
     * el resto de ubicaciones.
     *
     * Si el parámetro $todas se pone al false, solo devuelve las ubicaciones
     * donde haya existencias.
     *
     * Se utiliza principalmente en los procesos de recepción de mercancía
     * (recepción de pedidos y de elaboraciones)
     *
     * @param integer $idAlmacen El id de almacén
     * @param string $filtroUbicacion Filtro de la ubicación (defecto %)
     * @param boolean $todas
     * @return array Las ubicaciones array('Id' => IDUbicacion, 'Value' => Ubicacion, 'Reales' => Existencias Reales)
     */
    public function getUbicaciones($idAlmacen, $filtroUbicacion = "%", $todas = TRUE) {
        $ubicaciones = array();

        $mapa = new AlmacenesMapas();
        $existencias = new Existencias();

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            //Ubicaciones donde haya existencias.
            //$query = "Call UbicacionesArticulo({$idAlmacen},{$this->IDArticulo});";
            $query = "SELECT DISTINCT e.IDUbicacion AS Id, m.Ubicacion AS Value, e.Reales as Reales
                        FROM
                            {$existencias->getDataBaseName()}.{$existencias->getTableName()} e,
                            {$mapa->getDataBaseName()}.{$mapa->getTableName()} m
                        WHERE
                            e.IDUbicacion = m.IDUbicacion AND
                            e.IDAlmacen= '{$idAlmacen}' AND
                            e.IDArticulo= '{$this->IDArticulo}' AND
                            e.Reales > 0 AND
                            m.Ubicacion LIKE '{$filtroUbicacion}'";
            $em->query($query);
            $habituales = $em->fetchResult();
            $em->desConecta();
            unset($em);
        }

        if ($todas) {
            $habituales[] = array('Id' => '0', 'Value' => ':: Resto de Ubicaciones ::');
            $ubicaciones = array_merge($habituales, $mapa->fetchAll($idAlmacen, $filtroUbicacion));
        } else
            $ubicaciones = $habituales;

        unset($mapa);
        unset($existencias);

        return $ubicaciones;
    }

    /**
     * Devuelve un array con todos los lotes de los que
     * hay existencias para el artículo y el $idAlmacen
     *
     * Si el parametro $agrupado es TRUE se mostrará un solo registro por lote
     * acumulando todas las existencias que haya en distintas ubicaciones
     *
     * El array devuelto tiene 3 elementos:
     *
     *  Id: El id de lote
     *
     *  Value: El nombre del lote
     *
     *  Reales: Las existencias reales del lote y almacen
     *
     * @param integer $idAlmacen El id de almacen
     * @param boolean $agrupado Agrupar por ubicaciones true/false (defecto FALSE)
     * @return array Array de lotes
     */
    public function getLotesDisponibles($idAlmacen, $agrupado = FALSE) {

        $lotes = array();

        $tablaExistencias = new Existencias();
        $tablaLotes = new Lotes();

        $this->conecta();
        if (is_resource($this->_dbLink)) {
            //Lotes con existencias.
            //$query = "Call LotesArticulo({$idAlmacen},{$this->IDArticulo});";
            if ($agrupado) {
                $query = "SELECT e.IDLote AS Id, l.Lote AS Value, sum(e.Reales) as Reales
                        FROM 
                            {$tablaExistencias->getDataBaseName()}.{$tablaExistencias->getTableName()} e, 
                            {$tablaLotes->getDataBaseName()}.{$tablaLotes->getTableName()} l
                        WHERE
                            e.IDArticulo = '{$this->IDArticulo}' AND
                            e.IDAlmacen = '{$idAlmacen}' AND
                            e.IDLote = l.IDLote AND
                            Reales > 0
                        GROUP BY e.IDLote
                        ORDER BY l.FechaCaducidad ASC";
            } else {
                $query = "SELECT distinct e.IDLote AS Id, l.Lote AS Value, e.Reales as Reales
                        FROM 
                            {$tablaExistencias->getDataBaseName()}.{$tablaExistencias->getTableName()} e, 
                            {$tablaLotes->getDataBaseName()}.{$tablaLotes->getTableName()} l
                        WHERE
                            e.IDArticulo = '{$this->IDArticulo}' AND
                            e.IDAlmacen = '{$idAlmacen}' AND
                            e.IDLote = l.IDLote AND
                            e.Reales > 0
                        ORDER BY l.FechaCaducidad ASC";
            }
            $this->_em->query($query);
            $lotes = $this->_em->fetchResult();
            $this->_em->desConecta();
            unset($this->_em);
        }

        unset($tablaExistencias);
        unset($tablaLotes);

        return $lotes;
    }

    /**
     * Devuelve un array con todos los lotes y ubicaciones donde
     * hay existencias reales del artículo en el almacén indicado
     *
     * Si se indica el parametro 'cantidad' solo devuelve los lotes-ubicaciones
     * necesarios para satisfacer esa cantidad. En caso contrario, devuelve todos.
     *
     * El array tiene 4 columnas: IDLote, FechaCaducidad, IDUbicacion, Reales
     * El orden es ascendente por FechaCadudidad
     *
     * @param integer $idAlmacen El id de almacén
     * @param decimal $cantidad La cantidad que se necesita (opcional)
     * @return array Los Lotes, Ubicaciones y Existencias Reales
     */
    public function getLotesUbicaciones($idAlmacen, $cantidad = 0) {
        $lotesUbicaciones = array();

        $tablaExistencias = new Existencias();
        $tablaLotes = new Lotes();

        $this->conecta();
        if (is_resource($this->_dbLink)) {
            //Lotes con existencias.
            //$query = "Call LotesUbicacionesArticulo({$idAlmacen},{$this->IDArticulo});";
            $query = "SELECT DISTINCT e.IDLote, l.FechaCaducidad, e.IDUbicacion, e.Reales
                        FROM 
                            {$tablaExistencias->getDataBaseName()}.{$tablaExistencias->getTableName()} e, 
                            {$tablaLotes->getDataBaseName()}.{$tablaLotes->getTableName()} l
                        WHERE
                            e.IDArticulo = '{$this->IDArticulo}' AND
                            e.IDAlmacen= '{$idAlmacen}' AND
                            e.IDLote = l.IDLote AND
                            e.Reales > 0
                        ORDER BY l.FechaCaducidad, e.Reales ASC";
            $this->_em->query($query);
            $lotesUbicaciones = $this->_em->fetchResult();
            $this->_em->desConecta();
            unset($this->_em);
        }
        unset($tablaExistencias);
        unset($tablaLotes);

        if ($cantidad > 0) {
            //Devolver solo lo necesario para satisfacer esta cantidad
            $aux = array();
            $acumulado = 0;
            foreach ($lotesUbicaciones as $item) {
                $aux[] = $item;
                $acumulado += $item['Reales'];
                if ($acumulado >= $cantidad)
                    break;
            }
            $lotesUbicaciones = $aux;
        }

        return $lotesUbicaciones;
    }

    /**
     * Devuelve un array con las reservas del articulo y almacen indicado
     *
     * Cada elemento del array es otro array con los elementos:
     * array (
     *   'IDAlbaran' =>
     *   'PrimaryKeyMD5' =>
     *   'NumeroAlbaran' =>
     *   'Fecha' =>
     *   'IDCliente' =>
     *   'RazonSocial =>
     *   'Reservas' =>
     * )
     *
     * @param integer $idAlmacen El id de almacen
     * @return array Array con las reservas
     */
    public function getReservas($idAlmacen) {
        $reservas = array();

        $tablaAlbaranes = new AlbaranesCab();
        $tablaAlbaranesLineas = new AlbaranesLineas();
        $tablaClientes = new Clientes();

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            //$query = "Call ReservasArticuloAlmacen('{$this->IDArticulo}','{$idAlmacen}');";
            $query = "select
                        cab.IDAlbaran,
                        cab.PrimaryKeyMD5,
                        cab.NumeroAlbaran,                       
                        cab.Fecha,
                        cab.IDCliente,
                        cli.RazonSocial,
                        sum(lin.Unidades) as Reservas
                      from
                        {$tablaAlbaranes->getDataBaseName()}.{$tablaAlbaranes->getTableName()} as cab,
                        {$tablaAlbaranesLineas->getDataBaseName()}.{$tablaAlbaranesLineas->getTableName()} as lin,
                        {$tablaClientes->getDataBaseName()}.{$tablaClientes->getTableName()} as cli
                      where
                        cab.IDAlbaran  = lin.IDAlbaran and
                        cab.IDCliente  = cli.IDCliente and
                        lin.IDEstado   = '1' and
                        lin.IDArticulo = '{$this->IDArticulo}' and
                        lin.IDAlmacen  = '{$idAlmacen}'
                      group by IDAlbaran
                      order by Fecha ASC";
            $em->query($query);//echo $query,"<br/>";
            $reservas = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);

        unset($tablaAlbaranes);
        unset($tablaAlbaranesLineas);
        unset($tablaClientes);

        return $reservas;
    }
    
    /**
     * Devuelve un array con las entradas en curso del articulo y almacen indicado
     *
     * Cada elemento del array es otro array con los elementos:
     * array (
     *   'IDPedido' =>
     *   'PrimaryKeyMD5' =>
     *   'Fecha' =>
     *   'FechaEntraga' =>
     *   'IDProveedor' =>
     *   'RazonSocial =>
     *   'Entrando' =>
     * )
     *
     * @param integer $idAlmacen El id de almacen
     * @return array Array con las unidades entrando
     */
    public function getEntrando($idAlmacen) {
        $reservas = array();

        $tablaPedidos = new PedidosCab();
        $tablaPedidosLineas = new PedidosLineas();
        $tablaProveedores = new Proveedores();

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "select
                        cab.IDPedido,
                        cab.PrimaryKeyMD5,                     
                        cab.Fecha,
                        cab.FechaEntrega,
                        cab.IDProveedor,
                        prv.RazonSocial,
                        sum(lin.Unidades) as Entrando
                      from
                        {$tablaPedidos->getDataBaseName()}.{$tablaPedidos->getTableName()} as cab,
                        {$tablaPedidosLineas->getDataBaseName()}.{$tablaPedidosLineas->getTableName()} as lin,
                        {$tablaProveedores->getDataBaseName()}.{$tablaProveedores->getTableName()} as prv
                      where
                        cab.IDPedido  = lin.IDPedido and
                        cab.IDProveedor  = prv.IDProveedor and
                        lin.IDEstado   = '1' and
                        lin.IDArticulo = '{$this->IDArticulo}' and
                        lin.IDAlmacen  = '{$idAlmacen}'
                      group by IDPedido
                      order by Fecha ASC";
            $em->query($query);//echo $query,"<br/>";
            $reservas = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);

        unset($tablaPedidos);
        unset($tablaPedidosLineas);
        unset($tablaProveedores);

        return $reservas;
    }

    /**
     * Devuelve el registro de existencias del artículo para cada almacén
     * @return array
     */
    public function getExistencias() {

        $array = array();

        $existencias = new Existencias();
        $tablaExistencias = $existencias->getDataBaseName() . "." . $existencias->getTableName();
        $almacenes = new Almacenes();
        $tablaAlmacenes = $almacenes->getDataBaseName() . "." . $almacenes->getTableName();

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "select
                        a.Nombre,
                        sum(e.Reales) as Reales,
                        sum(e.Pales) as Pales,
                        sum(e.Cajas) as Cajas,
                        sum(e.Reservadas) as Reservadas,
                        sum(e.Entrando) as Entrando
                      from
                        {$tablaExistencias} as e
                        left join {$tablaAlmacenes} as a on e.IDAlmacen=a.IDAlmacen
                      where
                        e.IDArticulo='{$this->IDArticulo}'
                      group by a.Nombre
                      order by a.Nombre ASC";
            $em->query($query);
            $array = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);

        return $array;
    }

    /**
     * Devuelve los mvtos de almacén del artículo en curso para
     * el almacen y el periodo de fechas indicado.
     * 
     * Los movimientos se ordenan por almacén y descendentemente por fecha y hora
     * 
     * @param integer $idAlmacen Por defecto todos los almacenes
     * @param date $desdeFecha Por defecto todas
     * @param date $hastaFecha Por defecto todas
     * @return array
     */
    public function getMvtosAlmacen($idAlmacen = 0, $desdeFecha = '', $hastaFecha = '') {

        $array = array();

        $filtro = "(mv.IDArticulo='{$this->IDArticulo}')";
        $filtro .= ($idAlmacen <= 0) ? " and (1)" : " and (mv.IDAlmacen='$idAlmacen')";
        $filtro .= ($desdeFecha == '') ? " and (1)" : " and (mv.Fecha>='{$desdeFecha}')";
        $filtro .= ($hastaFecha == '') ? " and (1)" : " and (mv.Fecha<='{$hastaFecha}')";

        $mvtosAlmacen = new MvtosAlmacen();
        $tablaMvtos = $mvtosAlmacen->getDataBaseName() . "." . $mvtosAlmacen->getTableName();
        $almacenes = new Almacenes();
        $tablaAlmacenes = $almacenes->getDataBaseName() . "." . $almacenes->getTableName();
        $tipos = new TiposMvtosAlmacen();
        $tablaTipos = $tipos->getDataBaseName() . "." . $tipos->getTableName();

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "select
                        al.Nombre as Almacen,
                        ti.Descripcion,
                        ti.Signo,
                        ti.TipoDocumento,
                        mv.Fecha,
                        mv.Hora,
                        mv.IDUbicacion,
                        mv.IDLote,
                        mv.IDDocumento,
                        mv.UnidadesE,
                        mv.UnidadesS
                      from
                        {$tablaMvtos} as mv
                        left join {$tablaAlmacenes} as al on mv.IDAlmacen=al.IDAlmacen                        
                        left join {$tablaTipos} as ti on mv.IDTipo=ti.Id                        
                      where {$filtro}
                      order by mv.IDAlmacen,mv.Fecha DESC,mv.Hora DESC";
            $em->query($query);
            $array = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);

        return $array;
    }

    /**
     * Devuelve un valor numérico segun el artículo está sujeto a propiedades o no
     * 
     * El valor será:
     * 
     *      0 si no tiene propiedades
     *      1 si tiene a nivel de categoria
     *      2 si tiene a nivel de familia
     *      3 si tiene a nivel de subfamilia
     * 
     * Un artículo está sujeto a propiedades si su categoria, familia o subfamilia lo están
     * 
     * @return integer 0,1,2,3 indicando el nivel al que tiene propiedades
     */
    public function TienePropiedades() {

        $tiene = $this->IDSubfamilia->TienePropiedades();

        if (!$tiene) {
            $tiene = $this->IDFamilia->TienePropiedades();
            if (!$tiene) {
                $tiene = $this->IDCategoria->TienePropiedades();
                if ($tiene)
                    $tiene = 1;
            } else
                $tiene = 2;
        } else
            $tiene = 3;

        return $tiene;
    }

    /**
     * Devuelve un array con las propiedades que están definidas
     * para el artículo en curso.
     * 
     * Cada elemento del array es:
     * 
     * - Id: El id de la propiedad
     * - Titulo: El título de la propiedad
     * - IDTipo: el id de tipo de la propiedad
     * - IDValor: el id del valor actual de la propiedad
     * - Valores: array con los valores posibles para dicha propiedad
     * 
     * @param boolean $valorAditional Si true añade el valor adicional "Indique un valor" en el subarray de valores. Por defato true
     * @return array
     */
    public function getPropiedades($valorAditional = true) {

        $array = array();

        switch ($this->TienePropiedades()) {
            case 0:
                break;
            case 1:
                $propiedades = new FamiliasPropiedades();
                $rows = $propiedades->cargaCondicion("IDPropiedad", "IDFamilia='{$this->IDCategoria->getIDFamilia()}'");
                break;
            case 2:
                $propiedades = new FamiliasPropiedades();
                $rows = $propiedades->cargaCondicion("IDPropiedad", "IDFamilia='{$this->IDFamilia->getIDFamilia()}'");
                break;
            case 3:
                $propiedades = new FamiliasPropiedades();
                $rows = $propiedades->cargaCondicion("IDPropiedad", "IDFamilia='{$this->IDSubfamilia->getIDFamilia()}'");
                break;
        }

        foreach ($rows as $row) {
            $propiedad = new Propiedades($row['IDPropiedad']);
            $propiedadesValores = new PropiedadesValores();
            $propArticulo = new ArticulosPropiedades();
            $propiedadesArticulo = $propArticulo->cargaCondicion("IDValor", "IDArticulo='{$this->IDArticulo}' and IDPropiedad='{$row['IDPropiedad']}'");
            $array[$propiedad->getId()] = array(
                'Id' => $propiedad->getId(),
                'Titulo' => $propiedad->getTitulo(),
                'IDTipo' => $propiedad->getIDTipo()->getIDTipo(),
                'IDValor' => $propiedadesArticulo[0]['IDValor'],
                'Valores' => $propiedadesValores->getValores($row['IDPropiedad'], $valorAditional),
            );
        }

        unset($propiedadesValores);
        unset($propiedades);
        unset($propiedad);

        return $array;
    }

    /**
     * Devuelve un array con los artículos que forman
     * parte del escandallo del articulo actual
     * 
     * array('IDArticulo'=>,'Codigo'=>,'Descripcion'=>,'Unidades'=>,'UMV'=>)
     * 
     * @return array \Articulos
     */
    public function getEscandallo() {

        $escan = new ArticulosEscandallos();
        $filtro = "IDArticuloOrigen='{$this->IDArticulo}' and IDArticuloDestino<>'0'";
        $rows = $escan->cargaCondicion("IDArticuloDestino,Unidades", $filtro, "Id ASC");
        unset($escan);
        $array = array();

        foreach ($rows as $row) {
            $articulo = new Articulos($row['IDArticuloDestino']);
            $array[] = array(
                'IDArticulo' => $articulo->getIDArticulo(),
                'Codigo' => $articulo->getCodigo(),
                'Descripcion' => $articulo->getDescripcion(),
                'Unidades' => $row['Unidades'],
                'UMV' => $articulo->getUMV()->getUnidadMedida(),
            );
            unset($articulo);
        }

        return $array;
    }

    /**
     * Devuelve un array con los $nTop objetos Articulos más vendidos
     * 
     * @param int $nTop Por defecto 20
     * @return array \Articulos
     */
    public function getTops($nTop = 20) {

        if ($nTop <= 0)
            $nTop = 20;

        $lineasAlbaranes = new AlbaranesLineas();
        $rows = $lineasAlbaranes->cargaCondicion("IDArticulo,sum(Unidades)", "1 group by IDArticulo", "sum(Unidades) DESC limit {$nTop}");
        unset($lineasAlbaranes);

        $array = array();

        foreach ($rows as $row) {
            $array[] = new Articulos($row['IDArticulo']);
        }

        return $array;
    }

}
