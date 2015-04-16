<?php

/**
 * Description of IndexController
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 28-may-2011
 *
 */
class IndexController extends Controller {

    protected $entity = "Index";
    protected $parentEntity = "";

    public function __construct($request) {

        // Cargar lo que viene en el request
        $this->request = $request;

        // Cargar la configuracion del modulo (modules/moduloName/config.yaml)
        $this->form = new Form($this->entity);

        $this->cargaValores();

        // Instanciar el objeto listado con los parametros del modulo
        // y los eventuales valores del filtro enviados en el request
        $this->listado = new Listado($this->form, $this->request);

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
        $this->values['linkBy'] = array(
            'id' => $this->form->getLinkBy(),
            'value' => '',
        );

        $this->values['listado'] = array(
            'filter' => $this->listado->getFilter(),
        );

        // Si se ha indicado una entidad en el config.yml del controlador
        // pero no se ha definido la conexion, se muestra un error
        if (($this->form->getEntity()) and ( !$this->form->getConection())) {
            echo "No se ha definido la conexión para la entidad: " . $this->entity;
        }

        // Menu de favoritos
        $this->values['favoritos'] = $this->getFavoritos();
    }

    /**
     * Muestra un template con los accesos favoritos del usuario
     * @return array
     */
    public function IndexAction() {

        $this->values['sucursal'] = new Sucursales($_SESSION['suc']);
        //print_r($_SESSION['usuarioPortal']);
        if ($_SESSION['tpv']) {
            $this->values['dashBoard'] = $this->getDashBoard();
        }

        return array('template' => 'Index/index.html.twig', 'values' => $this->values);
    }

