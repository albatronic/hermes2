<?php

/**
 * CONTROLLER FOR PstoCab
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 00:45:18

 * Extiende a la clase controller
 */
class PstoCabController extends Controller {

    protected $entity = "PstoCab";
    protected $parentEntity = "";

    public function IndexAction() {
        return $this->ListAction();
    }
    
    /**
     * Generar el listado de presupuestos apoyándose en el método padre
     * Si el usuario es comercial muestra solo los
     * suyos, si no es comercial muestra todos.
     * @return array
     */
    public function listAction($aditionalFilter = '') {

        $filtro = "";

        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);

        if ($usuario->getEsComercial()) {
            $psto = new PstoCab();
            $tabla = $psto->getDataBaseName() . "." . $psto->getTableName();
            unset($psto);
            $filtro = $tabla . ".IDComercial='" . $usuario->getIDAgente() . "'";
        }

        return parent::listAction($filtro);
    }

    /**
     * Marca el presupuesto como confirmado (estado 1)
     * Generar un albaran en base al presupuesto
     * @return array Template y values
     */
    public function confirmarAction() {
        if ($this->values['permisos']['permisosModulo']['UP']) {

            $datos = new PstoCab($this->request['PstoCab']['IDPsto']);
            $datos->confirma();
            $this->values['errores'] = $datos->getErrores();
            $this->values['alertas'] = $datos->getAlertas();
            unset($datos);

            $template = $this->entity . "/edit.html.twig";
            $this->values['datos'] = new PstoCab($this->request['PstoCab']['IDPsto']);

            return array('template' => $template, 'values' => $this->values);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Marca el presupuesto y sus lineas como no confirmado (estado 0)
     * NO BORRAR EL ALBARAN QUE SE GENERÓ EN BASE A EL.
     * @return array Template y values
     */
    public function anularAction() {

        if ($this->values['permisos']['permisosModulo']['UP']) {

            $datos = new PstoCab($this->request['PstoCab']['IDPsto']);
            $datos->anulaConfirmacion();
            $this->values['errores'] = $datos->getErrores();
            $this->values['alertas'] = $datos->getAlertas();
            unset($datos);

            $this->values['datos'] = new PstoCab($this->request['PstoCab']['IDPsto']);

            return array('template' => $this->entity . '/edit.html.twig', 'values' => $this->values);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Hace una copia del presupuesto.
     * Genera otro presupuesto en base al actual.
     * IMPORTANTE: SE TOMAN LOS PRECIOS ACTUALES DE LOS ARTICULOS
     * 
     * @return array Template y values
     */
    public function duplicarAction() {
        if ($this->values['permisos']['permisosModulo']['IN']) {

            $datos = new PstoCab($this->request['PstoCab']['IDPsto']);
            $idPstoNuevo = $datos->duplica();
            if ($idPstoNuevo)
                $this->values['datos'] = new PstoCab($idPstoNuevo);
            else
                $this->values['datos'] = new PstoCab($this->request['PstoCab']['IDPsto']);

            $this->values['errores'] = $datos->getErrores();
            $this->values['alertas'] = $datos->getAlertas();
            unset($datos);

            return array('template' => $this->entity . '/edit.html.twig', 'values' => $this->values);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Envia por email el presupuesto en formato PDF
     * @return <type>
     */
    public function enviarAction() {
        
        switch ($this->request['accion']) {
            case 'Enviar':
                $para = $this->request['Para'];
                $de = $this->request['De'];
                $deNombre = $this->request['DeNombre'];
                $conCopia = $this->request['Cc'];  
                $conCopiaOculta = $this->request['Cco'];                
                $asunto = $this->request['Asunto'];
                $mensaje = $this->request['Mensaje'];
                $adjuntos = array($this->request['Adjunto'],);

                $envio = new Mail();
                $ok = $envio->send($para, $de, $deNombre, $conCopia, $conCopiaOculta, $asunto, $mensaje, $adjuntos);
                if ($ok) {
                    $entidad = new $this->entity($this->request['PstoCab']['IDPsto']);
                    $entidad->auditaEmail();
                    unset($entidad);
                    $this->values['resultadoEnvio'][] = "Envío con éxito";
                } else {
                    $this->values['resultadoEnvio'] = $envio->getMensaje();
                }
                unset($envio);
                break;

            case 'CambioFormato':
                $datos = new PstoCab($this->request['PstoCab']['IDPsto']);
                $formatos = DocumentoPdf::getFormatos($this->entity);
                $formato = $this->request['Formato'];
                if ($formato == '')
                    $formato = 0;

                $this->values['archivo'] = $this->generaPdf($this->entity, array('0' => $datos->getIDPsto()), $formato);
                $this->values['email'] = array(
                    'Para' => $this->request['Para'],
                    'De' => $this->request['De'],
                    'DeNombre' => $this->request['DeNombre'],
                    'Cc' => $this->request['Cc'],
                    'Cco' => $this->request['Cco'],                    
                    'Asunto' => $this->request['Asunto'],
                    'Formatos' => $formatos,
                    'Formato' => $formato,
                    'Mensaje' => $this->request['Mensaje'],
                    'idAlbaran' => $datos->getIDPsto(),
                );
                break;

            default:
                $datos = new PstoCab($this->request['PstoCab']['IDPsto']);
                $formatos = DocumentoPdf::getFormatos($this->entity);
                $formato = $this->request['Formato'];
                if ($formato == '')
                    $formato = 0;

                $this->values['archivo'] = $this->generaPdf($this->entity, array('0' => $datos->getIDPsto()), $formato);
                $this->values['email'] = array(
                    'Para' => $datos->getIDCliente()->getEMail(),
                    'De' => $_SESSION['usuarioPortal']['email'],//$datos->getIDComercial()->getIDAgente()->getEMail(),
                    'DeNombre' => $datos->getIDComercial()->getNombre(),
                    'Cco' => $_SESSION['usuarioPortal']['email'],
                    'Asunto' => 'Presupuesto n. ' . $datos->getIDPsto(),
                    'Formatos' => $formatos,
                    'Formato' => $formato,
                    'Mensaje' => 'Le adjunto el presupuesto ' . $datos->getIDPsto() . "\n\nUn saludo.",
                    'idPsto' => $datos->getIDPsto(),
                );
                break;
        }

        return parent::enviarAction();
    }

    /**
     * Genera un array con la informacion necesaria para imprimir el documento
     * Recibe el id del presupuesto
     *
     * @param array $idsDocumento Array con los ids de presupuestos
     * @return array Array con dos elementos: master es un objeto presupuesto y detail es un array de objetos lineas de presupuesto
     */
    protected function getDatosDocumento(array $idsDocumento) {

        $master = array();
        $detail = array();

        // Recorro el array de los albaranes a imprimir
        foreach ($idsDocumento as $key => $idDocumento) {
            // Instancio la cabecera del albaran
            $master[$key] = new PstoCab($idDocumento);

            // LLeno el array con objetos de lineas de presupuesto
            $lineas = array();
            $pstoLineas = new PstoLineas();
            $rows = $pstoLineas->cargaCondicion('IDLinea', "IDPsto='{$idDocumento}'", "IDPsto ASC");
            foreach ($rows as $row) {
                $lineas[] = new PstoLineas($row['IDLinea']);
            }
            $detail[$key] = $lineas;
        }

        return array(
            'master' => $master,
            'detail' => $detail,
        );
    }

}