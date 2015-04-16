<?php

/**
 * CONTROLLER FOR Agentes
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL 
 * @since 07.06.2011 19:41:32

 * Extiende a la clase controller
 */
class AgentesController extends Controller {

    protected $entity = "Agentes";
    protected $parentEntity = "";

    /**
     * SI EL USUARIO NO ES SUPER, LE QUITO LOS PERMISOS DE INSERTAR Y BORRAR
     * EMPRESAS
     * 
     * @param type $request
     */
    public function __construct($request) {
        parent::__construct($request);

        if ($_SESSION['usuarioPortal']['IdPerfil'] != 1) {
            $this->values['permisos']['permisosModulo']['IN'] = false;
            $this->values['permisos']['permisosModulo']['DE'] = false;
        }

    }
    
    public function indexAction() {
        return $this->listAction();
    }

    public function listAction($aditionalFilter = '') {

        if ($_SESSION['usuarioPortal']['IdPerfil'] != 1)
            $aditionalFilter = "IDAgente <> 1";

        return parent::listAction($aditionalFilter);
    }

    public function listadoAction($aditionalFilter = '') {

        if ($_SESSION['usuarioPortal']['IdPerfil'] != 1)
            $aditionalFilter = "IDAgente <> 1";

        return parent::listadoAction($aditionalFilter);
    }

    /**
     * Edita, actualiza o borrar un registro
     *
     * Si viene por GET es editar
     * Si viene por POST puede ser actualizar o borrar
     * según el valor de $this->request['accion']
     *
     * @return array con el template y valores a renderizar
     */
    public function editAction() {

        switch ($this->request["METHOD"]) {

            case 'GET':
                if ($this->values['permisos']['permisosModulo']['CO']) {
                    //SI EN LA POSICION 3 DEL REQUEST VIENE ALGO,
                    //SE ENTIENDE QUE ES EL VALOR DE LA CLAVE PARA LINKAR CON LA ENTIDAD PADRE
                    //ESTO SE UTILIZA PARA LOS FORMULARIOS PADRE->HIJO
                    if ($this->request['3'] != '')
                        $this->values['linkBy']['value'] = $this->request['3'];

                    //MOSTRAR DATOS. El ID viene en la posicion 2 del request
                    $datos = new $this->entity();
                    $datos = $datos->find('PrimaryKeyMD5', $this->request[2]);
                    if ($datos->getStatus()) {
                        $this->values['datos'] = $datos;
                        $this->values['errores'] = $datos->getErrores();
                    } else {
                        $this->values['errores'] = array("Valor no encontrado. El objeto que busca no existe. Es posible que haya sido eliminado por otro usuario.");
                    }
                    $template = $this->entity . '/edit.html.twig';
                } else {
                    $template = '_global/forbiden.html.twig';
                }

                return array('template' => $template, 'values' => $this->values);
                break;

            case 'POST':
                //COGER DEL REQUEST EL LINK A LA ENTIDAD PADRE
                if ($this->values['linkBy']['id'] != '') {
                    $this->values['linkBy']['value'] = $this->request[$this->entity][$this->values['linkBy']['id']];
                }

                switch ($this->request['accion']) {
                    case 'Guardar': //GUARDAR DATOS
                        if ($this->values['permisos']['permisosModulo']['UP']) {
                            // Cargo la entidad
                            $datos = new $this->entity($this->request[$this->entity][$this->form->getPrimaryKey()]);
                            // Vuelco los datos del request
                            $datos->bind($this->request[$this->entity]);
                            //$usuario = new PcaeUsuarios($this->request[$this->entity][$this->form->getPrimaryKey()]);
                            //$usuario->bind($this->request['PcaeUsuarios']);
                            //$form = new Form('PcaeUsuarios');
                            //$rules = $form->getRules();
                            //unset($form);
                            //if ($usuario->valida($rules)) {
                            //$this->values['alertas'] = $usuario->getAlertas();
                            if (!$datos->save())
                                $this->values['errores'] = $datos->getErrores();

                            //Recargo el objeto para refrescar las propiedas que
                            //hayan podido ser objeto de algun calculo durante el proceso
                            //de guardado.
                            $datos = new $this->entity($this->request[$this->entity][$datos->getPrimaryKeyName()]);
                            //} else {
                            //    $this->values['errores'] = $usuario->getErrores();
                            //    $this->values['alertas'] = $usuario->getAlertas();
                            //}
                            $this->values['datos'] = $datos;
                            return array('template' => $this->entity . '/edit.html.twig', 'values' => $this->values);
                        } else {
                            return array('template' => '_global/forbiden.html.twig', 'values' => $this->values);
                        }
                        break;

                    case 'Borrar': //MARCA EL OBJETO COMO BORRADO, PERO NO BORRA FÍSICAMENTE

                        break;
                }
                break;
        }
    }

