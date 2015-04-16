<?php

/**
 * CONTROLLER FOR Remesas
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 7.10.2013 11:45:13

 * Extiende a la clase controller
 */

class RemesasController  {

    protected $entity = "Remesas";
    protected $parentEntity = "";   
    protected $values;
    
    public function __construct($request) {

        // Cargar lo que viene en el request
        $this->request = $request;

        // Cargar la configuracion del modulo (modules/moduloName/config.yaml)
        $this->form = new Form($this->entity);

        // Cargar los permisos.
        // Si la entidad no está sujeta a control de permisos, se habilitan todos
        if ($this->form->getPermissionControl()) {
            if ($this->parentEntity == '')
                $this->permisos = new ControlAcceso($this->entity);
            else
                $this->permisos = new ControlAcceso($this->parentEntity);
        } else
            $this->permisos = new ControlAcceso();

        $this->values['titulo'] = $this->form->getTitle();
        $this->values['ayuda'] = $this->form->getHelpFile();
        $this->values['permisos'] = $this->permisos->getPermisos();
        $this->values['request'] = $this->request;

        // QUITAR LOS COMENTARIOS PARA Actualizar los favoritos para el usuario
        //if ($this->form->getFavouriteControl())
        //    $this->actualizaFavoritos();
        
        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);
        $this->values['sucursales'] = $usuario->getSucursales();
        unset($usuario);
        
        $estados = new EstadosRecibos();
        $this->values['estados'] = $estados->fetchAll();
        unset($estados);
        
        $this->values['filtro'] = $this->request['filtro'];
        if ($this->values['filtro']['desdeFecha'] == '')
            $this->values['filtro']['desdeFecha'] = '01-01-' . date('Y');
        if ($this->values['filtro']['hastaFecha'] == '')
            $this->values['filtro']['hastaFecha'] = '31-12-' . date('Y');        
    }

    public function IndexAction() {
        return array('template' => $this->entity . '/index.html.twig', 'values' => $this->values);
    }
    
    public function ListAction() {
        
        $filtro = $this->request['filtro'];
        $desde = new Fecha($filtro['desdeFecha']);
        $hasta = new Fecha($filtro['hastaFecha']);

        $filtroSucursal = ($filtro['idSucursal']) ? "IDSucursal='{$filtro['idSucursal']}'" : "1";
        $filtroEstado = ($filtro['idEstado'] != '') ? "IDEstado='{$filtro['idEstado']}'" : "1";
        $filtroQuery = "(Vencimiento>='{$desde->getaaaammdd()}') and (Vencimiento<='{$hasta->getaaaammdd()}') and ({$filtroSucursal}) and ({$filtroEstado})";// and (Iban<>0)";

        $recibos = new RecibosClientes();
        $rows = $recibos->cargaCondicion("IDRecibo",$filtroQuery,"Vencimiento ASC");
        unset($recibos);
       
        $array = array();
        foreach ($rows as $row) {
            $recibo = new RecibosClientes($row['IDRecibo']);            
            $array[] = array(
                'IDRecibo' => $recibo->getIDRecibo(),
                'NumeroFactura' => $recibo->getIDFactura()->getNumeroFactura(),
                'RazonSocial' => $recibo->getIDCliente()->getRazonSocial(),
                'Fecha' => $recibo->getFecha(),
                'Vencimiento' => $recibo->getVencimiento(),
                'Importe' => $recibo->getImporte(),
                'Remesa' => $recibo->getIDRemesa(),
                'Iban' => $recibo->getIban(),
                'Estado' => $recibo->getIDEstado()->getDescripcion(),
            );
        }
        unset($recibo);
        
        $this->values['datos'] = $array;
        
        return array('values' => $this->values, 'template' => $this->entity . '/list.html.twig');
    }
}
?>
