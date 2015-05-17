<?php

/**
 * Description of Controller
 *
 * Controlador común a todos los módulos del Erp
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @date 24-agosto-2012 19:39
 */
class Controller {

    /**
     * Variables enviadas en el request por POST o por GET
     * @var array
     */
    protected $request;

    /**
     * Objeto de la clase 'form' con las propiedades y métodos
     * del formulario obtenidos del fichero de configuracion
     * del formulario en curso
     * @var array
     */
    protected $form;

    /**
     * Valores a devolver al controlador principal para
     * que los renderice con el twig correspondiente
     * @var array
     */
    protected $values;

    /**
     * Objeto de la clase 'controlAcceso'
     * para gestionar los permisos de acceso a los métodos del controller
     * @var ControlAcceso
     */
    protected $permisos;

    /**
     * String con el nombre de la app a la que pertenece el módulo
     * @var string
     */
    protected $app;

    /**
     * Array de entidades enlazables a la actual
     * @var array
     */
    protected $enlazarCon = array();

    /**
     * Array con las variables Web del modulo
     * @var array
     */
    protected $varWebMod;

    /**
     * Array con las variables de entorno del modulo
     * @var array
     */
    protected $varEnvMod;

    /**
     * Array con las variables Web de la app
     * @var array
     */
    protected $varWebApp;

    /**
     * Array con las variables de entorno de la app
     * @var array
     */
    protected $varEnvApp;

    /**
     * Array con las variables de entorno del proyecto
     * @var array
     */
    protected $varEnvPro;

    /**
     * Array con las variables web del proyecto
     * @var array
     */
    protected $varWebPro;

    public function __construct($request) {

        if ($this->entity == '') {
            $this->entity = str_replace('Controller', '', get_class($this));
        }

        // Cargar lo que viene en el request
        $this->request = $request;

        
        $var = new CpanVariables('Pro', 'Env');
        $this->varEnvPro = $var->getValores();
        $var = new CpanVariables('Pro', 'Web');
        $this->varWebPro = $var->getValores();
        $var = new CpanVariables('App', 'Env');
        $this->varEnvApp = $var->getValores();
        $var = new CpanVariables('App', 'Web');
        $this->varWebApp = $var->getValores();
        $var = new CpanVariables('Mod', 'Env', $this->entity);
        $this->varEnvMod = $var->getValores();
        $var = new CpanVariables('Mod', 'Web', $this->entity);
        $this->varWebMod = $var->getValores();
        
        $this->variables = $this->setVariables($this->entity);
        print_r($this->variables);

        // Pongo la app a la que pertenece
        $this->app = $this->form->getNode('app');

        // Instanciar el objeto listado con los parametros del modulo
        // y los eventuales valores del filtro enviados en el request
        if ($this->form->getTieneListado()) {
            $this->listado = new Listado($this->form, $this->request);
            $this->values['listado'] = array(
                'filter' => $this->listado->getFilter(),
            );
        }

        // Cargar los permisos.
        // Si la entidad no está sujeta a control de permisos, se habilitan todos
        if ($this->form->getPermissionControl()) {
            $this->permisos = ($this->parentEntity == '') ? new ControlAcceso($this->entity) : new ControlAcceso($this->parentEntity);
        } else {
            $this->permisos = new ControlAcceso();
        }

        $this->values['titulo'] = $this->form->getTitle();
        $this->values['ayuda'] = $this->form->getHelpFile();
        $this->values['permisos'] = $this->permisos->getPermisos();
        $this->values['adittionalCommands'] = $this->form->getNode('aditional_commands');
        $this->values['enCurso'] = $this->values['permisos']['enCurso'];
        $this->values['tieneListado'] = $this->form->getTieneListado();
        $this->values['request'] = $this->request;
        $this->values['linkBy'] = array(
            'id' => $this->form->getLinkBy(),
            'value' => '',
        );

        $this->values['atributos'] = $this->form->getAtributos($this->entity); //$this->values['permisos']['enCurso']['modulo']);
        // Poner la solapa activa del formulario
        $this->values['solapaActiva'] = (!isset($this->request['solapaActiva'])) ? '0' : $this->request['solapaActiva'];
        // Poner el acordeon activo de los campos comunes
        $this->values['acordeonActivo'] = (!isset($this->request['acordeonActivo'])) ? '0' : $this->request['acordeonActivo'];

        // Registrar en el archivo log
        if (isset($this->varEnvPro['log'])) {
            Log::write($this->request);
        }
    }

    public function IndexAction() {

        if ($this->values['permisos']['permisosModulo']['AC'])
            $template = $this->entity . "/index.html.twig";
        else
            $template = "_global/forbiden.html.twig";

        return array(
            'template' => $template,
            'values' => $this->values,
        );
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
                        $this->values['metadatos'] = $datos->getMetaDatas();
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
                            $metaDatos = $this->request['metaDato'];

                            $rules = $this->form->getRules();
                            if ($datos->valida($rules)) {
                                $this->values['alertas'] = $datos->getAlertas();
                                if ($datos->save()) {
                                    if (count($metaDatos)) {
                                        $this->saveMetaDatos($datos->getPrimaryKeyValue(), $metaDatos);
                                    }

                                    if ($datos->getUrlTarget() == '') {
                                        $this->gestionUrlMeta($datos);

                                        // Salvar los campos Controller, action, template y parameters
                                        // del objeto CpanUrlAmigables asociado
                                        if ($this->request['objetoUrlAmigable']['Id'] != '') {
                                            $arrayUrlAmigable = $this->request['objetoUrlAmigable'];
                                            $objetoUrl = new CpanUrlAmigables($arrayUrlAmigable['Id']);
                                            $objetoUrl->setController($arrayUrlAmigable['Controller']);
                                            $objetoUrl->setAction($arrayUrlAmigable['Action']);
                                            $objetoUrl->setTemplate($arrayUrlAmigable['Template']);
                                            $objetoUrl->setParameters($arrayUrlAmigable['Parameters']);

                                            if (!$objetoUrl->save())
                                                $this->values['errores'] = $objetoUrl->getErrores();

                                            unset($objetoUrl);
                                        }
                                    }

                                    // Si estoy en el idioma principal y el módulo es traducible, tengo que
                                    // repercutir los cambios a los demás idiomas
                                    if (($_SESSION['idiomas']['actual'] == '0') && ($this->varEnvMod['translatable'] == '1'))
                                        $this->ActualizaIdiomas($datos->getPrimaryKeyValue());

                                    // Si ex buscable, actualizar la tabla de búsquedas
                                    if ($this->varEnvMod['searchable'] == '1')
                                        $this->ActualizaBusquedas($datos);
                                } else
                                    $this->values['errores'] = $datos->getErrores();

                                //Recargo el objeto para refrescar las propiedas que
                                //hayan podido ser objeto de algun calculo durante el proceso
                                //de guardado.
                                $datos = new $this->entity($this->request[$this->entity][$datos->getPrimaryKeyName()]);
                            } else {
                                $this->values['errores'] = $datos->getErrores();
                                $this->values['alertas'] = $datos->getAlertas();
                            }
                            $this->values['datos'] = $datos;
                            $this->values['metadatos'] = $datos->getMetaDatas();
                            return array('template' => $this->entity . '/edit.html.twig', 'values' => $this->values);
                        } else {
                            return array('template' => '_global/forbiden.html.twig', 'values' => $this->values);
                        }
                        break;

