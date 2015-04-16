<?php

/**
 * CONTROLLER FOR Etiquetas
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 00:45:15

 * Extiende a la clase controller
 */
require_once "bin/barcode/core.php";

class EtiquetaPdf extends FPDF {

    public function Footer() {
        
    }

}

class EtiquetasController extends Controller {

    protected $entity = "Etiquetas";
    protected $parentEntity = "";
    protected $formato = array();
    protected $docPdf;

    public function __construct($request) {

        // Coger los últimos 20 pedidos
        $pedido = new PedidosCab();
        $em = new EntityManager($pedido->getConectionName());
        if ($em->getDbLink()) {
            $query = "select IDPedido as Id,concat(IDPedido,' - ',RazonSocial) as Value FROM ErpPedidosCab,ErpProveedores where ErpPedidosCab.IDProveedor=ErpProveedores.IDProveedor ORDER BY FechaEntrada DESC LIMIT 20";
            $em->query($query);
            $rows = $em->fetchResult();
        }
        unset($em);
        unset($pedido);

        array_unshift($rows, array('Id' => '', Value => ':: Indique un Valor'));

        $this->values['pedidos'] = $rows;

        // Coger fabricantes
        $fabricante = new Fabricantes();
        $this->values['fabricantes'] = $fabricante->fetchAll();
        unset($fabricante);

        // Coger familias
        $familia = new Familias();
        $this->values['categorias'] = $familia->fetchAll();
        unset($familia);

        // Coger los almacenes
        $usuario = new Agentes();
        $this->values['almacenes'] = $usuario->getAlmacenes("", "", true);
        $this->values['almacenes'][] = array('Id' => '*', 'Value' => 'No tener en cuenta la existencias');
        unset($usuario);

        // Formatos de impresión
        $documento = new DocumentoPdf();
        $this->values['formatos'] = $documento->getFormatos($this->entity);
        unset($documento);

        parent::__construct($request);
    }

    public function IndexAction() {

        $etiqueta = new Etiquetas();
        $rows = $etiqueta->cargaCondicion("Id", "IDAgente='{$_SESSION['usuarioPortal']['Id']}'");
        unset($etiqueta);

        // Añado una etiqueta vacía
        $etiqueta = new Etiquetas();
        $etiqueta->setIDAgente($_SESSION['usuarioPortal']['Id']);
        $this->values['etiquetas'][] = $etiqueta;
        unset($etiqueta);

        foreach ($rows as $value)
            $this->values['etiquetas'][] = new Etiquetas($value['Id']);

        return array(
            'template' => $this->entity . "/list.html.twig",
            'values' => $this->values,
        );
    }

    public function editAction() {
        switch ($this->request['accion']) {
            case 'B' :
                $etiqueta = new Etiquetas($this->request['Etiquetas']['Id']);
                $etiqueta->delete();
                break;
            case 'G' :
                $idArticulo = $this->request['Etiquetas']['IDArticulo'];
                $descripcion = $this->request['Etiquetas']['Descripcion'];
                $unidades = ($this->request['Etiquetas']['Unidades'] <= 0) ? 1 : $this->request['Etiquetas']['Unidades'];
                $precio = $this->request['Etiquetas']['Precio'];
                $articulo = new Articulos($idArticulo);
                if ($articulo->getStatus()) {
                    $row = array(
                        "Id" => $this->request['Etiquetas']['Id'],
                        "IDArticulo" => $idArticulo,
                        "Descripcion" => $descripcion,
                        "Unidades" => $unidades,
                        "Precio" => $precio,);
                    $this->actualiza($row);
                }
                break;
        }

        return $this->IndexAction();
    }

