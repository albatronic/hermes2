<?php

/**
 * CONTROLLER FOR Familias
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 00:45:15

 * Extiende a la clase controller
 */
class FamiliasController extends Controller {

    protected $entity = "Familias";
    protected $parentEntity = "";

    public function IndexAction() {
        return parent::newAction();
    }

    /**
     * Importa familias desde fichero externo csv según
     * el formato de facturaplus
     */    
    public function ImportarAction() {

        $fileName = "docs/docs{$_SESSION['emp']}/tmp/familias.csv";
        $archivo = new Archivo($fileName);
        $archivo->setColumnsDelimiter(";");
        //$archivo->setColumnsEnclosure('"');

        if ($archivo->open("r")) {
            set_time_limit(0);
            // Me salto la primera línea de cabecera
            $linea = $archivo->readLine();
            while (($linea = $archivo->readLine()) !== FALSE) {
                print_r($linea);
                $fp = new Familias();
                $fp->setFamilia(utf8_encode($linea[1]));
                $fp->setObservations($linea[0]);
                $fp->setInventario(1);
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