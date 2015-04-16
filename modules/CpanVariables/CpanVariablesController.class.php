<?php

/**
 * Description of CpanVariablesController
 *
 * Gestión las Variables de Entorno y Web
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 28-ago-2012 17:59:49
 */
class CpanVariablesController {

    /**
     * Variables enviadas en el request por POST o por GET
     * @var request
     */
    protected $request;

    /**
     * Objeto de la clase 'form' con las propiedades y métodos
     * del formulario obtenidos del fichero de configuracion
     * del formulario en curso
     * @var from
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
    protected $entity = "CpanVariables";
    protected $variables;

    public function __construct($request) {

        // Cargar lo que viene en el request
        $this->request = $request;

        // Cargar la configuracion del modulo (modules/moduloName/config.yml)
        $this->form = new Form($this->entity);

        $this->values['ayuda'] = $this->form->getHelpFile();

        // PARA DE QUÉ APP O MODULO ESTOY VIENDO LAS VARIABLES
        if ($this->request['METHOD'] == 'GET') {
            $ambito = $this->request['2'];
            $this->permisos = new ControlAcceso($this->request['3']);
        } else {
            $ambito = $this->request['ambito'];
            $this->permisos = new ControlAcceso($this->request['nombre']);
        }

        $this->values['permisos'] = $this->permisos->getPermisos();
        $this->values['enCurso'] = $this->values['permisos']['enCurso'];
        if ($this->values['enCurso']['app'] == $this->values['enCurso']['modulo'])
            $this->values['enCurso']['modulo'] = '';
        // --------------------------------------------------------
        //
        // LE DOY PERMISOS SOLO AL SUPER
        if ($_SESSION['usuarioPortal']['IdPerfil'] == '1') {
            $this->values['permisos']['permisosModulo']['IN'] = FALSE;
            $this->values['permisos']['permisosModulo']['DE'] = TRUE;
            $this->values['permisos']['permisosModulo']['UP'] = TRUE;
            $this->values['permisos']['permisosModulo']['VW'] = TRUE;
        } else {
            $this->values['permisos']['permisosModulo']['IN'] = FALSE;
            $this->values['permisos']['permisosModulo']['DE'] = FALSE;
            $this->values['permisos']['permisosModulo']['UP'] = TRUE;
            if (!isset($this->values['enCurso']['app']))
                $this->values['permisos']['permisosModulo']['VW'] = TRUE;
        }
        $this->values['request'] = $this->request;

        $includesHead = $this->form->getIncludesHead();

        $this->values['twigCss'] = $includesHead['twigCss'];
        $this->values['twigJs'] = $includesHead['twigJs'];
    }

    /**
     * Muestra el formulario de mantenimineto de las variables de entorno y web
     * a nivel global, de app, o de módulo, dependiendo de los valores GET request.
     *
     * La posicion 1 del request indica el tipo de variable. Valores posibles:
     *
     *      Web   : Variables web
     *      Env   : Variables entorno
     *
     * La posicion 2 del request indica el ámbito. Valores posibles:
     *
     *      Pro: para las variables de entorno globales al projecto
     *      App: para las variables de entorno de un aplicación
     *      Mod: para las variables de entorno de un modulo
     *
     * Si la posicion 2 es 'App' ó 'Mod', la posición 3 del request indica
     * el nombre de la aplicación o del módulo
     *
     * Si no viene nada en el request, no se mostrar
     * @return array Array(template,values)
     */
    public function indexAction($ambito = '', $tipo = '', $nombre = '') {


        if ($tipo == '')
            $tipo = $this->request[1];
        if ($tipo == '')
            $tipo = 'Env';

        if ($ambito == '')
            $ambito = $this->request[2];
        if ($ambito == '')
            $ambito = 'Pro';

        if ($nombre == '')
            $nombre = $this->request[3];

        // PUEDE VER LAS VARIABLES DE ENTORNO Y WEB ÚNICAMENTE SI ES DE PERFIL 1
        // PUEDE VER LAS VARIABLES WEB SI TIENE PERMISO
        $idPerfil = $_SESSION['usuarioPortal']['IdPerfil'];
        $permiso = ( ($idPerfil == '1') or ( ($tipo == 'Web') and ($this->values['permisos']['permisosModulo']['VW'])) );

        if ($permiso) {

            $this->variables = new CpanVariables($ambito, $tipo, $nombre);
            $this->values['errores'] = array_merge((array) $this->values['errores'], (array) $this->variables->getErrores());
            $datos = $this->variables->getValores();

            switch ($ambito) {

                case 'Pro':
                    $this->ponValoresDefecto($ambito, $tipo, '', $datos);
                    if ($tipo == 'Web') {
                        // Coger la visibilidad de las variables web
                        $var = new CpanVariables($ambito, 'Env');
                        $this->values['visibilidad'] = $var->getNode('showVarWeb');
                        unset($var);
                    }
                    break;

                case 'App':
                    $this->ponValoresDefecto($ambito, $tipo, $nombre, $datos);

                    // Variables especificas
                    $datos['especificas'] = $this->getEspecificas($datos['especificas']);
                    break;

                case 'Mod':
                    $this->ponValoresDefecto($ambito, $tipo, $nombre, $datos);

                    switch ($tipo) {
                        case 'Env':

                            // Constuyo array con los nombres de las columnas del modulo(=entidad)
                            // para mostrar en el template las variables Web/Env de cada columna.
                            $archivoConfig = new Form($nombre);
                            $columnasConfig = $archivoConfig->getNode('columns');
                            unset($archivoConfig);
                            // Creo el nodo 'columns' creando si no existen o asignado valores por defecto si no tienen
                            // provinientes del 'config.yml' de cada módulo
                            // SI SE AÑADE UNA COLUMNA NUEVA EN config.yml TAMBIEN SERÁ AÑADIDA EN EL FORMULARIO DE VARIABLES
                            if (is_array($columnasConfig)) {
                                foreach ($columnasConfig as $columnaConfig => $atributosConfig) {
                                    $valoresActuales = $datos['columns'][$columnaConfig];
                                    $datos['columns'][$columnaConfig] = $this->ponAtributos((array) $valoresActuales, $atributosConfig);
                                }
                            }
                            break;

                        case 'Web':
                            // Coger la visibilidad de las variables web
                            $var = new CpanVariables($ambito, 'Env', $nombre);
                            $this->values['visibilidad'] = $var->getNode('showVarWeb');
                            unset($var);
                            break;
                    }

                    // Variables especificas
                    $datos['especificas'] = $this->getEspecificas($datos['especificas']);

                    // Valores para el desplegable de columnas
                    if (class_exists($nombre)) {
                        $entidad = new $nombre();
                        $this->values['columnas'] = $entidad->getColumnsNames();
                        unset($entidad);
                    }
                    break;
            }

            $template = $this->variables->getTemplate();
            $this->values['tipo'] = $tipo;
            $this->values['ambito'] = $ambito;
            $this->values['nombre'] = $nombre;
            $this->values['titulo'] = $this->variables->getTitulo();
            $this->values['d'] = $datos;
            $this->values['yml'] = $this->variables->getYml();
            $this->values['template'] = $this->variables->getTemplate();
            unset($this->variables);
        } else
            $template = '_global/forbiden.html.twig';

        return array(
            'template' => $template,
            'values' => $this->values,
        );
    }

