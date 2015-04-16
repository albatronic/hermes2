<?php

/**
 * CONTROLLER FOR Articulos
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 00:45:13

 * Extiende a la clase controller
 */
class ArticulosController extends Controller {

    protected $entity = "Articulos";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }

    /**
     * Importa artículos desde fichero externo csv según
     * el formato de facturaplus
     * 
     * NOTA IMPORTANTE: SE HAN DE IMPORTAR LAS FAMILIAS ANTES.
     */
    public function ImportarAction() {

        $fileName = "docs/docs{$_SESSION['emp']}/tmp/articulos.csv";
        $archivo = new Archivo($fileName);
        $archivo->setColumnsDelimiter(";");
        //$archivo->setColumnsEnclosure('"');

        if ($archivo->open("r")) {
            set_time_limit(0);
            // Me salto la primera línea de cabecera
            $linea = $archivo->readLine();            
            while (($linea = $archivo->readLine()) !== FALSE) {//print_r($linea);
                $fam = new Familias();
                $fam = $fam->find('Observations', trim($linea[2]));
                $idFamilia = $fam->getIDFamilia();
                if (!$idFamilia)
                    $idFamilia = 1;

                $arti = new Articulos();
                $arti->setCodigo($linea[0]);
                $arti->setDescripcion(utf8_encode($linea[1]));
                $arti->setIDCategoria($idFamilia);
                $arti->setAllowsChildren($linea[4]);
                $arti->setInventario(1);
                $arti->setIDIva(1);
                $arti->setPmc(str_replace(",", ".", $linea[6]));
                $arti->setPvd(str_replace(",", ".", $linea[7]));
                $arti->setPvp(str_replace(",", ".", $linea[8]));
                $arti->setMargen(str_replace(",", ".", $linea[9]));
                $arti->setPeso(str_replace(",", ".", $linea[5]));
                $arti->setStockMaximo($linea[11]);
                $arti->setStockMinimo($linea[12]);
                $arti->setGarantia("S/F");
                $idArti = $arti->create();
                if (!$idArti) {
                    $nErrores += 1;
                    print_r($arti->getErrores());
                } else {
                    $nAciertos += 1;
                }
                unset($arti);
            }
            $archivo->close();
        }
        else
            $this->values['errores'][] = "El fichero de importación " . $fileName . " no existe";

        echo "Aciertos: {$nAciertos}, Errores: {$nErrores}";
        unset($archivo);
    }

}