    public function CargarAction() {

        if ($this->request['IdPedido']) {
            $lineas = new PedidosLineas();
            $rows = $lineas->cargaCondicion("IDArticulo,UnidadesRecibidas as Unidades", "IDPedido='{$this->request['IdPedido']}' and UnidadesRecibidas>0");
            unset($lineas);
        }

        if ($this->request['IdFabricante']) {
            $articulo = new Articulos();
            $em = new EntityManager($articulo->getConectionName());
            if ($em->getDbLink()) {
                $idAlmacen = $this->request['IdAlmacen'];
                if ($idAlmacen != '*') {
                    $query = "select a.IDArticulo, sum(e.Reales) as Unidades
                    from ErpArticulos as a, ErpExistencias as e 
                    where a.IDFabricante='{$this->request['IdFabricante']}' and 
                        a.Inventario='1' and a.Vigente='1' and a.IDArticulo=e.IDArticulo and 
                        e.IDAlmacen='{$idAlmacen}' and
                        e.Reales>0
                    group by a.IDArticulo";
                } else {
                    $query = "select a.IDArticulo, '1' as Unidades
                    from ErpArticulos as a
                    where a.IDFabricante='{$this->request['IdFabricante']}' and a.Inventario='1' and a.Vigente='1'";
                }
                $em->query($query);
                $rows = $em->fetchResult();
            }
            unset($em);
            unset($articulo);
        }

        if ($this->request['IdCategoria']) {
            $articulo = new Articulos();
            $em = new EntityManager($articulo->getConectionName());
            if ($em->getDbLink()) {
                $idAlmacen = $this->request['IdAlmacen'];
                if ($idAlmacen != '*') {
                    $query = "select a.IDArticulo, sum(e.Reales) as Unidades
                    from ErpArticulos as a, ErpExistencias as e 
                    where a.IDCategoria='{$this->request['IdCategoria']}' and 
                        a.Inventario='1' and a.Vigente='1' and
                        a.IDArticulo=e.IDArticulo and 
                        e.IDAlmacen='{$idAlmacen}' and
                        e.Reales>0
                    group by a.IDArticulo";
                } else {
                    $query = "select a.IDArticulo, '1' as Unidades
                    from ErpArticulos as a
                    where a.IDCategoria='{$this->request['IdCategoria']}' and a.Inventario='1' and a.Vigente='1'";
                }
                $em->query($query);
                $rows = $em->fetchResult();
            }
            unset($em);
            unset($articulo);
        }

        foreach ($rows as $row)
            $this->actualiza($row);

        return $this->IndexAction();
    }

    /**
     * Borra todas las etiquetas del agente en curso
     * @return type
     */
    public function BorrarAction() {

        $etiqueta = new Etiquetas();
        $etiqueta->queryDelete("IDAgente='{$_SESSION['usuarioPortal']['Id']}'");
        unset($etiqueta);

        return $this->IndexAction();
    }

    /**
     * Genera el documento pdf con las etiquetas
     * @return type
     */
    public function imprimirAction() {

        $this->formato = DocumentoPdf::getConfigFormato($this->entity, $this->request['IdFormato']);

        // Márgenes: top,right,bottom,left
        $margenes = explode(',', trim($this->formato['margins']));
        if (count($margenes) != 4)
            $margenes = array('10', '10', '15', '10');
        $this->formato['margins'] = $margenes;
        $this->formato['printBorder'] = $this->request['printBorder'];

        $this->docPdf = new EtiquetaPdf($this->formato['orientation'], $this->formato['unit'], $this->formato['format']);
        $this->docPdf->SetTopMargin($margenes[0]);
        $this->docPdf->SetRightMargin($margenes[1]);
        $this->docPdf->SetLeftMargin($margenes[3]);
        $this->docPdf->SetAuthor("Informatica ALBATRONIC, SL");
        $this->docPdf->SetTitle($this->format['title']);
        //$this->docPdf->AliasNbPages();
        //$this->docPdf->SetFillColor(210);
        $this->docPdf->SetAutoPageBreak(1, $margenes[2]);
        $this->docPdf->AddPage();
        //$primeraPagina = TRUE;

        $etiquetasPorPagina = $this->formato['rows'] * $this->formato['columns'];
        $puntero = $this->request['puntero'];
        if ($puntero <= 0 || $puntero > $etiquetasPorPagina)
            $puntero = 1;

        $etiqueta = new Etiquetas();
        $rows = $etiqueta->cargaCondicion("*", "IDAgente='{$_SESSION['usuarioPortal']['Id']}'", "Id ASC");
        foreach ($rows as $row) {
            $i = 0;
            while ($i < $row['Unidades']) {
                $i += 1;
                if ($puntero > $etiquetasPorPagina) {
                    $this->docPdf->AddPage();
                    $puntero = 1;
                }
                $this->HazEtiqueta($row, $puntero);
                $puntero += 1;
            }
        }
        $archivo = Archivo::getTemporalFileName();
        $this->docPdf->Output($archivo, 'F');
        unset($this->docPdf);

        $this->values['archivo'] = $archivo;
        $template = '_global/documentoPdf.html.twig';
        return array('template' => $template, 'values' => $this->values,);
    }