    /**
     * Guarda o borra las variables y sus valores en el proyecto
     *
     * Si se trata de variables web, también actualiza el control
     * de visibilidad de estas en las variables de entorno
     * del módulo correspondiente
     *
     * @return array Array template, values
     */
    public function EditAction() {

        if ($this->request['METHOD'] == 'POST') {

            $tipo = $this->request['tipo'];
            $ambito = $this->request['ambito'];
            $nombre = $this->request['nombre'];

            switch ($this->request['accion']) {

                case 'Guardar':
                    if ($this->values['permisos']['permisosModulo']['UP']) {

                        $this->ponValoresDefecto($ambito, $tipo, $nombre, $this->request['d']);

                        $variables = new CpanVariables($ambito, $tipo, $nombre);
                        $variables->setDatosYml($this->request['d']);
                        $variables->save();
                        $this->values['errores'] = $variables->getErrores();
                        if (count($this->values['errores']) == 0)
                            $_SESSION['VARIABLES'][$tipo . $ambito] = $this->request['d'];

                        unset($variables);

                        return $this->indexAction($ambito, $tipo, $nombre);
                    } else {
                        return array('template' => '_global/forbiden.html.twig', 'values' => $this->values);
                    }

                    break;

                case 'Borrar':
                    if ($this->values['permisos']['permisosModulo']['DE']) {
                        $variables = new CpanVariables($ambito, $tipo, $nombre);
                        $variables->erase();
                        $this->values['errores'] = $variables->getErrores();
                        unset($variables);

                        return $this->indexAction($ambito, $tipo, $nombre);
                    } else {
                        return array('template' => '_global/forbiden.html.twig', 'values' => $this->values);
                    }

                    break;
            }
        } else
            return array('template' => '_global/forbiden.html.twig', array());
    }

