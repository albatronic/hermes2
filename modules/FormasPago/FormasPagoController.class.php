<?php

/**
 * CONTROLLER FOR FormasPago
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 00:45:15

 * Extiende a la clase controller
 */
class FormasPagoController extends Controller {

    protected $entity = "FormasPago";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->listAction();
    }
    
    public function ImportarAction() {

        $fileName = "docs/docs{$_SESSION['emp']}/tmp/formasPago.csv";
        $archivo = new Archivo($fileName);
        $archivo->setColumnsDelimiter(";");
        //$archivo->setColumnsEnclosure('"');

        if ($archivo->open("r")) {
            set_time_limit(0);
            while (($linea = $archivo->readLine()) !== FALSE) {
                $fp = new FormasPago();
                $fp->setDescripcion(utf8_encode($linea[1]));
                $fp->setObservations($linea[0]);
                $fp->setNumeroVctos(1);
                $fp->setAnotarEnCaja(0);
                $id = $fp->create();
                if (!$id) {
                    $nErrores += 1;
                    print_r($fp->getErrores());
                } else {
                    $nAciertos += 1;
                }
                unset($fp);
            }
            $archivo->close();
        }
        else
            $this->values['errores'][] = "El fichero de importación " . $fileName . " no existe";

        echo "Aciertos: {$nAciertos}, Errores: {$nErrores}";
        unset($archivo);
    }    
}

?>