    /**
     * Genera una etiqueta en pdf
     * @param type $row Los datos
     * @param type $puntero EL número de etiqueta
     */
    private function HazEtiqueta($row, $puntero) {

        $fila = CEIL($puntero / $this->formato['columns']);
        $y = ($fila - 1) * ($this->formato['height'] + $this->formato['marginX']) + $this->formato['margins'][0] + 1;
        $yy = $y;

        $columna = $puntero % $this->formato['columns'];
        if ($columna == 0)
            $columna = $this->formato['columns'];
        $x = ($columna - 1) * ($this->formato['width'] + $this->formato['marginY']) + $this->formato['margins'][3];

        $articulo = new Articulos($row['IDArticulo']);

        $this->docPdf->SetXY($x, $y);

        // Imprimir el marco de la etiqueta
        if ($this->request['printBorder']) {
            $this->docPdf->Rect($x, $y, $this->formato['width'], $this->formato['height']);
            $this->docPdf->SetY($y);
        }

        $this->docPdf->SetFont('Arial', '', 5);
        $this->docPdf->SetX($x);

        $this->docPdf->Cell($this->formato['width'], 5, substr($row['Descripcion'], 0, 35), 0, 1, "C");

        // Imprimir el precio
        if ($this->request['printPvp']) {
            $this->docPdf->SetFont('Arial', 'B', 10);
            $this->docPdf->SetX($x);
            $this->docPdf->Cell($this->formato['width'], 5, round($row['Precio'], 2) . " E", 0, 1, "C");
        }

        //Creo la imagen png con el codigo de barras y luego la pinto
        if ($this->formato['Barcode']) {
            $archivoImagen = "docs/docs" . $_SESSION['emp'] . "/tmp/" . $row['IDArticulo'];
            barCode($this->formato['BarcodeType'], $articulo->getCodigo(), 1, 3, 1, FALSE, 20, BC_IMG_TYPE_PNG, TRUE, BC_ROTATE_0, TRUE, TRUE, $archivoImagen);
            $this->docPdf->Image($archivoImagen . ".png", $x + 2, $this->docPdf->GetY(), 0, $this->formato['BarcodeHeight']);
        } else {
            $this->docPdf->SetX($x);
            $this->docPdf->SetFont('Arial', '', 7);
            $this->docPdf->Cell($this->formato['width'], 5, "Ref " . $row['IDArticulo'], 0, 1, "C");
        }

        // Pie de la etiqueta
        if ($this->formato['TextFooter']) {
            $this->docPdf->SetY($yy + $this->formato['height'] - 6);
            $this->docPdf->SetFont('Arial', 'B', 5);
            $this->docPdf->SetX($x);
            $this->docPdf->Cell($this->formato['width'], 5, $this->formato['TextFooter'], 0, 0, "C");
        }
    }

    /**
     * Crea o incrementa una línea de etiqueta
     * @param type $row
     */
    private function actualiza($row) {

        $articulo = new Articulos($row['IDArticulo']);
        $descripcion = ($row['Descripcion'] == '') ? $articulo->getEtiqueta() : $row['Descripcion'];

        $etiqueta = new Etiquetas();
        $aux = $etiqueta->cargaCondicion("Id", "IDAgente='{$_SESSION['usuarioPortal']['Id']}' and IDArticulo='{$row['IDArticulo']}'");
        if (count($aux) > 0) {
            $etiqueta = new Etiquetas($aux[0]['Id']);
            $unidades = ($aux[0]['Id'] == $row['Id']) ? $row['Unidades'] : $etiqueta->getUnidades() + $row['Unidades'];
            $etiqueta->setDescripcion($descripcion);
            $etiqueta->setUnidades($unidades);
            $etiqueta->setPrecio(($row['Precio'] <= 0) ? $articulo->getPrecioVentaConImpuestos() : $row['Precio']);
            $etiqueta->save();
        } else {
            $etiqueta = new Etiquetas();
            $etiqueta->setIDAgente($_SESSION['usuarioPortal']['Id']);
            $etiqueta->setIDArticulo($row['IDArticulo']);
            $etiqueta->setDescripcion($descripcion);
            $etiqueta->setUnidades($row['Unidades']);
            $etiqueta->setPrecio(($row['Precio'] <= 0) ? $articulo->getPrecioVentaConImpuestos() : $row['Precio']);
            $etiqueta->setPublish(1);
            $etiqueta->create();
        }

        unset($etiqueta);
        unset($articulo);
    }

}

?>