    /**
     * Edita las variables de entorno de un nodo
     *
     * @return array Array template, values
     */
    public function EditNodeAction() {

        if ($_SESSION['usuarioPortal']['IdPerfil'] == '1') {

            switch ($this->request['METHOD']) {

                case 'GET':

                    $tipo = $this->request['3'];
                    $ambito = $this->request['2'];
                    $nombre = $this->request['4'];
                    $columna = $this->request['5'];
                    $titulo = "Variables {$this->request['3']} de '{$columna}'";

                    $variables = new CpanVariables($ambito, $tipo, $nombre);
                    $variablesColumna = $variables->getColumn($columna);
                    unset($variables);

                    $archivoConfig = new Form($nombre);
                    $columnasConfig = $archivoConfig->getNode('columns');
                    unset($archivoConfig);
                    $datos = $this->ponAtributos($variablesColumna, $columnasConfig[$columna]);

                    $this->values['titulo'] = $titulo;
                    $this->values['tipo'] = $tipo;
                    $this->values['ambito'] = $ambito;
                    $this->values['nombre'] = $nombre;
                    $this->values['columna'] = $columna;
                    $this->values['d'] = $datos;

                    $template = $this->entity . '/formPlantillaVariables.html.twig';
                    break;

                case 'POST':

                    $tipo = $this->request['tipo'];
                    $ambito = $this->request['ambito'];
                    $nombre = $this->request['nombre'];
                    $columna = $this->request['columna'];
                    $titulo = "Variables {$tipo} de '{$columna}'";

                    $variables = new CpanVariables($ambito, $tipo, $nombre);
                    $variables->setColumn($columna, $this->request['d']);
                    $variables->save();

                    $this->values['titulo'] = $titulo;
                    $this->values['tipo'] = $tipo;
                    $this->values['ambito'] = $ambito;
                    $this->values['nombre'] = $nombre;
                    $this->values['columna'] = $columna;
                    $this->values['errores'] = $variables->getErrores();

                    $archivoConfig = new Form($nombre);
                    $columnasConfig = $archivoConfig->getNode('columns');
                    unset($archivoConfig);
                    $datos = $this->ponAtributos($variables->getColumn($columna), $columnasConfig[$columna]);
                    $this->values['d'] = $datos;
                    unset($variables);

                    $template = $this->entity . '/formPlantillaVariables.html.twig';
                    break;
            }
        } else
            $template = '_global/forbiden.html.twig';

        return array('template' => $template, 'values' => $this->values);
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
        if (!is_file($file) or ($this->form->getHelpFile() == '')) {
            $template = "_help/noFound.html.twig";
        }

        $values['title'] = $this->form->getTitle();
        $values['idVideo'] = $this->form->getIdVideo();
        $values['urlVideo'] = $this->form->getUrlVideo();

        return array('template' => $template, 'values' => $values);
    }