                    case 'Borrar': //MARCA EL OBJETO COMO BORRADO, PERO NO BORRA FÍSICAMENTE
                        if ($this->values['permisos']['permisosModulo']['DE']) {
                            $datos = new $this->entity($this->request[$this->entity][$this->form->getPrimaryKey()]);
                            $primaryKey = $datos->getPrimaryKeyValue();
                            if ($datos->erase()) {
                                $this->borraMetaDatos($this->entity, $primaryKey);

                                // Si ex buscable, actualizar la tabla de búsquedas
                                if ($this->varEnvMod['searchable'] == '1')
                                    $this->ActualizaBusquedas($datos);

                                $datos = new $this->entity();
                                $this->values['datos'] = $datos;
                                $this->values['metadatos'] = $datos->getMetaDatas();
                                $this->values['errores'] = array();
                                unset($datos);
                                return array('template' => $this->entity . '/new.html.twig', 'values' => $this->values);
                            } else {
                                $this->values['datos'] = $datos;
                                $this->values['metadatos'] = $datos->getMetaDatas();
                                $this->values['errores'] = $datos->getErrores();
                                $this->values['alertas'] = $datos->getAlertas();
                                unset($datos);
                                return array('template' => $this->entity . '/edit.html.twig', 'values' => $this->values);
                            }
                        } else {
                            return array('template' => '_global/forbiden.html.twig', 'values' => $this->values);
                        }
                        break;
                }
                break;
        }
    }

    /**
     * Crea un registro nuevo hijo asociándolo al
     * campo que viene en el request[2] y al valor que
     * viene en el request[3]
     *
     * @return array con el template y valores a renderizar
     */
    public function deAction() {

        if ($this->values['permisos']['permisosModulo']['IN']) {

            switch ($this->request["METHOD"]) {
                case 'GET':
                    if ($this->request['3'] != '') {
                        $this->values['linkBy']['value'] = $this->request['3'];
                        $entidad = new $this->request['2'];
                        $padre = $entidad->find("PrimaryKeyMD5", $this->request['4']);
                        $idPadre = $padre->getPrimaryKeyValue();
                        unset($padre);
                        unset($entidad);
                    }
                    $columnaAsociar = $this->request[3];
                    $datos = new $this->entity();
                    $datos->setDefaultValues((array) $this->varEnvMod['columns']);
                    $datos->{"set$columnaAsociar"}($idPadre);
                    $this->values['datos'] = $datos;
                    $this->values['errores'] = array();
                    $template = $this->entity . '/new.html.twig';
                    break;
            }
        } else {
            $template = '_global/forbiden.html.twig';
        }

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Crea un registro nuevo hijo (belongsTo)
     *
     * @return array con el template y valores a renderizar
     */
    public function belongsToAction() {

        if ($this->values['permisos']['permisosModulo']['IN']) {

            switch ($this->request["METHOD"]) {
                case 'GET': //MOSTRAR FORMULARIO VACIO                
                    //SI EN LA POSICION 2 DEL REQUEST VIENE ALGO,
                    //SE ENTIENDE QUE ES EL VALOR DE LA CLAVE PARA LINKAR CON LA ENTIDAD PADRE
                    //ESTO SE UTILIZA PARA LOS FORMULARIOS PADRE->HIJO
                    if ($this->request['2'] != '') {
                        $this->values['linkBy']['value'] = $this->request['2'];
                        $entidad = new $this->entity;
                        $padre = $entidad->find("PrimaryKeyMD5", $this->request['2']);
                        $idPadre = $padre->getPrimaryKeyValue();
                        unset($padre);
                        unset($entidad);
                    }

                    $datos = new $this->entity();
                    $datos->setDefaultValues((array) $this->varEnvMod['columns']);
                    $datos->setBelongsTo($idPadre);
                    $this->values['datos'] = $datos;
                    $this->values['errores'] = array();
                    $template = $this->entity . '/new.html.twig';
                    break;
            }
        } else {
            $template = '_global/forbiden.html.twig';
        }

        return array('template' => $template, 'values' => $this->values);
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
                    $this->values['metadatos'] = $datos->getMetaDatas();
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
                    $metaDatos = $this->request['metaDato'];

                    $rules = $this->form->getRules();
                    $rules['GLOBALES']['numMaxPages'] = $this->varEnvPro['numMaxPages'];
                    $rules['GLOBALES']['numMaxRecords'] = $this->varEnvMod['numMaxRecords'];

                    if ($datos->valida($rules)) {
                        $lastId = $datos->create();
                        if (($lastId) and ( count($metaDatos)))
                            $this->saveMetaDatos($lastId, $metaDatos);

                        $this->values['errores'] = $datos->getErrores();
                        $this->values['alertas'] = $datos->getAlertas();

                        //Recargo el objeto para refrescar las propiedades que
                        //hayan podido ser objeto de algun calculo durante el proceso
                        //de guardado y pongo valores por defecto (urlamigable, etc)
                        if (($lastId) and ( $datos->getUrlTarget() == '')) {
                            $datos = new $this->entity($lastId);
                            $this->gestionUrlMeta($datos);
                            $this->values['errores'] = $datos->getErrores();
                            $this->values['alertas'] = $datos->getAlertas();
                        }

                        // Si es buscable, actualizar la tabla de búsquedas
                        if (($lastId) and ( $this->varEnvMod['searchable'] == '1'))
                            $this->ActualizaBusquedas($datos);

                        $this->values['datos'] = $datos;
                        $this->values['metadatos'] = $datos->getMetaDatas();

                        $template = $this->entity . '/edit.html.twig';
                    } else {
                        $this->values['datos'] = $datos;
                        $this->values['metadatos'] = $datos->getMetaDatas();
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

    /**
     * Guardar o crea los metadatos
     * 
     * @param int $idEntity
     * @param array $metaDatos
     */
    public function saveMetaDatos($idEntity, $metaDatos) {

        foreach ($metaDatos as $key => $value) {
            $meta = new CpanMetaData();
            $rows = $meta->cargaCondicion("Id", "Entity='{$this->entity}' and IdEntity='{$idEntity}' and Name='{$key}'");
            if (count($rows)) {
                $meta->queryUpdate(array('Value' => $value), "Id='{$rows[0]['Id']}'");
            } else {
                $meta = new CpanMetaData();
                $meta->setEntity($this->entity);
                $meta->setIdEntity($idEntity);
                $meta->setName($key);
                $meta->setValue($value);
                $meta->create();
            }
        }
        unset($meta);
    }

    /**
     * Borra los meta datos
     * 
     * @param string $entidad
     * @param int $idEntidad
     */
    public function borraMetaDatos($entidad, $idEntidad) {
        $meta = new CpanMetaData();
        $meta->queryDelete("Entity='{$entidad}' and IdEntity='{$idEntidad}'");
    }

    /**
     * Muestra el template de ayuda asociado al controlador
     * El nombre del template de ayuda está definido en el
     * nodo <help_file> del config.yml del controlador
     * Si no existiera, se muestra un template indicando esta
     * circunstancia
     *
     * @return array con el template a renderizar
     */
    public function helpAction() {
        $template = $this->entity . '/' . $this->form->getHelpFile();
        $file = "modules/" . $template;
        if (!is_file($file) or ( $this->form->getHelpFile() == '')) {
            $template = "_help/noFound.html.twig";
        }

        $values['title'] = $this->form->getTitle();
        $values['idVideo'] = $this->form->getIdVideo();
        $values['urlVideo'] = $this->form->getUrlVideo();

        return array('template' => $template, 'values' => $values);
    }

    /**
     * Muestra la vista mediante la que se pueden enlazar
     * entidades a la entidad actual ($this->entity)
     * 
     * Las entitdades destino del enlace para cada controlador se definen 
     * en la clase entities/abstract/<NOMBRECONTROLADOR>Enlaces.class.php
     * 
     * @param string $primaryKeyMD5 El valor de la primaryKey en formato MD5 de la entidad
     * a la que se va a realizar el enlace
     * @return array Array template, value
     */
    public function EnlazarAction($primaryKeyMD5 = '') {

        // Obtener las entidades con las que se pueden enlazar la entidad actual
        $entidadActual = $this->entity . "Enlaces";
        $enlaces = new $entidadActual();
        $this->enlazarCon = $enlaces->fetchAll('Descripcion', 0);
        unset($enlaces);

        switch ($this->request['METHOD']) {
            case 'GET':
                if ($primaryKeyMD5 == '')
                    $primaryKeyMD5 = $this->request['2'];

                $entidad = new $this->entity();
                $objeto = $entidad->find("PrimaryKeyMD5", $primaryKeyMD5);

                $this->values['objetoOrigen'] = $objeto;
                $this->values['enlazarCon'] = $this->enlazarCon;
                unset($objeto);
                unset($entidad);

                return array(
                    'template' => '_global/popupEnlazar.html.twig',
                    'values' => $this->values,
                );
                break;

            case 'POST':
                $entidadDestino = $this->request['entidadDestino'];
                $idEntidadDestino = $this->request['idEntidadDestino'];
                $idEntidadOrigen = $this->request['idEntidadOrigen'];

                $objeto = new $this->entity($idEntidadOrigen);
                if ($this->request['accion'] == 'quitar') {
                    $objeto->setEntidad('');
                    $objeto->setIdEntidad(0);
                    $entidadEnlazada = '';
                } else {
                    $objeto->setEntidad($entidadDestino);
                    $objeto->setIdEntidad($idEntidadDestino);
                    $entidadEnlazada = $entidadDestino;
                }
                $objeto->setUrlTarget('');
                $objeto->setUrlParameters('');
                $objeto->save();

                $this->values['entidadOrigen'] = $this->entity;
                $this->values['idEntidadOrigen'] = $idEntidadOrigen;
                $this->values['entidadDestino'] = new $entidadDestino();
                $this->values['idEntidadDestino'] = $idEntidadDestino;
                $this->values['entidadEnlazada'] = $entidadEnlazada;

                return array(
                    'template' => $entidadDestino . '/formEnlazar.html.twig',
                    'values' => $this->values,
                );
                break;
        }
    }

    public function CargaEnlacesAction() {

        $primaryKeyMD5 = $this->request[2];
        $entidadDestino = $this->request[3];

        $entidadOrigen = new $this->entity();
        $rows = $entidadOrigen->cargaCondicion('Id,Entidad,IdEntidad', "PrimaryKeyMd5='{$primaryKeyMD5}'");
        unset($entidadOrigen);

        $this->values['entidadOrigen'] = $this->entity;
        $this->values['idEntidadOrigen'] = $rows[0]['Id'];
        $this->values['entidadDestino'] = new $entidadDestino();
        $this->values['idEntidadDestino'] = $rows[0]['IdEntidad'];
        $this->values['entidadEnlazada'] = $rows[0]['Entidad'];

        return array(
            'template' => $entidadDestino . '/formEnlazar.html.twig',
            'values' => $this->values,
        );
    }

    /**
     * Genera una listado por pantalla en base al filtro.
     * Puede recibir un filtro adicional
     *
     * @param string $aditionalFilter
     * @return array con el template y valores a renderizar
     */
    public function listAction($aditionalFilter = '') {

        if ($this->values['permisos']['permisosModulo']['CO']) {

            $objeto = new $this->entity();
            $tabla = $objeto->getDataBaseName() . "." . $objeto->getTableName();
            unset($objeto);

            if ($aditionalFilter != '')
                $aditionalFilter .= " AND ";
            $aditionalFilter .= "({$tabla}.Deleted='0')";
            $this->values['listado'] = $this->listado->getAll($aditionalFilter);
            $template = $this->entity . '/list.html.twig';
        } else {
            $template = '_global/forbiden.html.twig';
        }

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Genera un listado en formato PDF en base a los parametros obtenidos
     * del fichero listados.yml de cada controlador y los datos filtrados
     * segun el request
     * @return array Template y valores
     */
    public function listadoAction($aditionalFilter = '') {

        if ($this->values['permisos']['permisosModulo']['LI']) {
            // Lee la configuracion del listado
            $formato = new Form($this->entity, 'listados.yml');
            $parametros = $formato->getFormatoListado($this->request['formatoListado']);
            unset($formato);

            $this->values['archivo'] = $this->listado->getPdf($parametros, $aditionalFilter);
            $template = '_global/listadoPdf.html.twig';
        } else {
            $template = "_global/forbiden.html.twig";
        }

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Renderiza el documento indicado en $this->values['archivo']
     * @return array Template y valores
     */
    public function imprimirAction() {
        if ($this->values['permisos']['permisosModulo']['LI']) {

            if ($this->request['METHOD'] == 'GET') {
                $idDocumento = $this->request['2'];
                $tipoDocumento = $this->request['3'];
                $formato = $this->request['4'];
            } else {
                $idDocumento = $this->request['idDocumento'];
                $tipoDocumento = $this->request['tipoDocumento'];
                $formato = $this->request['formato'];
            }

            $this->values['archivo'] = $this->generaPdf($tipoDocumento, array('0' => $idDocumento), $formato);
            $template = '_global/documentoPdf.html.twig';
        } else {
            $template = "_global/forbiden.html.twig";
        }

        return array('template' => $template, 'values' => $this->values,);
    }

    /**
     * Enviar por email el documento indicado en $this->values['archivo']
     * @return array Template y valores
     */
    public function enviarAction() {
        return array('template' => $this->entity . '/mail.html.twig', 'values' => $this->values,);
    }

    /**
     * Realiza el proceso de exportación de información en base a
     * los datos que le pasa cada controlador en $this->values['export']
     *
     * Puede generar distintos tipos de archivos (xml, excel).
     * Despues de generar el archivo, muestra un template para descargarlo
     *
     * @return array
     */
    public function exportarAction($aditionalFilter = '') {

        if ($this->values['permisos']['permisosModulo']['EX']) {

            if ($this->values['export']['title'] == '')
                $this->values['export']['title'] = $this->entity;

            switch ($this->request['exportType']) {
                case 'xml':
                    $this->values['export']['file'] = $this->listado->getXml($this->request['formatoListado'], $aditionalFilter);
                    break;
                case 'xls':
                    $this->values['export']['file'] = $this->listado->getXls($this->request['formatoListado'], $aditionalFilter);
                    break;
                case 'Yaml':
                    $this->values['export']['file'] = $this->listado->getYaml($this->request['formatoListado'], $aditionalFilter);
                    break;
                case 'csv':
                    $this->values['export']['file'] = $this->listado->getCsv($this->request['formatoListado'], $aditionalFilter);
                    break;
            }

            $template = '_global/exportar.html.twig';
        } else {
            $template = '_global/forbiden.html.twig';
        }
        return array('template' => $template, 'values' => $this->values,);
    }

    /**
     * Sube o quita del servidor los documentos (imagenes, etc) asociados
     * a la entidad.
     *
     * @return array
     */
    public function ImagenAction() {

        $rules = array(
            'allowTypes' => explode(",", $this->varEnvPro['allowTypes']),
            'maxFileSize' => $this->varEnvMod['maxSizes']['image'], // Tamaño expresado en bytes
        );

        $idEntidad = $this->request[$this->entity][$this->form->getPrimaryKey()];

        switch ($this->request['accion']) {
            case 'EnviarMaster':
                if ($this->values['permisos']['permisosModulo']['UP']) {

                    $variables = new CpanVariables('Mod', 'Env', $this->entity);
                    $varEnv = $variables->getValores();
                    unset($variables);
                    $datos = new $this->entity($idEntidad);
                    $columnaSlug = $varEnv['fieldGeneratorUrlFriendly'];
                    $slug = $datos->{"get$columnaSlug"}();
                    unset($datos);

                    $doc = new CpanDocs();
                    $doc->setArrayDoc($_FILES['imagenMaster']);
                    if ($doc->validaArchivo($rules)) {

                        // Borrar las eventuales imagenes que existieran
                        $img = new CpanDocs();
                        $img->borraDocs($this->entity, $idEntidad, 'image%');
                        unset($img);

                        foreach ($this->varEnvMod['images'] as $key => $value)
                            if ($value['visible'] == '1') {

                                $_FILES['imagenMaster']['maxWidth'] = $value['width'];
                                $_FILES['imagenMaster']['maxHeight'] = $value['height'];
                                $_FILES['imagenMaster']['modoRecortar'] = $this->request['modoRecortar'];

                                $doc = new CpanDocs();
                                $doc->setEntity($this->entity);
                                $doc->setIdEntity($idEntidad);
                                $doc->setPathName($this->entity . $idEntidad);
                                $doc->setName($slug);
                                $doc->setTitle($slug);
                                $doc->setType('image' . $key);
                                $doc->setArrayDoc($_FILES['imagenMaster']);
                                $doc->setIsThumbnail(0);
                                $doc->setPublish($value['valorDefectoPublicar']);
                                if ($doc->valida($rules))
                                    $lastId = $doc->create();
                                $this->values['errores'] = $doc->getErrores();
                                if (count($doc->getErrores())) {
                                    $doc->borraDocs($this->entity, $idEntidad, 'image%');
                                    $lastId = 0;
                                }

                                // Subir Miniatura
                                if (($lastId) and ( $value['generateThumbnail'] == '1')) {

                                    $_FILES['imagenMaster']['maxWidth'] = $value['widthThumbnail'];
                                    $_FILES['imagenMaster']['maxHeight'] = $value['heightThumbnail'];
                                    $doc = new CpanDocs();
                                    $doc->setEntity($this->entity);
                                    $doc->setIdEntity($idEntidad);
                                    $doc->setPathName($this->entity . $idEntidad);
                                    $doc->setName($slug);
                                    $doc->setTitle($slug);
                                    $doc->setType('image' . $key);
                                    $doc->setArrayDoc($_FILES['imagenMaster']);
                                    $doc->setIsThumbnail(1);
                                    $doc->setPublish($value['valorDefectoPublicar']);
                                    $doc->setBelongsTo($lastId);
                                    if ($doc->valida($rules))
                                        $ok = $doc->create();
                                    if (!$ok)
                                        $this->values['errores'] = $doc->getErrores();
                                }
                            }
                    } else
                        $this->values['errores'] = $doc->getErrores();

                    $template = $this->entity . '/edit.html.twig';
                } else {
                    $template = "_global/forbiden.html.twig";
                }
                break;

            case 'GuardarCambios':
                if ($this->values['permisos']['permisosModulo']['UP']) {

                    $idImagen = $this->request['idImagenEnviar'];

                    $id = $this->request['image'][$idImagen]['Id'];
                    $tipo = $this->request['image'][$idImagen]['Tipo'];
                    $title = trim($this->request['image'][$idImagen]['Title']);
                    $slug = trim($this->request['image'][$idImagen]['Name']);
                    $showCaption = $this->request['image'][$idImagen]['ShowCaption'];
                    $orden = $this->request['image'][$idImagen]['SortOrder'];
                    $publicar = $this->request['image'][$idImagen]['Publish'];
                    $documento = $this->request['FILES'][$tipo];
                    $documento['maxWidth'] = $this->varEnvMod['images'][$idImagen]['width'];
                    $documento['maxHeight'] = $this->varEnvMod['images'][$idImagen]['height'];
                    $documento['modoRecortar'] = $this->request['image'][$idImagen]['modoRecortar'];

                    $doc = new CpanDocs($id);
                    $doc->setTitle($title);
                    $doc->setName($slug);
                    $doc->setShowCaption($showCaption);
                    $doc->setSortOrder($orden);
                    $doc->setPublish($publicar);
                    if ($documento['name'] != '')
                        $doc->setArrayDoc($documento);
                    $doc->setIsThumbnail(0);
                    if ($doc->valida($rules)) {
                        $ok = $doc->actualiza();
                        // Subir Miniatura
                        if (($ok) and ( $this->varEnvMod['images'][$idImagen]['generateThumbnail'] == '1')) {
                            $thumbNail = $doc->getThumbNail();
                            $thumbNail->setTitle($title);
                            $thumbNail->setName($slug);
                            $thumbNail->setShowCaption($showCaption);
                            $thumbNail->setSortOrder($orden);
                            $thumbNail->setIsThumbnail(1);
                            if ($documento['name'] != '') {
                                $documento['maxWidth'] = $this->varEnvMod['images'][$idImagen]['widthThumbnail'];
                                $documento['maxHeight'] = $this->varEnvMod['images'][$idImagen]['heightThumbnail'];
                                $thumbNail->setArrayDoc($documento);
                            }
                            if ($thumbNail->valida($rules)) {

                                $ok = $thumbNail->actualiza();
                            }

                            unset($thumbNail);
                        }
                    }

                    $template = $this->entity . '/edit.html.twig';
                } else {
                    $template = "_global/forbiden.html.twig";
                }
                break;

            case 'Quitar':
                if ($this->values['permisos']['permisosModulo']['DE']) {
                    $idImagen = $this->request['idImagenEnviar'];
                    $tipo = $this->request['image'][$idImagen]['Tipo'];
                    $img = new CpanDocs();
                    if (!$img->borraDocs($this->entity, $idEntidad, $tipo))
                        $this->values['errores'] = $img->getErrores();
                    unset($img);
                    $template = $this->entity . '/edit.html.twig';
                } else {
                    $template = "_global/forbiden.html.twig";
                }
                break;
        }

        $this->values['datos'] = new $this->entity($idEntidad);
        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Genera un documento pdf
     *
     * @param string $tipoDocumento El tipo de documento: albaranes, pedidos, etc.
     * @param array $idsDocumento Array con los ids de la entidad a imprimir. Ej. id de albaran, pedido, etc.
     * @param integer $formato El formato del documento (defecto=0)
     * @return string Nombre del archivo pdf generado con la ruta completa
     */
    protected function generaPdf($tipoDocumento, array $idsDocumento, $formato = 0) {

        // Cargo en un array el archivo de configuracion
        // del tipo de documento y formato
        $config = DocumentoPdf::getConfigFormato($tipoDocumento, $formato);

        // LLamo al método específico de cada controlador para que obtenga
        // la información necesaria del documento.
        // Le paso el array con los ids de documentos (ej: id de albaran, pedido, factura, etc)
        $datos = $this->getDatosDocumento($idsDocumento);

        // CREAR EL DOCUMENTO----------------------------------------------------
        $fichero = Archivo::getTemporalFileName();

        if ($fichero) {
            $pdf = new DocumentoPdf($config['orientation'], $config['unit'], $config['format']);
            $pdf->generaDocumento($config, $datos['master'], $datos['detail'], $fichero);
        }

        return $fichero;
    }

    /**
     * Cambia de idioma
     * @return type
     */
    public function langAction() {

        // En la posición 2 del resquest viene el número de idioma seleccionado
        $idiomaNuevo = $this->request[2];
        $PrimaryKeyMD5 = $this->request[3];

        $_SESSION['idiomas']['actual'] = $idiomaNuevo;

        // Si en la posición 3 del request viene algo, es el primaryKeyMD5
        // del registro a editar.
        if ($PrimaryKeyMD5 != '') {

            $this->request[2] = $PrimaryKeyMD5;

            // Busco el objeto en el idioma nuevo
            $datos = new $this->entity();
            $datos = $datos->find('PrimaryKeyMD5', $PrimaryKeyMD5);
            if ($datos->getStatus()) {
                // Si existe, lo edito (en el idioma nuevo)
                $this->request['METHOD'] = 'GET';
                return $this->editAction();
            } else {
                // No existe en el idioma nuevo, hay que crearlo
                $this->request['METHOD'] = 'POST';
                // Busco el objeto en el idioma principal
                $_SESSION['idiomas']['actual'] = 0;
                $datos = new $this->entity();
                $datos = $datos->find('PrimaryKeyMD5', $PrimaryKeyMD5);
                // Vuelco al request las propiedades del objeto
                $this->request[$this->entity] = $datos->iterator();
                // Cambio el idioma y creo el objeto nuevo
                $_SESSION['idiomas']['actual'] = $idiomaNuevo;
                return $this->newAction();
            }
        } else
            return $this->IndexAction();
    }

    /**
     * Crea o actualiza la url amigable y el metatagTitle
     *
     * @param array $datos
     */
    protected function gestionUrlMeta($datos) {

        $objetoAuxuliar = new $this->entity($datos->getPrimaryKeyValue());

        if ($this->varEnvMod['urlFriendlyManagement'])
            $urlAmigable = $this->calculaUrlAmigable($objetoAuxuliar);

        if ($this->varEnvMod['metatagTitleManagement'])
            $metatag = $this->calculaMetatagTitle($objetoAuxuliar);

        unset($objetoAuxuliar);

        if (count($urlAmigable) or count($metatag)) {

            if (count($urlAmigable)) {
                $arrayUpdate['UrlPrefix'] = $urlAmigable['prefix'];
                $arrayUpdate['Slug'] = $urlAmigable['slug'];
                $arrayUpdate['UrlFriendly'] = $urlAmigable['url'];
                /**
                  $datos->setUrlPrefix($urlAmigable['prefix']);
                  $datos->setSlug($urlAmigable['slug']);
                  $datos->setUrlFriendly($urlAmigable['url']); */
            }

            if (count($metatag)) {
                $arrayUpdate['MetatagTitle'] = $metatag['title'];
                $arrayUpdate['MetatagDescription'] = $metatag['description'];
                $arrayUpdate['MetatagKeywords'] = $metatag['keywords'];
                //$datos->setMetatagTitle($metatagTitle);
            }
            $condicion = "{$datos->getPrimaryKeyName()}='{$datos->getPrimaryKeyValue()}'";
            $datos->queryUpdate($arrayUpdate, $condicion);
            //$datos->save();
        }
    }

    protected function calculaUrlAmigable($datos) {

        $urlPrefix = '';
        $urlAmigable = '';
        $slug = '';

        $columnaSlug = $this->varEnvMod['fieldGeneratorUrlFriendly'];

        // Si hay que generar la url amigable
        if ($columnaSlug) {

            $bloqueoUrlPrefix = ( $datos->getLockUrlPrefix()->getIDTipo() == '1' );
            $datos->setLockUrlPrefix($bloqueoUrlPrefix);
            $bloqueoSlug = ( $datos->getLockSlug()->getIDTipo() == '1' );
            $datos->setLockSlug($bloqueoSlug);
            $perteneceA = $datos->getBelongsTo()->getPrimaryKeyValue();

            // CALCULAR EL PREFIJO DE LA URL -----------------------------------
            //
            // Si está bloqueado el prefijo, se calcula
            if ($bloqueoUrlPrefix) {

                if ($this->varEnvMod['isModuleRoot']) {
                    // Es el módulo padre de la app
                    if ($perteneceA) {
                        $objetoPadre = new $this->entity($perteneceA);
                        if ($objetoPadre->getUrlHeritable()->getIDTipo() == '1') {
                            $urlPrefix = $objetoPadre->getUrlFriendly();
                        } else {
                            $urlPrefix = "/" . $this->varEnvApp['globales']['urlPrefix'];
                        }
                        unset($objetoPadre);
                    } else {
                        $urlPrefix = "/" . $this->varEnvApp['globales']['urlPrefix'];
                    }
                } else {
                    // No es el módulo padre de la app. Miro a ver si
                    // está linkado con otro módulo
                    $linkModule = $this->varWebMod['globales'];
                    if (($linkModule['linkFromColumn'] != '') and ( $linkModule['linkToEntity'] != '') and ( $linkModule['linkToColumn'] != '')) {
                        // Está linkado con otro módulo. El prefijo será la url amigable
                        // del padre si es heredable
                        $idToLink = $datos->getColumnValue($linkModule['linkFromColumn']);
                        if (is_object($idToLink))
                            $idToLink = $idToLink->getPrimaryKeyValue();

                        $moduloPadre = new $linkModule['linkToEntity']($idToLink);
                        if ($moduloPadre->getUrlHeritable()->getIDTipo() == '1') {
                            $urlPrefix = $moduloPadre->getUrlFriendly();
                        } else {
                            $urlPrefix = "/" . $this->varEnvApp['globales']['urlPrefix'];
                        }
                        unset($moduloPadre);
                    }
                }
            } else {
                // Si no está bloqueado, se toma el indicado por el usuario y se limpia
                $urlPrefix = Textos::limpia($datos->getUrlPrefix());
                if ($urlPrefix)
                    $urlPrefix = "/" . $urlPrefix;
            }
            // -----------------------------------------------------------------
            // CALCULAR EL SLUG ------------------------------------------------
            //
            // Si está bloquedo el slug, se calcula
            if ($bloqueoSlug) {
                $slug = $datos->{"get$columnaSlug"}();
            } else {
                // Si no está bloqueado, se toma el indicado por el usuario
                $slug = $datos->getSlug();
            }

            $slug = Textos::limpia($slug);

            // -----------------------------------------------------------------
            // Construir la url amigable, límito su longitud al valor indicado
            // en la variable de entorno del proyecto
            if ($urlPrefix != '')
                $urlAmigable = $urlPrefix;
            $urlAmigable .= "/{$slug}";

            $urlAmigable = str_replace("//", "/", $urlAmigable);
            if ($this->varEnvPro['maxLengthUrlsFriendly'])
                $urlAmigable = substr($urlAmigable, 0, $this->varEnvPro['maxLengthUrlsFriendly']);
            if ($this->varEnvMod['parametros'] != '')
                $urlAmigable .= "/" . $this->varEnvMod['parametros'];

            $urls = new CpanUrlAmigables();
            $filtro = "Idioma='{$_SESSION['idiomas']['actual']}' and Entity='{$this->entity}' and IdEntity='{$datos->getPrimaryKeyValue()}'";
            $rows = $urls->cargaCondicion("Id", $filtro);
            $idUrl = $rows[0]['Id'];
            if (!$idUrl) {
                if ($_SESSION['idiomas']['actual'] == 0) {
                    // Pongo el controlador, action, template y parametros con las variables de entorno, pero...
                    $urls->setController($this->varEnvMod['controller']);
                    $urls->setAction($this->varEnvMod['action']);
                    $urls->setTemplate($this->varEnvMod['template']);
                    $urls->setParameters($this->varEnvMod['parametros']);
                } else {
                    $filtro = "Idioma='0' and Entity='{$this->entity}' and IdEntity='{$datos->getPrimaryKeyValue()}'";
                    $rows = $urls->cargaCondicion("Controller,Action,Template,Parameters", $filtro);
                    $row = $rows[0];
                    if ($row) {
                        // Si la entidad tiene padre (belongsto), pongo el controller del padre
                        $urls->setController($row['Controller']);
                        $urls->setAction($row['Action']);
                        $urls->setTemplate($row['Template']);
                        $urls->setParameters($row['Parameters']);
                    }
                }

                // Si el objeto es hijo (belongsTo), pongo el del objeto padre
                if ($datos->getBelongsTo()->getPrimaryKeyValue() > 0) {
                    $clasePadre = $datos->getClassName();
                    $urlPadre = new CpanUrlAmigables();
                    $filtro = "Idioma='{$_SESSION['idiomas']['actual']}' and Entity='{$clasePadre}' and IdEntity='{$datos->getBelongsTo()->getPrimaryKeyValue()}'";
                    $rows = $urlPadre->cargaCondicion("Controller,Action,Template,Parameters", $filtro);
                    $row = $rows[0];
                    if ($row) {
                        // Si la entidad tiene padre (belongsto), pongo el controller del padre
                        $urls->setController($row['Controller']);
                        $urls->setAction($row['Action']);
                        $urls->setTemplate($row['Template']);
                        $urls->setParameters($row['Parameters']);
                    }
                }
                $urls->setIdioma($_SESSION['idiomas']['actual']);
                $urls->setUrlFriendly($this->entity . $datos->getPrimaryKeyValue());
                $urls->setEntity($this->entity);
                $urls->setIdEntity($datos->getPrimaryKeyValue());
                $idUrl = $urls->create();
            }

            $rows = $urls->cargaCondicion("Id, Entity, IdEntity", "Idioma='{$_SESSION['idiomas']['actual']}' and UrlFriendly='{$urlAmigable}'");
            $row = $rows[0];
            if (($row['Id']) and ( $row['Entity'] != "{$this->entity}" or $row['IdEntity'] != "{$datos->getPrimaryKeyValue()}")) {
                // Ya existe esa url amigable, le pongo al final el id
                $urlAmigable .= "-" . $idUrl;
                $slug .= "-" . $idUrl;
            }
            $urls = new CpanUrlAmigables($idUrl);
            $urls->setUrlFriendly($urlAmigable);
            $urls->setEntity($this->entity);
            $urls->setIdEntity($datos->getPrimaryKeyValue());
            $urls->setPrivacy($datos->getPrivacy()->getIDTipo());
            $urls->setAccessProfileList($datos->getAccessProfileList());
            $urls->setAccessProfileListWeb($datos->getAccessProfileListWeb());
            $urls->save();
        }

        $array = array();

        if ($urlPrefix . $urlAmigable . $slug) {
            $array = array(
                'prefix' => $urlPrefix,
                'url' => $urlAmigable,
                'slug' => $slug,
            );
        }

        return $array;
    }

    protected function calculaMetatagTitle($datos) {

        $metatag = array();

        $bloqueoMetatagTitle = ($datos->getLockMetatagTitle()->getIDTipo() == '1');
        $datos->setLockMetatagTitle($bloqueoMetatagTitle);

        if ($bloqueoMetatagTitle) {
            $columnaMetatagTitle = $this->varEnvMod['fieldGeneratorMetatagTitle'];
            $columnaMetatagDescription = $this->varEnvMod['fieldGeneratorMetatagDescription'];
            //$columnaMetatagKeywords = $this->varEnvMod['fieldGeneratorMetatagKeywords'];
            if ($columnaMetatagTitle != '') {
                $metatag['title'] = $datos->{"get$columnaMetatagTitle"}();
            }
            if ($columnaMetatagDescription != '') {
                $metatag['description'] = $datos->{"get$columnaMetatagDescription"}();
            }
            //if ($columnaMetatagKeywords != '') {
            //    $metatag['keywords'] = $datos->{"get$columnaMetatagKeywords"}();
            //}
        }

        $metatag['keywords'] = $datos->getMetatagKeywords();

        return $metatag;
    }

    /**
     * Repercute los cambios realizados en el objeto del
     * idioma principal en el resto de idiomas.
     * 
     * Solo actualiza los campos que no son traducibles y los
     * traducibles que estén vacios
     * 
     * @param int $idEntidad
     * @return void
     */
    private function ActualizaIdiomas($idEntidad) {

        $objetoPrincipal = new $this->entity($idEntidad);
        // Array de columnas y valores del objeto principal
        // Utilizo este array para no utilizar los metodos getters
        // que me pueden devolver objetos relacionados
        $valoresObjetoPrincipal = $objetoPrincipal->iterator();
        unset($objetoPrincipal);

        $idiomasAdicionales = $_SESSION['idiomas']['disponibles'];

        // Recorro los idiomas adicionales
        foreach ($idiomasAdicionales as $key => $value) {
            if ($key > 0) {
                $_SESSION['idiomas']['actual'] = $key;

                $objetoSecundario = new $this->entity($idEntidad);

                foreach ($objetoSecundario->iterator() as $column => $value) {
                    $esTraducible = $this->varEnvMod['columns'][$column]['translatable'];
                    // Actualizo las columnas no traducibles y las traducibles que
                    // estén vacías.
                    if ((!$esTraducible) or ( $value == '')) {
                        $objetoSecundario->{"set$column"}($valoresObjetoPrincipal[$column]);
                    }
                }
                $objetoSecundario->save();
            }
        }

        unset($objetoSecundario);
        $_SESSION['idiomas']['actual'] = 0;
    }

    /**
     * Carga las variables web y de entorno del proyecto, app y módulo
     * Si está activo, se utiliza el servidor de caché.
     * Las variables se ponen en values.variables
     * @return void
     */
    public function setVariables($entity = '') {

        $entity = ($entity == '') ? $this->entity : $entity;

        if (!$_SESSION['memcache']['active']) {
            $array = $this->getVariables($entity);
        } else {
            $keyMemcache = md5($_SESSION['usuarioPortal']['IdPerfil']);
            $memCache = new Memcache();
            $memCache->connect($_SESSION['memcache']['host'], $_SESSION['memcache']['port']);
            $array = $memCache->get($keyMemcache);
            if (!$array) {
                $array = $this->getVariables($entity);
                $memCache->set($keyMemcache, $array, MEMCACHE_COMPRESSED, $_SESSION['memcache']['expire']);
                echo "Meto en cache ", $entity, "<br/>";
            } else {
                echo "Saco de cache ", $entity, "<br/>";
            }
        }

        return $array;
    }

    public function getVariables($entity) {

        $array = array();

        //$var = new CpanVariables('Pro', 'Env');
        //$array['EnvPro'] = $var->getValores();
        //$var = new CpanVariables('Pro', 'Web');
        //$array['WebPro'] = $var->getValores();

        $var = new CpanVariables('Mod', 'Env', $entity);
        $array['EnvMod'] = $var->getValores();
        if (count($array['EnvMod']) == 0) {
            // Cargar la configuracion del modulo (modules/moduloName/config.yml)
            $form = new Form($this->entity);            
            $array['EnvMod'] = $form->getAll();
            $var->setDatosYml($array['EnvMod']);
            $var->save();
        }

        //$var = new CpanVariables('Mod', 'Web', $entity);
        //$array['WebMod'] = $var->getValores();

        return $array;
    }

    /**
     * Actualiza la tabla de búsquedas
     * @param type $objeto
     */
    protected function ActualizaBusquedas($objeto) {

        $search = new CpanSearch();
        $search->actualiza($objeto);
        unset($search);
    }

    /**
     * Gestión de favoritos. 
     * Añade o borra un item a los favoritos 
     * 
     * @return array
     */
    public function FavoritosAction() {

        $accion = $this->request[2];

        switch ($accion) {
            case 'add': // Añadir al menú de favoritos
                $fav = new Favoritos();
                $rows = $fav->cargaCondicion("Id", "IDUsuario='{$_SESSION['usuarioPortal']['Id']}' and Controller='{$this->entity}'");
                if ($rows[0]['Id'] == '') {
                    $titulo = $this->form->getNode('title');
                    $fav->setIDUsuario($_SESSION['usuarioPortal']['Id']);
                    $fav->setController($this->entity);
                    $fav->setTitulo($titulo);
                    $id = $fav->create();
                }
                unset($fav);
                return $this->IndexAction();
                break;

            case 'delete': // Quitar del menú de favoritos
                $fav = new Favoritos();
                $ok = $fav->queryDelete("IDUsuario='{$_SESSION['usuarioPortal']['Id']}' and Controller='{$this->entity}'");
                unset($fav);
                if ($ok) {
                    include_once "modules/Index/IndexController.class.php";
                    $controller = new IndexController($this->request);
                    return $controller->IndexAction();
                }
                break;
        }
    }

    /**
     * Devuelve array (Id,Value) con los idiomas definimos en
     * la variable Web de Proyecto [globales][lang]
     * 
     * Si no se ha definido ninguno, devuelve el español
     * 
     * @return array
     */
    public function getArrayIdiomas() {

        $idiomas = new Idiomas();
        $array = $idiomas->getArrayIdiomas();
        unset($idiomas);

        return $array;
    }

    /**
     * Devuelve un array con los favoritos del
     * usuario en curso.
     * 
     * @return array Controller,Titulo
     */
    public function getFavoritos() {

        $fav = new Favoritos();
        $rows = $fav->cargaCondicion("Controller,Titulo", "IDUsuario='{$_SESSION['usuarioPortal']['Id']}'", "SortOrder");
        unset($fav);

        return $rows;
    }

    /**
     * Devuelve el texto utilizado para calcular la password
     * 
     * El texto está en el nodo <config><semillaMD5> del archivo config/config.yml
     * 
     * @return string La semilla
     */
    protected function getSemilla() {

        $semilla = "";

        $fileConfig = $_SERVER['DOCUMENT_ROOT'] . $_SESSION['appPath'] . "/config/config.yml";

        if (file_exists($fileConfig)) {
            $yaml = sfYaml::load($fileConfig);
            $semilla = $yaml['config']['semillaMD5'];
        }

        return $semilla;
    }

    /**
     * Renderiza template desde archivo
     * 
     * @param string $template El path completo al template
     * @param array $values Array de valores
     * @return string Texto html
     */
    static function renderTwigTemplate($template, $values) {

        $loader = new Twig_Loader_Array(array('index' => file_get_contents($template),));
        $twig = new Twig_Environment($loader);

        return $twig->render('index', $values);
    }

    /**
     * Renderiza template desde string
     * 
     * @param string $template El path completo al template
     * @param array $values Array de valores
     * @return string Texto html
     */
    static function renderTwigString($template, $values) {

        $loader = new Twig_Loader_Array(array('index' => $template,));
        $twig = new Twig_Environment($loader);

        return $twig->render('index', $values);
    }

    /**
     * Devuelve el nombre del archivo css asociado al template
     * @param string $template
     * @return string
     */
    static function getArchivoCss($template) {
        $archivoTemplate = str_replace('html', 'css', $template);
        if (!file_exists("modules/" . $archivoTemplate)) {
            $archivoTemplate = "_global/css.html.twig";
        }
        return $archivoTemplate;
    }

    /**
     * Devuelve el nombre del archivo js asociado al template
     * @param string $template
     * @return string
     */
    static function getArchivoJs($template) {
        $archivoTemplate = str_replace('html', 'js', $template);
        if (!file_exists("modules/" . $archivoTemplate)) {
            $archivoTemplate = "_global/js.html.twig";
        }
        return $archivoTemplate;
    }

}