    public function LoginAction() {

        switch ($this->request['METHOD']) {
            case 'GET':
                $template = $this->entity . '/login.html.twig';
                break;
            case 'POST':
                $user = new PcaeUsuarios();
                $usuario = $user->find("EMail", $this->request['email']);
                unset($user);

                if ($usuario->getEMail() != '') {

                    if ($usuario->getPassword() == md5($this->request['password'] . $this->getSemilla())) {

                        $_SESSION['usuarioPortal'] = array(
                            'Id' => $usuario->getId(),
                            'IdPerfil' => '1',
                            'Nombre' => $usuario->getNombre(),
                        );

                        //Actualizar el registro de entradas
                        $usuario->setNLogin($usuario->getNLogin() + 1);
                        $usuario->setUltimoLogin(date('Y-m-d H:i:s'));
                        $usuario->save();

                        // Crear la variable de sesion con el array de
                        // las empresas, proyectos y apps accesibles.
                        $_SESSION['usuarioPortal']['accesosPortal'] = $usuario->getArrayAccesos();

                        $this->values['accesosPortal'] = $_SESSION['usuarioWeb']['accesosPortal'];
                        //print_r($this->values['accesosPortal']);
                        $template = $this->entity . "/proyectos.html.twig";
                    } else {
                        $this->values['email'] = $this->request['email'];
                        $this->values['errorPassword'] = true;
                        return $this->IndexAction();
                    }
                } else {
                    $this->values['errorUsuario'] = true;
                    return $this->IndexAction();
                }
                break;
        }

        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Acciones a realizar cuando se selecciona otra empresa
     * Se cambia el valor de la variable de session 'emp'
     * y se recargan las sucursales de la nueva empresa.
     * @return
     */
    public function EmpresaAction() {
        //Activo la empresa nueva
        $_SESSION['emp'] = $this->request['Empresa'];

        //Buscar las sucursales de la nueva empresa seleccionada
        $user = new Agentes($_SESSION['usuarioPortal']['Id']);
        $_SESSION['usuarioPortal']['sucursales'] = $user->getSucursales($_SESSION['emp']);

        //Activo la sucursal nueva
        $_SESSION['suc'] = $_SESSION['usuarioPortal']['sucursales'][0]['Id'];

        //Desactivo el tpv para forzar a la elección de un nuevo
        unset($_SESSION['tpv']);

        $this->values['sucursal'] = new Sucursales($_SESSION['suc']);
        return array('template' => 'Index/index.html.twig', 'values' => $this->values);
    }

    /**
     * Acciones a realizar cuando se selecciona otra sucursal
     * Se cambia el valor de la variable de session 'suc'
     * @return
     */
    public function SucursalAction() {

        $_SESSION['suc'] = $this->request['Sucursal'];

        //Desactivo el tpv para forzar a la elección de un nuevo
        unset($_SESSION['tpv']);

        $this->values['sucursal'] = new Sucursales($_SESSION['suc']);
        return array('template' => 'Index/index.html.twig', 'values' => $this->values);
    }

    /**
     * Activa el código de tpv utilizado
     * @return <type>
     */
    public function setTpvAction() {

        $_SESSION['tpv'] = $this->request['IDTpv'];
        return $this->IndexAction();
    }

    protected function cargaValores() {
        if (!isset($_SESSION['usuarioPortal']['menu'])) {
            // Está logeado (viene del portal), pero es la primera vez que entra
            $_SESSION['usuarioPortal']['accesosPortal'] = array();

            // Carga la cadena de conexion a la base de datos del proyecto
            $proyectoApp = new PcaeProyectosApps();
            $proyectoApp = $proyectoApp->find('PrimaryKeyMD5', $this->request[1]);
            //print_r($proyectoApp);
            $_SESSION['project']['Id'] = $proyectoApp->getId();
            $_SESSION['project']['IdEmpresa'] = $proyectoApp->getIdProyecto()->getIdEmpresa()->getId();
            $_SESSION['project']['empresa'] = $proyectoApp->getIdProyecto()->getIdEmpresa()->getRazonSocial();
            $_SESSION['project']['title'] = $proyectoApp->getIdProyecto()->getProyecto();
            $_SESSION['project']['url'] = $proyectoApp->getUrl();
            $_SESSION['project']['conection'] = array(
                'dbEngine' => $proyectoApp->getDbEngine(),
                'host' => $proyectoApp->getHost(),
                'user' => $proyectoApp->getUser(),
                'password' => $proyectoApp->getPassword(),
                'database' => $proyectoApp->getDatabase(),
            );
            // Carga la cadena de conexión al servidor ftp del proyecto
            $_SESSION['project']['ftp'] = array(
                'server' => $proyectoApp->getFtpServer(),
                'port' => $proyectoApp->getFtpPort(),
                'timeout' => $proyectoApp->getFtpTimeout(),
                'folder' => $proyectoApp->getFtpFolder(),
                'user' => $proyectoApp->getFtpUser(),
                'password' => $proyectoApp->getFtpPassword(),
            );

            unset($proyectoApp);

            // Establece el perfil del usuario para el proyecto y carga
            // el menú en base a su perfil
            $usuario = new Agentes($_SESSION['usuarioPortal']['Id']); //print_r($usuario);
            if ($usuario->getStatus()) {
                $idPerfil = $usuario->getIDPerfil()->getPrimaryKeyValue();
                $_SESSION['usuarioPortal']['IdPerfil'] = $idPerfil;
                $_SESSION['usuarioPortal']['IdRol'] = $usuario->getIDRol()->getIDTipo();
                $_SESSION['usuarioPortal']['email'] = $usuario->getEMail();

                $_SESSION['emp'] = $_SESSION['project']['IdEmpresa'];
                $_SESSION['usuarioPortal']['sucursales'] = $usuario->getSucursales('', false);
                $_SESSION['suc'] = $_SESSION['usuarioPortal']['sucursales'][0]['Id'];
                $_SESSION['usuarioPortal']['menu'] = $usuario->getArrayMenu();
                // Carga las variables de entorno y web del proyecto
                $this->cargaVariables();

                // Activar la versión
                $var = new CpanVariables('Pro', 'Web');
                $erp = $var->getNode('erp');
                $_SESSION['ver'] = ($erp['version'] != '') ? $erp['version'] : '0';

                // Activar o no la posibilidad de cambiar precios
                $rolesCambioPrecio = explode(",", trim($erp['rolesCambioPrecios']));
                $_SESSION['usuarioPortal']['cambioPrecios'] = in_array($_SESSION['usuarioPortal']['IdRol'], $rolesCambioPrecio);

                // Poner en la sesión la política de actualización de precios en base
                $_SESSION['usuarioPortal']['actuPrecios'] = ($erp['actuPrecios'] != '') ? $erp['actuPrecios'] : 'PVP';

                // Poner en la sesión el margén mínimo de venta
                $_SESSION['usuarioPortal']['margenMinimo'] = ($erp['alertaMargen'] > 0) ? $erp['alertaMargen'] : 0;

                // Poner en la sesión si se generar alertas o no por falta de stock
                $_SESSION['usuarioPortal']['alertaStock'] = $erp['alertaStock'];

                // Establece los idiomas en base a la varible web del proyecto
                /**
                  $langs = trim($_SESSION['VARIABLES']['WebPro']['globales']['lang']);
                  $_SESSION['idiomas']['disponibles'] = ($langs == '') ? array('0' => 'es') : explode(",", $langs);

                  if (!isset($_SESSION['idiomas']['actual'])) {
                  $_SESSION['idiomas']['actual'] = 0;
                  }
                 */
            }
            //print_r($_SESSION);
            unset($usuario);
        }
    }

    /**
     * Muestra el template para el caso que un usuario
     * no logeado en el portal quiera entrar directamente.
     *
     * Además, borra cualquier dato que hubiese en $_SESSION
     *
     * @return array Array template, values
     */
    public function NoLogedAction() {

        unset($_SESSION);

        return array(
            'template' => $this->entity . '/noLoged.html.twig',
            'values' => $this->values,
        );
    }

    /**
     * Carga en la sesión las variables de entorno y web del proyecto
     */
    protected function cargaVariables() {

        // Variables de entorno del proyecto
        if (!isset($_SESSION['VARIABLES']['EnvPro'])) {
            $variables = new CpanVariables('Pro', 'Env');
            $this->varEnvPro = $variables->getValores();
            $_SESSION['VARIABLES']['EnvPro'] = $this->varEnvPro;
        } else
            $this->varEnvPro = $_SESSION['VARIABLES']['EnvPro'];
        $this->values['varEnvPro'] = $this->varEnvPro;

        // Variables web del proyecto
        if (!isset($_SESSION['VARIABLES']['WebPro'])) {
            $variables = new CpanVariables('Pro', 'Web');
            $this->varWebPro = $variables->getValores();
            $_SESSION['VARIABLES']['WebPro'] = $this->varWebPro;
        } else
            $this->varWebPro = $_SESSION['VARIABLES']['WebPro'];
        $this->values['varWebPro'] = $this->varWebPro;

        unset($variables);
    }

    public function ImportarAction() {
        set_time_limit(0);
        //CARGAR EL ARCHIVO XML
        cargaDatosDemo::LeeArchivo('tmp/fixturesDemo.xml');

        //VACIAR LAS TABLAS
        cargaDatosDemo::VaciarTablas();

        //Creo las familias y subfamilias en las tablas de la BD en base al array bidemensional
        cargaDatosDemo::CrearFamilias(cargaDatosDemo::getFamilias());

        //Cargo los articulos en la BD.
        $fallos = cargaDatosDemo::CargaArticulos();
        echo "Articulos Fallidos: ", $fallos;

        return $this->IndexAction();
    }

    /**
     * Activa o desactiva el modo debuger de Twig
     * 
     * Para activar: Index/Debuger/true
     * Para desactivar: Index/Debuger/false
     * 
     * @return void
     */
    public function DebugerAction() {

        $fileConfig = 'config/config.yml';
        $array = sfYaml::load($fileConfig);
        $array['config']['twig']['debug_mode'] = ($this->request[2] == 'true') ? true : false;
        $yml = sfYaml::dump($array, 4);
        $archivo = new Archivo($fileConfig);
        $archivo->write($yml);
        $archivo->close();

        echo "Modo debuger Twig: ", $array['config']['twig']['debug_mode'], "----";

        return $this->IndexAction();
    }

    /**
     * Genera el array con la información para el dashboard
     * 
     * @return array
     */
    public function getDashBoard() {

        $idRol = $_SESSION['usuarioPortal']['IdRol'];

        $array = array();

        $array['presupuestos'] = DashBoard::getPresupuestos();

        // La tesoreria la muestro si el rol es admon o super
        if ($idRol == '0' or $idRol == '9')
            $array['tesoreria'] = DashBoard::getTesoreria();

        $array['logistica']['entradasRetrasadas'] = DashBoard::getEntradasRetrasadas();
        $array['logistica']['entradasHoy'] = DashBoard::getEntradasHoy();
        $array['logistica']['pendienteServir'] = DashBoard::getPteServir();
        $array['logistica']['roturasStock'] = DashBoard::getRoturasStock();

        $array['tops']['clientes'] = DashBoard::getTopNClientes();
        $array['tops']['articulos'] = DashBoard::getTopNArticulos();
        $array['tops']['categorias'] = DashBoard::getTopNCategorias();
        $array['tops']['familias'] = DashBoard::getTopNFamilias();
        $array['tops']['comerciales'] = DashBoard::getTopNComerciales();

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

}
