<?php
/**
 * GENERA UN GRAFICO
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
 */

include "autoloader.inc";

//----------------------------------------------------------------
// ACTIVAR EL MOTOR DE GRAFICOS DE BARRAS
//----------------------------------------------------------------
if (is_array($config['config']['graph'])) {
    foreach ($config['config']['graph'] as $value) {
        $file = "../" . $value;
        if (file_exists($file))
            include_once $file;
        else
            die("NO SE PUEDE ENCONTRAR EL MOTOR DE GRAFICOS");
    }
}

// Se define el array de datos
$datosy = array(0,0,0,0,0,0,0,0,0,0,0,0);
$femitidas = new FemitidasCab();
$rows = $femitidas->cargaCondicion(
        "DATE_FORMAT(Fecha,'%m') as mes,sum(TotalBases) as base",
        "IDSucursal='{$_SESSION['suc']}' GROUP BY mes","mes ASC");
unset($femitidas);

foreach ($rows as $value) $datosy[$value['mes']-1] = $value['base'];
// Creamos el grafico

$grafico = new Graph(450, 250);
$grafico->SetScale('textlin');

// Ajustamos los margenes del grafico-----    (left,right,top,bottom)
$grafico->SetMargin(40, 30, 30, 40);

// Creamos barras de datos a partir del array de datos
$bplot = new BarPlot($datosy);
// Configuramos color de las barras
$bplot->SetFillColor('#479CC9');
//Añadimos barra de datos al grafico
$grafico->Add($bplot);
// Queremos mostrar el valor numerico de la barra
$bplot->value->Show();
// Configuracion de los titulos
$grafico->title->Set('Ventas Mensuales (Facturas)');
$grafico->xaxis->title->Set('Meses');
$grafico->yaxis->title->Set('Base Imponible');
$grafico->title->SetFont(FF_FONT1, FS_BOLD);
$grafico->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
$grafico->xaxis->title->SetFont(FF_FONT1, FS_BOLD);
// Se muestra el grafico
$grafico->Stroke();