    /**
     * Crea un registro nuevo
     *
     * Si viene por GET muestra un template vacio
     * Si viene por POST crea un registro
     *
     * @return array con el template y valores a renderizar
     */
    public function newAction() {

        if ($this->values['permisos']['permisosModulo']['IN']) {

            switch ($this->request["METHOD"]) {
                case 'GET': //MOSTRAR FORMULARIO VACIO                
                    //SI EN LA POSICION 2 DEL REQUEST VIENE ALGO,
                    //SE ENTIENDE QUE ES EL VALOR DE LA CLAVE PARA LINKAR CON LA ENTIDAD PADRE
                    //ESTO SE UTILIZA PARA LOS FORMULARIOS PADRE->HIJO
                    if ($this->request['2'] != '')
                        $this->values['linkBy']['value'] = $this->request['2'];

                    $datos = new $this->entity();
                    $datos->setDefaultValues((array) $this->varEnvMod['columns']);
                    $this->values['datos'] = $datos;
                    $this->values['errores'] = array();
                    $template = $this->entity . '/new.html.twig';
                    break;

                case 'POST': //CREAR NUEVO REGISTRO
                    //COGER EL LINK A LA ENTIDAD PADRE
                    if ($this->values['linkBy']['id'] != '') {
                        $this->values['linkBy']['value'] = $this->request[$this->entity][$this->values['linkBy']['id']];
                    }

                    $datos = new $this->entity();
                    $datos->bind($this->request[$this->entity]);

                    $rules = $this->form->getRules();
                    $rules['GLOBALES']['numMaxPages'] = $this->varEnvPro['numMaxPages'];
                    $rules['GLOBALES']['numMaxRecords'] = $this->varEnvMod['numMaxRecords'];

                    if ($datos->valida($rules)) {
                        $em = new EntityManager($datos->getConectionName());
                        if ($em->getDbLink()) {
                            $v = $this->request[$this->entity];
                            $columnas = "`IDAgente`,`IDRol`,`IDPerfil`,`IDSucursal`,`IDAlmacen`,`Activo`,`PrimaryKeyMD5`";
                            $valores = "'{$v['IDAgente']}','{$v['IDRol']}','{$v['IDPerfil']}','{$v['IDSucursal']}','{$v['IDAlmacen']}','{$v['Activo']}','" . md5($v['IDAgente']). "'";
                            $query = "insert into {$datos->getDataBaseName()}.{$datos->getTableName()} ({$columnas}) VALUES ({$valores})";
                            $em->query($query);
                            $lastId = $em->getInsertId();
                            $em->desConecta();
                        }
                        $lastId = $this->request[$this->entity]['IDAgente'];
                        $this->values['errores'] = $datos->getErrores();
                        $this->values['alertas'] = $datos->getAlertas();

                        //Recargo el objeto para refrescar las propiedades que
                        //hayan podido ser objeto de algun calculo durante el proceso
                        //de guardado y pongo valores por defecto (urlamigable, etc)
                        if (($lastId) and ($datos->getUrlTarget() == '')) {
                            $datos = new $this->entity($lastId);
                            $this->gestionUrlMeta($datos);
                            $this->values['errores'] = $datos->getErrores();
                            $this->values['alertas'] = $datos->getAlertas();
                        }

                        // Si ex buscable, actualizar la tabla de búsquedas
                        if (($lastId) and ($this->varEnvMod['searchable'] == '1'))
                            $this->ActualizaBusquedas($datos);

                        $this->values['datos'] = $datos;

                        $template = ($this->values['errores']) ? $this->entity . '/edit.html.twig' : $this->entity . '/edit.html.twig';
                    } else {
                        $this->values['datos'] = $datos;
                        $this->values['errores'] = $datos->getErrores();
                        $this->values['alertas'] = $datos->getAlertas();
                        $template = $this->entity . '/new.html.twig';
                    }
                    break;
            }
        } else {
            $template = '_global/forbiden.html.twig';
        }

        return array('template' => $template, 'values' => $this->values);
    }

}

?>