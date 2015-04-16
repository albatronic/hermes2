<?php

/**
 * CONTROLLER FOR RutasRepartoDetalle
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 14.05.2012 12:01:02

 * Extiende a la clase controller
 */
class RutasRepartoDetalleController extends Controller {

    protected $entity = "RutasRepartoDetalle";
    protected $parentEntity = "RutasReparto";

    /**
     * Genera array con las direcciones de entrega de la ruta para el
     * repartidor y dia indicado
     *
     * @param integer $idRepartidor
     * @param integer $dia
     * @return <type>
     */
    public function listAction($idRuta='', $dia='') {

        if ($idRuta == '')
            $idRuta = $this->request[2];

        if ($dia == '')
            $dia = $this->request[3];

        $this->values['listado']['dia'] = $dia;

        $cliente = new Clientes();
        $zona = new Zonas();
        $dentrega = new ClientesDentrega();
        $ruta = new RutasRepartoDetalle();
        
        // Busco las direcciones de entrega de la ruta indicada y sucursal actual
        // que aun no estén asignadas al día solicitado
        $em = new EntityManager($cliente->getConectionName());
        if ($em->getDbLink()) {
            $query = "SELECT t1.IDDirec as Id, t1.Nombre as Value
                        FROM 
                            {$dentrega->getDataBaseName()}.{$dentrega->getTableName()} as t1, 
                            {$cliente->getDataBaseName()}.{$cliente->getTableName()} as t2, 
                            {$zona->getDataBaseName()}.{$zona->getTableName()} as t3
                        WHERE t1.IDCliente=t2.IDCliente
                        AND t2.IDSucursal='{$_SESSION['suc']}'
                        AND t3.IDSucursal='{$_SESSION['suc']}'
                        AND t1.IDZona=t3.IDZona
                        AND t1.IDDirec NOT IN
                            (SELECT IDDirec FROM {$ruta->getDataBaseName()}.{$ruta->getTableName()}
                            WHERE IDRuta='{$idRuta}' AND Dia='{$dia}')
                        ORDER BY t1.Nombre ASC";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);

        $this->values['listado']['direcciones'] = $rows;
        array_unshift($this->values['listado']['direcciones'], array('Id' => '', 'Value' => ':: Indique una dirección'));

        // Busco las zonas de las direcciones de entrega de la sucursal actual
        // que aun no estén asignadas al día solicitado
        $em = new EntityManager($cliente->getConectionName());
        if ($em->getDbLink()) {
            $query = "SELECT DISTINCT t1.IDZona as Id, t2.Zona as Value 
                        FROM 
                            {$dentrega->getDataBaseName()}.{$dentrega->getTableName()} as t1, 
                            {$zona->getDataBaseName()}.{$zona->getTableName()} as t2, 
                            {$cliente->getDataBaseName()}.{$cliente->getTableName()} as t3
                        WHERE t1.IDZona=t2.IDZona
                        AND t1.IDCliente=t3.IDCliente
                        AND t3.IDSucursal='{$_SESSION['suc']}'
                        AND t2.IDSucursal='{$_SESSION['suc']}'
                        AND t1.IDDirec NOT IN
                            (SELECT IDDirec FROM {$ruta->getDataBaseName()}.{$ruta->getTableName()}
                            WHERE IDRuta='{$idRuta}' AND Dia='{$dia}')
                        ORDER BY t2.Zona ASC";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);

        $this->values['listado']['zonas'] = $rows;
        array_unshift($this->values['listado']['zonas'], array('Id' => '', 'Value' => ':: Indique una Zona'));
        //-----------------------------------------------
        // Lleno las direcciones asignadas al repartidor y día
        // ordenados por Orden de direccion de entrega y IDZona
        // -----------------------------------------------
        $em = new EntityManager($cliente->getConectionName());
        if ($em->getDbLink()) {
            $query = "SELECT t1.Id,t1.IDRepartidor 
                        FROM 
                            {$ruta->getDataBaseName()}.{$ruta->getTableName()}  as t1, 
                            {$dentrega->getDataBaseName()}.{$dentrega->getTableName()}  as t2, 
                            {$zona->getDataBaseName()}.{$zona->getTableName()} as t3
                        WHERE t1.IDDirec=t2.IDDirec
                        AND t2.IDZona=t3.IDZona
                        AND t1.IDRuta='{$idRuta}'
                        AND t1.Dia='{$dia}'
                        ORDER BY t1.OrdenDirec,t1.IDZona";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();
        }
        unset($em);
        unset($cliente);
        unset($dentrega);
        unset($zona);

        foreach ($rows as $row) {
            $lineas[] = new $this->entity($row['Id']);
        }
        //-----------------------------------------------

        $this->values['listado']['IDRepartidor'] = $rows[0]['IDRepartidor'];
        
        $template = $this->entity . '/list.html.twig';

        $this->values['linkBy']['value'] = $idRuta;
        $this->values['listado']['data'] = $lineas;
        $this->values['listado']['nDirecciones'] = count($lineas);

        unset($lis);
        unset($lineas);

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Guardar el id de repartidor para todas las lineas
     * de detalle de la ruta $IDRuta y dia $dia
     * @return <type>
     */
    public function cambiarRepartidorAction() {
        if ($this->values['permisos']['permisosModulo']['UP']) {

            $ruta = new RutasRepartoDetalle();
            
            $em = new EntityManager($ruta->getConectionName());
            if ($em->getDbLink()) {
                $em->query("UPDATE {$ruta->getDataBaseName()}.{$ruta->getTableName()} SET IDRepartidor='{$this->request['IDRepartidor']}' WHERE IDRuta='{$this->request['IDRuta']}' and Dia='{$this->request['dia']}'");
                $em->desConecta();
            }
            unset($em);
            unset($ruta);

            return $this->listAction($this->request['IDRuta'], $this->request['dia']);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Inserta direcciones de entrega a la ruta (repartidor-dia).
     * Puede insertar una sola direccion o bien todas las de una zona dada.
     * Esto depende de la 'accion' que venga en el request
     * @return <type>
     */
    public function newAction() {
        if ($this->values['permisos']['permisosModulo']['IN']) {

            switch ($this->request['accion']) {
                case 'direccion': //CREAR NUEVO REGISTRO
                    if ($this->request['IDDirec'] != '') {
                        $direc = new ClientesDentrega($this->request['IDDirec']);
                        $datos = new $this->entity();
                        $datos->setIDRuta($this->request['IDRuta']);
                        $datos->setIDRepartidor($this->request['IDRepartidor']);
                        $datos->setDia($this->request['dia']);
                        $datos->setIDDirec($this->request['IDDirec']);
                        $datos->setIDZona($direc->getIDZona()->getIDZona());
                        $datos->create();
                        unset($datos);
                        unset($direc);
                        //$this->values['datos'] = $datos;
                    }
                    break;

                case 'zona': //INSERTAR TODOS LAS DIRECCIONES DE ESA ZONA
                    if ($this->request['IDZona'] != '') {
                        $direc = new ClientesDentrega();
                        $rows = $direc->cargaCondicion("IDDirec", "IDZona='{$this->request['IDZona']}'");
                        unset($direc);
                        foreach ($rows as $key => $value) {
                            $datos = new $this->entity();
                            $datos->setIDRuta($this->request['IDRuta']);
                            $datos->setIDRepartidor($this->request['IDRepartidor']);
                            $datos->setDia($this->request['dia']);
                            $datos->setIDZona($this->request['IDZona']);
                            $datos->setIDDirec($value['IDDirec']);
                            $datos->create();
                        }
                        unset($datos);
                    }
                    break;
            }
            return $this->listAction($this->request['IDRuta'], $this->request['dia']);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Cambiar el orden de la zona:
     * hay que cambiar el orden en todos los registros de esa zona.
     * NOTA: NO SE UTILIZA ACTUALMENTE.
     * @return <type>
     */
    public function cambiarOrdenZonaAction() {
        if ($this->values['permisos']['permisosModulo']['UP']) {
            $datos = new $this->entity($this->request['Id']);
            $rows = $datos->cargaCondicion("Id", "IDZona='{$datos->getIDZona()->getIDZona()}'");
            foreach ($rows as $row) {
                $datos = new $this->entity($row['Id']);
                $datos->setOrdenZona($this->request['OrdenZona']);
                $datos->save();
            }
            unset($datos);
            return $this->listAction($this->request['IDRuta'], $this->request['dia']);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Cambiar el orden del cliente dentro de su zona
     * @return <type>
     */
    public function cambiarOrdenDirecAction() {
        if ($this->values['permisos']['permisosModulo']['UP']) {
            $datos = new $this->entity($this->request['Id']);
            $datos->setOrdenDirec($this->request['OrdenDirec']);
            $datos->save();
            return $this->listAction($this->request['IDRuta'], $this->request['dia']);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Borra todas las direcciones de entrega de una zona
     * @return <type>
     */
    public function borrarZonaAction() {
        if ($this->values['permisos']['permisosModulo']['DE']) {

            $rutas = new RutasRepartoDetalle();
            $em = new EntityManager($rutas->getConectionName());
            if ($em->getDbLink()) {
                $query = "DELETE FROM {$rutas->getDataBaseName()}.{$rutas->getTableName()} WHERE IDRuta='{$this->request['IDRuta']}' and IDZona='{$this->request['IDZona']}' and Dia='{$this->request['dia']}'";
                $em->query($query);
                $em->desConecta();
            }
            unset($em);
            unset($rutas);

            return $this->listAction($this->request['IDRuta'], $this->request['dia']);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

    /**
     * Borrar un registro cuyo Id viene en el request Id
     * @return <type>
     */
    public function borrarDireccionAction() {
        if ($this->values['permisos']['permisosModulo']['DE']) {
            $datos = new $this->entity($this->request['Id']);
            $datos->erase();
            return $this->listAction($this->request['IDRuta'], $this->request['dia']);
        } else {
            return array('template' => '_global/forbiden.html.twig');
        }
    }

}

?>