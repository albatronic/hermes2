<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 16.10.2012 16:33:56
 */

/**
 * @orm:Entity(PcaeUsuarios)
 */
class PcaeUsuarios extends PcaeUsuariosEntity {

    public function __toString() {
        return ($this->Id != '') ? $this->getId() : '';
    }

    public function create() {

        $this->setPublish(1);
        $this->setPrivacy(0);
        return parent::create();
    }

    /**
     * Recalcula el passw y guarda
     * @return boolean
     */
    public function save() {
        $config = sfYaml::load('config/config.yml');
        $this->Password = md5($this->Password . $config['config']['semillaMD5']);

        return parent::save();
    }

    /**
     * Devuelve el nombre concatenado con los apellidos
     * @return string
     */
    public function getNombreApellidos() {
        return trim($this->getNombre() . " " . $this->getApellidos());
    }

    /**
     * Devuelve los apellidos concatenado con el nombre
     * @return string
     */
    public function getApellidosNombre() {
        $texto = $this->getApellidos() . ", " . $this->getNombre();
        if ($texto == ", ")
            $texto = "";
        return $texto;
    }

    /**
     * Devuelve un array de objetos \PcaeEmpresas
     * a las que tiene acceso el usuario en curso
     *
     * @return array Array de objetos \PcaeEmpresas
     */
    public function getEmpresas() {

        $empresas = array();

        $empUsu = new PcaeEmpresasUsuarios();
        $rows = $empUsu->cargaCondicion("IdEmpresa", "IdUsuario='{$this->Id}'");
        unset($empUsu);

        foreach ($rows as $row)
            $empresas[] = new PcaeEmpresas($row['IdEmpresa']);

        return $empresas;
    }

    /**
     * Devuelve un array con los opciones de menu
     * para el usuario en curso y la empresa $idEmpresa.
     *
     * @param integer $idEmpresa EL id de empresa
     * @return array
     */
    public function getArrayMenu($idEmpresa) {

        $menu = array();

        switch ($this->getPerfilEmpresa($idEmpresa)->getId()) {

            // Super, tiene acceso a todo
            case '1':
                $menu = array('Empresas', 'Usuarios', 'Proyectos');
                break;

            // Administrador de empresa
            case '2':
                $menu = array('Asignar Usuarios');
                break;

            // Acceso, solo tiene permiso de acceso
            case '3':
                break;
        }
        return $menu;
    }

    /**
     * Devuelve un array anidado con las empresa, proyectos
     * y aplicaciones a los que tiene acceso el usuario en curso.
     *
     * @return array
     */
    public function getArrayAccesos() {

        $accesos = array();

        $permisos = new PcaePermisos();
        $rows = $permisos->cargaCondicion("IdEmpresa,IdProyecto,IdApp", "IdUsuario='{$this->Id}'", "IdEmpresa,IdProyecto,IdApp ASC");
        unset($permisos);
        $empAnt = '';
        $proAnt = '';

        foreach ($rows as $row) {

            if ($empAnt != $row['IdEmpresa']) {

                $empresa = new PcaeEmpresas($row['IdEmpresa']);

                $accesos['empresas'][$row['IdEmpresa']] = array(
                    'empresa' => $empresa->getRazonSocial(),
                    'perfil' => $this->getPerfilEmpresa($row['IdEmpresa'])->getPerfil(),
                );
                unset($empresa);
            }

            if ($proAnt != $row['IdProyecto']) {

                $proyecto = new PcaeProyectos($row['IdProyecto']);

                $accesos['empresas'][$row['IdEmpresa']]['proyectos'][$row['IdProyecto']] = array(
                    'proyecto' => $proyecto->getProyecto(),
                );
                unset($proyecto);
            }

            $app = new PcaeApps($row['IdApp']);
            $proyectoApp = new PcaeProyectosApps();
            $keyProyectoApp = $proyectoApp->cargaCondicion("PrimaryKeyMD5", "IdProyecto='{$row['IdProyecto']}' AND IdApp='{$row['IdApp']}'");

            $accesos['empresas'][$row['IdEmpresa']]['proyectos'][$row['IdProyecto']]['apps'][$row['IdApp']] = array(
                'aplicacion' => $app->getAplicacion(),
                'url' => $app->getUrl(),
                'IdProyectoApp' => $keyProyectoApp[0]['PrimaryKeyMD5'],
            );
            unset($app);
            unset($proyectoApp);

            $proAnt = $row['IdProyecto'];
            $empAnt = $row['IdEmpresa'];
        }

        return $accesos;
    }

    /**
     * Devuelve el objeto PcaePerfiles correspondiente al
     * usuario en curso y la empresa $idEmpresa.
     *
     * @param integer $idEmpresa El id de empresa
     * @return \PcaePerfiles Objeto \PcaePerfiles
     */
    public function getPerfilEmpresa($idEmpresa) {

        $empUsu = new PcaeEmpresasUsuarios();
        $rows = $empUsu->cargaCondicion("IdPerfil", "IdEmpresa='{$idEmpresa}' AND IdUsuario='{$this->Id}'");
        unset($empUsu);

        return new PcaePerfiles($rows[0]['IdPerfil']);
    }

    /**
     * Devuelve un array (Id,Value) con los usuarios adscritos 
     * a la empresa $idEmpresa
     * 
     * @param integer $idEmpresa El id de la empresa
     * @return array Array de usuarios
     */
    public function getUsuariosEmpresa($idEmpresa) {

        $usuarios = array();

        $usu = new PcaeEmpresasUsuarios();
        $rows = $usu->cargaCondicion("IdUsuario", "IdEmpresa='{$idEmpresa}'");
        unset($usu);

        foreach ($rows as $row) {
            if ($row['IdUsuario'] != '1') {
                $usu = new PcaeUsuarios($row['IdUsuario']);
                $usuarios[] = array("Id" => $row['IdUsuario'], "Value" => $usu->getApellidosNombre() . " <" . $usu->getEMail() . ">");
            }
        }
        unset($usu);

        return $usuarios;
    }

}

?>