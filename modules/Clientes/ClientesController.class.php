<?php

/**
 * CONTROLLER FOR Clientes
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 07.06.2011 00:45:14

 * Extiende a la clase controller
 */
class ClientesController extends Controller {

    protected $entity = "Clientes";
    protected $parentEntity = "";

    public function indexAction() {
        return $this->listAction();
    }

    /**
     * Generar el listado de clientes apoyándose en el método padre
     * Si el usuario es comercial muestra solo los suyos.
     *
     * @return array
     */
    public function listAction($aditionalFilter = '') {

        $cliente = new Clientes();
        $tabla = $cliente->getDataBaseName() . "." . $cliente->getTableName();
        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);

        $filtro = "";
        if ($usuario->getEsComercial()) {
            $filtro = $tabla . ".IDComercial='" . $usuario->getIDAgente()->getId() . "'";
        }
        unset($usuario);
        unset($cliente);

        return parent::listAction($filtro);
    }

    /**
     * Devuelve el template "listVencimientos" con un listado
     * de todos los vencimientos del cliente en curso
     *
     * El template extiende al popup y está pensado para ser mostrado
     * en una solapa
     *
     * @return array
     */
    public function listVencimientosAction() {

        $idCliente = $this->request[2];

        $datos = new Clientes($idCliente);
        $this->values['recibos'] = $datos->getRecibos();
        unset($datos);

        return array('template' => $this->entity . "/listVencimientos.html.twig", 'values' => $this->values);
    }

    /**
     * Generar el listado de clientes apoyándose en el método padre
     * Si el usuario es comercial muestra solo los suyos.
     *
     * @return array
     */
    public function listadoAction($aditionalFilter = '') {

        $tabla = $this->form->getDataBaseName() . "." . $this->form->getTable();
        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);
        if ($usuario->getEsComercial()) {
            $filtro = $tabla . ".IDComercial='" . $usuario->getIDAgente() . "'";
        }
        unset($usuario);

        return parent::listadoAction($filtro);
    }

    /**
     * Establece los parametros de exportacion y se los entrega
     * al método padre del controller principal que es el que reliza
     * el proceso de exportación en base a estos parámetros.
     *
     * @return array
     */
    public function exportarAction($aditionalFilter = '') {

        $tabla = $this->form->getDataBaseName() . "." . $this->form->getTable();
        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);
        if ($usuario->getEsComercial()) {
            $filtro = $tabla . ".IDComercial='" . $usuario->getIDAgente() . "'";
        }
        unset($usuario);

        $this->values['export'] = array(
            'title' => 'Clientes de la sucursal ' . $_SESSION['suc'],
        );
        return parent::exportarAction($filtro);
    }

    /**
     * Importa clientes desde fichero externo csv según
     * el formato de facturaplus
     */    
    public function ImportarAction() {

        $fileName = "docs/docs{$_SESSION['emp']}/tmp/clientes.csv";
        $archivo = new Archivo($fileName);
        $archivo->setColumnsDelimiter(";");
        $archivo->setColumnsEnclosure('"');

        $idPais = 68; // España
        // Crear array de poblaciones
        if ($archivo->open("r")) {
            set_time_limit(0);
            while (($linea = $archivo->readLine()) !== FALSE)
                $poblaciones[trim($linea[4])] = 0;

            $pobla = new Municipios();
            foreach ($poblaciones as $key => $value) {
                $rows = $pobla->cargaCondicion("IDMunicipio", "Municipio='{$key}'");
                if ($rows[0]['IDMunicipio'] <> '')
                    $poblaciones[$key] = $rows[0]['IDMunicipio'];
            }
            unset($pobla);
            $archivo->close();
        }
        else
            $this->values['errores'][] = "El fichero de importación " . $fileName . " no existe";


        if ($archivo->open("r")) {
            set_time_limit(0);
            while (($linea = $archivo->readLine()) !== FALSE) {//print_r($linea);
            
                $fp = new FormasPago();
                $fp = $fp->find('Observations', trim($linea[20]));
                $idFp = $fp->getIDFP();
                if (!$idFp) $idFp = 1;
                
                $cliente = new Clientes();
                $cliente->setIDCliente($linea[0]);
                $cliente->setRazonSocial(utf8_encode($linea[1]));
                $cliente->setNombreComercial(utf8_encode($linea[2]));
                $cliente->setDireccion(utf8_encode($linea[3]));
                $cliente->setIDPoblacion($poblaciones[trim($linea[4])]);
                $cliente->setIDProvincia($linea[5]);
                $cliente->setCodigoPostal($linea[6]);
                $cliente->setIDPais($idPais);
                $cliente->setTelefono($linea[7]);
                $cliente->setMovil($linea[8]);
                $cliente->setFax($linea[9]);
                $cliente->setCif(str_replace("-", "",$linea[10]));
                $cliente->setObservaciones($linea[11] . " " . $linea[19]);
                $cliente->setBanco($linea[15]);
                $cliente->setOficina($linea[16]);
                $cliente->setDigito(substr($linea[17],2,2));
                $cliente->setCuenta($linea[18]);
                $cliente->setIDFP($idFp);
                $cliente->setEMail($linea[41]);
                $cliente->setLimiteRiesgo($linea[38]);
                $cliente->setIDZona(1);
                $cliente->setIDSucursal($_SESSION['suc']);
                $cliente->setIDTipo(1);
                $cliente->setIDGrupo(1);
                $idCliente = $cliente->create();
                if (!$idCliente) {
                    $nErrores += 1;
                    print_r($cliente->getErrores());
                } else {
                    $nAciertos += 1;
                }
                unset($cliente);
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