    /**
     * Pone los valores por defecto de las variables en base a los establecido
     * en el config del proyecto, aplicacion ó módulo
     *
     * @param string $ambito
     * @param string $tipo
     * @param string $nombre
     * @param array $datos
     * @return void
     */
    private function ponValoresDefecto($ambito, $tipo, $nombre, &$datos) {

        switch ($ambito) {
            case 'Pro':
                switch ($tipo) {
                    case 'Env':
                        // Leo el config global del Cpanel
                        $archivoConfig = sfYaml::load('config/config.yml');
                        $valores = $archivoConfig['config'];
                        if ($datos['maxLengthUrlsFriendly'] == '')
                            $datos['maxLengthUrlsFriendly'] = $valores['maxLengthUrlsFriendly'];
                        if ($datos['allowTypes'] == '')
                            $datos['allowTypes'] = $valores['allowTypes'];
                        if ($datos['numMaxPages'] == '')
                            $datos['numMaxPages'] = $valores['numMaxPages'];
                        if ($datos['numMaxDocuments'] == '')
                            $datos['numMaxDocuments'] = $valores['numMaxDocuments'];
                        if ($datos['numMaxGalery'] == '')
                            $datos['numMaxGalery'] = $valores['numMaxGalery'];
                        if ($datos['numMaxVideos'] == '')
                            $datos['numMaxVideos'] = $valores['numMaxVideos'];
                        if ($datos['numMaxAudios'] == '')
                            $datos['numMaxAudios'] = $valores['numMaxAudios'];
                        if ($datos['modulosConEtiquetas'] == '')
                            $datos['modulosConEtiquetas'] = $valores['modulosConEtiquetas'];
                        if ($datos['blockRobots'] == '')
                            $datos['blockRobots'] = $valores['blockRobots'];
                        if ($datos['visitas']['activo'] == '')
                            $datos['visitas']['activo'] = $valores['visitas']['activo'];
                        if ($datos['visitas']['ws'] == '')
                            $datos['visitas']['ws'] = $valores['visitas']['ws'];
                        if ($datos['visitas']['frecuenciaHorasBorrado'] == '')
                            $datos['visitas']['frecuenciaHorasBorrado'] = $valores['visitas']['frecuenciaHorasBorrado'];
                        break;
                    case 'Web' :
                        // Leo el config global del Cpanel
                        $archivoConfig = sfYaml::load('config/config.yml');
                        $signatures = $archivoConfig['config']['signatures'];

                        if ($datos['signatures']['links'] == '')
                            $datos['signatures']['links'] = $signatures['links'];
                        if ($datos['signatures']['services'] == '')
                            $datos['signatures']['services'] = $signatures['services'];
                        if ($datos['signatures']['locations'] == '')
                            $datos['signatures']['locations'] = $signatures['locations'];

                        $mail = $archivoConfig['config']['mailer'];
                        foreach ($mail as $key => $value)
                            if ($datos['mail'][$key] == '')
                                $datos['mail'][$key] = $value;

                        $meta = $archivoConfig['config']['meta'];
                        foreach ($meta as $key => $value)
                            if ($datos['meta'][$key] == '')
                                $datos['meta'][$key] = $value;
                        break;
                }
                break;

            case 'App':
                /**
                  if ($tipo == 'Env') {
                  if ($datos['globales']['urlPrefix'] == '') {
                  $archivoConfig = new Form($nombre);
                  $datos['globales']['urlPrefix'] = $archivoConfig->getNode('urlPrefix');
                  unset($archivoConfig);
                  }
                  }
                 */ break;

            case 'Mod':

                $archivoConfig = new Form($nombre);

                if ($tipo == 'Env') {

                    if ($datos['isModuleRoot'] == '')
                        $datos['isModuleRoot'] = $archivoConfig->getNode('isModuleRoot');
                    if ($datos['translatable'] == '')
                        $datos['translatable'] = $archivoConfig->getNode('translatable');
                    if ($datos['searchable'] == '')
                        $datos['searchable'] = $archivoConfig->getNode('searchable');                    
                    if ($datos['showCommonFields'] == '')
                        $datos['showCommonFields'] = $archivoConfig->getNode('showCommonFields');
                    if ($datos['numMaxRecords'] == '')
                        $datos['numMaxRecords'] = $archivoConfig->getNode('numMaxRecords');
                    if ($datos['numberOfImages'] == '')
                        $datos['numberOfImages'] = $archivoConfig->getNode('numberOfImages');
                    if ($datos['withMetadata'] == '')
                        $datos['withMetadata'] = $archivoConfig->getNode('withMetadata');
                    if ($datos['addMetadata'] == '')
                        $datos['addMetadata'] = $archivoConfig->getNode('addMetadata');
                    if ($datos['deleteMetadata'] == '')
                        $datos['deleteMetadata'] = $archivoConfig->getNode('deleteMetadata');                    
                    if ($datos['withGalery'] == '')
                        $datos['withGalery'] = $archivoConfig->getNode('withGalery');
                    if ($datos['withDocuments'] == '')
                        $datos['withDocuments'] = $archivoConfig->getNode('withDocuments');
                    if ($datos['withVideos'] == '')
                        $datos['withVideos'] = $archivoConfig->getNode('withVideos');
                    if ($datos['withAudios'] == '')
                        $datos['withAudios'] = $archivoConfig->getNode('withAudios');

                    $maxSizes = $archivoConfig->getNode('maxSizes');
                    if ($datos['maxSizes']['image'] == '')
                        $datos['maxSizes']['image'] = $maxSizes['image'];
                    if ($datos['maxSizes']['document'] == '')
                        $datos['maxSizes']['document'] = $maxSizes['document'];
                    if ($datos['maxSizes']['video'] == '')
                        $datos['maxSizes']['video'] = $maxSizes['video'];
                    if ($datos['maxSizes']['audio'] == '')
                        $datos['maxSizes']['audio'] = $maxSizes['audio'];

                    $galery = $archivoConfig->getNode('galery');
                    if ($datos['galery']['maxWidthImage'] == '')
                        $datos['galery']['maxWidthImage'] = $galery['maxWidthImage'];
                    if ($datos['galery']['maxHeightImage'] == '')
                        $datos['galery']['maxHeightImage'] = $galery['maxHeightImage'];
                    if ($datos['galery']['widthThumbnail'] == '')
                        $datos['galery']['widthThumbnail'] = $galery['widthThumbnail'];
                    if ($datos['galery']['heightThumbnail'] == '')
                        $datos['galery']['heightThumbnail'] = $galery['heightThumbnail'];

                    if ($datos['controller'] == '')
                        $datos['controller'] = $archivoConfig->getNode('controller');
                    if ($datos['action'] == '')
                        $datos['action'] = $archivoConfig->getNode('action');
                    if ($datos['template'] == '')
                        $datos['template'] = $archivoConfig->getNode('template');
                    if ($datos['parametros'] == '')
                        $datos['parametros'] = $archivoConfig->getNode('parametros');

                    if ($datos['urlFriendlyManagement'] == '')
                        $datos['urlFriendlyManagement'] = $archivoConfig->getNode('urlFriendlyManagement');
                    if ($datos['fieldGeneratorUrlFriendly'] == '')
                        $datos['fieldGeneratorUrlFriendly'] = $archivoConfig->getNode('fieldGeneratorUrlFriendly');

                    if ($datos['metatagTitleManagement'] == '')
                        $datos['metatagTitleManagement'] = $archivoConfig->getNode('metatagTitleManagement');
                    if ($datos['fieldGeneratorMetatagTitle'] == '')
                        $datos['fieldGeneratorMetatagTitle'] = $archivoConfig->getNode('fieldGeneratorMetatagTitle');
                    if ($datos['fieldGeneratorMetatagDescription'] == '')
                        $datos['fieldGeneratorMetatagDescription'] = $archivoConfig->getNode('fieldGeneratorMetatagDescription');
                    if ($datos['fieldGeneratorMetatagKeywords'] == '')
                        $datos['fieldGeneratorMetatagKeywords'] = $archivoConfig->getNode('fieldGeneratorMetatagKeywords');

                    //if ($datos['ordenesWeb'] == '')
                    //    $datos['ordenesWeb'] = $archivoConfig->getNode('ordenes_web');
                    $ordenesWeb = $archivoConfig->getNode('ordenes_web');
                    if (is_array($ordenesWeb)) {
                        foreach ($ordenesWeb as $key => $value)
                            if (!is_array($datos['ordenesWeb'][$key]))
                                $datos['ordenesWeb'][$key] = $value;
                    }
                    unset($archivoConfig);
                }

                if ($tipo == 'Web') {
                    // Variables web globales
                    $linkModule = $archivoConfig->getNode('linkModule');
                    if ($datos['globales']['linkFromColumn'] == '')
                        $datos['globales']['linkFromColumn'] = $linkModule['fromColumn'];
                    if ($datos['globales']['linkToEntity'] == '')
                        $datos['globales']['linkToEntity'] = $linkModule['toEntity'];
                    if ($datos['globales']['linkToColumn'] == '')
                        $datos['globales']['linkToColumn'] = $linkModule['toColumn'];

                    // Variables web específicas
                    $varWebEspecificas = sfYaml::load("modules/{$nombre}/varWeb.yml");
                    if (is_array($varWebEspecificas)) {
                        foreach ($varWebEspecificas as $variable => $valores) {
                            $valorActual = $datos['especificas'][$variable]['value'];
                            $datos['especificas'][$variable]['caption'] = $valores['caption'];
                            $datos['especificas'][$variable]['values'] = $valores['values'];
                            $datos['especificas'][$variable]['value'] = ($valorActual == '') ?
                                    $valores['default'] :
                                    $valorActual;
                        }
                    }
                    unset($archivoConfig);
                }
                break;
        }
    }

    /**
     * Recibe dos array:
     *
     *      el primero tiene los atributos y valores que hay en el archivo
     *      de variables de entorno
     *
     *      el segundo tiene los atributos predefinidos en el config.yml del modulo
     *
     * Devuelve un array de atributos para la columna obtenido 'mezclando' los dos,
     * teniendo en cuenta que los valores que prevalecen son los del archivo
     * de variables de entorno. Si en el config.yml hubiera algún atributo nuevo,
     * este es incluido.
     *
     * En el array devuelto se incluye (eventualmente) un elemento adicional 'listaValores' que
     * es un array con los valores posibles a tomar por el atributo.
     *
     * @param array $datos Array de los atributos que hay en las variables de entorno
     * @param array $atributosConfig Array de los atributos que hay en el config.yml
     * @return array Array de atributos de una columna
     */
    private function ponAtributos(array $datos, array $atributosConfig) {

        // Compruebo que exista la columna, si no la creo
        if (!is_array($datos)) {
            $datos = array();
        }

        foreach (VariablesEnv::$varEnvMod as $keyVar => $keyColumnaConfig) {
            if (!isset($datos[$keyVar]))
                $datos[$keyVar] = $atributosConfig[$keyColumnaConfig];
        }
        // SI LA COLUMNA ESTA VINCULADA A UNA ENTIDAD, CREA LA LISTA DE VALORES
        if (($atributosConfig['aditional_filter']['entity'] != '') and ($atributosConfig['aditional_filter']['type'] == 'select')) {
            $entidad = $atributosConfig['aditional_filter']['entity'];          
            $claseConId = explode(',', $entidad);
            if (class_exists($claseConId[0]))
                $objeto = new $claseConId[0]($claseConId[1]);
            $metodo = $atributosConfig['aditional_filter']['method'];
            //$objeto = new $entidad();
            $listaValores = $objeto->$metodo($atributosConfig['aditional_filter']['params'], 0);
            $datos['listaValores'] = $listaValores;
        }

        return $datos;
    }

    /**
     * Construye un array con las variables específicas en base
     * a los establecido en el archivo yml de la app o del módulo y los valores
     * definidos en el proyecto.
     *
     * @param type $datosEspecificas
     * @return array Array con las variables específicas
     */
    private function getEspecificas($datosEspecificas) {

        foreach ($this->variables->getArrayEspecificas() as $key => $especifica) {
            $valorActual = $datosEspecificas[$key];

            $especificas[$key] = array(
                'caption' => $especifica['caption'],
                'value' => $valorActual,
                'values' => $especifica['values'],
            );
        }
        return $especificas;
    }

    /**
     * Construye un array con las variables específicas en base
     * a los establecido en el archivo yml del módulo y los valores
     * definidos en el proyecto.
     *
     * @param type $datosEspecificas
     * @return array Array con las variables específicas
     */
    private function getShowVarWeb($datosShowVarWeb) {

        foreach ($this->variables->getNode('showVarWeb') as $key => $value) {
            $valorActual = $datosShowVarWeb[$key];

            $showVarWeb[$key] = array(
                'value' => $valorActual,
                'caption' => $caption,
            );
        }
        return $showVarWeb;
    }

}

?>
