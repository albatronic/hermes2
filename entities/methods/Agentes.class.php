<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 24.08.2012 17:27:15
 */

/**
 * @orm:Entity(core_usuarios)
 */
class Agentes extends AgentesEntity {

    public function __toString() {
        return ($this->IDAgente) ? $this->getNombreApellidos() : "";
    }

    public function getNombreApellidos() {

        $usuario = new PcaeUsuarios($this->IDAgente);
        $nombreApellidos = $usuario->getNombre() . " " . $usuario->getApellidos();
        unset($usuario);

        return $nombreApellidos;
    }

    public function getNombre() {
        return $this->getNombreApellidos();
    }

    public function getEMail() {
        return $this->getIDAgente()->getEmail();
    }

    public function fetchAll($column = '', $default = true) {

        $usuariosPcae = new PcaeUsuarios();

        $filtro = "(a.IDAgente = u.Id)";
        if ($_SESSION['usuarioPortal']['Id'] !== 1)
            $filtro .= " AND (a.IDAgente<>'1')";

        $em = new EntityManager($this->getConectionName());
        $link = $em->getDbLink();
        if (is_resource($link)) {
            $query = "select a.IDAgente as Id, concat(u.Nombre,' ',u.Apellidos) as Value
                             from 
                                {$this->getDataBaseName()}.{$this->getTableName()} as a,
                                {$usuariosPcae->getDataBaseName()}.{$usuariosPcae->getTableName()} as u
                            where {$filtro}";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();

            if ($default)
                $rows[] = array('Id' => '', 'Value' => '** Todos **');
        }
        unset($em);

        return $rows;
    }

    /**
     * Devuelve un array de dos dimensiones con las aplicaciones
     * y módulos a los que tiene acceso el ususario en base a su perfil
     *
     * @return array $menu El el array con el menu
     */
    public function xxx_getArrayMenu() {

        $menu = array();

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "
                select m.CodigoApp ,p.NombreModulo, p.Funcionalidades, m.Publish
                from {$this->getDataBaseName()}.ErpPermisos as p, {$this->getDataBaseName()}.ErpModulos as m
                where  m.NombreModulo = p.NombreModulo and
                p.IdPerfil = '{$this->getIDPerfil()->getIDPerfil()}' AND
                LOCATE('AC',p.Funcionalidades)
                order by m.Id ASC ,m.SortOrder ASC";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();
        } else
            echo "NO HAY CONEXION CON LA BASE DE DATOS";
        unset($em);

        $appAnterior = '';
        foreach ($rows as $row) {

            if ($row['CodigoApp'] != $appAnterior) {

                $aplicacion = new Aplicaciones();
                $aplicacion = $aplicacion->find("CodigoApp", $row['CodigoApp']);

                $menu[$row['CodigoApp']] = array(
                    'titulo' => $aplicacion->getNombreApp(),
                    'descripcion' => $aplicacion->getDescripcion(),
                    'funcionalidades' => $row['Funcionalidades'],
                    'publicar' => $row['Publish'],
                );
                unset($aplicacion);
            } else {

                $modulo = new Modulos();
                $modulo = $modulo->find('NombreModulo', $row['NombreModulo']);

                $menu[$row['CodigoApp']]['modulos'][$row['NombreModulo']] = array(
                    'titulo' => $modulo->getTitulo(),
                    'descripcion' => $modulo->getDescripcion(),
                    'funcinalidades' => $row['Funcionalidades'],
                    'controller' => $row['NombreModulo'],
                    'publicar' => $row['Publish'],
                );
                unset($modulo);
            }

            $appAnterior = $row['CodigoApp'];
        }
        return $menu;
    }

    public function getOpciones($de, $nivel) {

        $rows = array();

        $em = new EntityManager($this->getConectionName());
        if ($em->getDbLink()) {
            $query = "
                select m.Id,m.CodigoApp,m.Titulo ,p.NombreModulo, p.Funcionalidades, m.Publish
                from {$this->getDataBaseName()}.ErpPermisos as p, {$this->getDataBaseName()}.ErpModulos as m
                where  m.NombreModulo = p.NombreModulo and m.BelongsTo='{$de}' and m.Nivel='{$nivel}' and
                p.IdPerfil = '{$this->getIDPerfil()->getIDPerfil()}' AND
                LOCATE('AC',p.Funcionalidades)
                order by m.Id ASC ,m.SortOrder ASC";
            $em->query($query);
            $rows = $em->fetchResult();
            $em->desConecta();
        } else
            echo "NO HAY CONEXION CON LA BASE DE DATOS";
        unset($em);

        return $rows;
    }

    public function getArrayMenu() {

        $menu = $this->getOpciones(0, 0);
        foreach ($menu as $keyOpcion => $opcion) {
            $menu[$keyOpcion]['hijos'] = $this->getOpciones($opcion['Id'], 1);
            foreach ($menu[$keyOpcion]['hijos'] as $keySubOpcion => $subOpciones) {
                $menu[$keyOpcion]['hijos'][$keySubOpcion]['hijos'] = $this->getOpciones($subOpciones['Id'], 2);
            }
        }

        // Añadir los favoritos
        $fav = new Favoritos();
        $rows = $fav->cargaCondicion("Controller,Titulo", "IDUsuario='{$_SESSION['usuarioPortal']['Id']}'", "SortOrder");
        unset($fav);
        if (count($rows)) {
            $arrayFavoritos['Titulo'] = "Favoritos";
            foreach ($rows as $row) {
                $arrayFavoritos['hijos'][] = array('Titulo' => $row['Titulo'], 'NombreModulo' => $row['Controller']);
            }
            array_unshift($menu, $arrayFavoritos);
        }

        return $menu;
    }

    public function validaLogico() {

        parent::validaLogico();

        if ($this->IDSucursal == '')
            $this->IDSucursal = NULL;
        if ($this->IDAlmacen == '')
            $this->IDAlmacen = NULL;

        //$this->Password = md5($this->Password);
    }

    /**
     * Devuelve un array con todas las sucursales de la empresa indicada
     * a las que tiene acceso el usuario logeado.
     *
     * Si no se indica empresa, se toma la actual: $_SESSION['emp']
     *
     * Si el usuario puede acceder a todas las sucursales y se activa a TRUE
     * el parámetro $opcionTodas, se añade un valor más en el array
     * con la opcion '** Todas **'
     *
     * @param integer $idEmpresa
     * @param boolean $opcionTodas
     * @return array
     */
    public function getSucursales($idEmpresa = '', $opcionTodas = TRUE) {

        if ($idEmpresa == '')
            $idEmpresa = $_SESSION['emp'];

        if ($this->IDSucursal < 1) { //Puede acceder a todas
            $sucursal = new Sucursales();
            $sucursales = $sucursal->cargaCondicion("IDSucursal as Id, Nombre as Value");
            if ($opcionTodas)
                $sucursales[] = array('Id' => '', 'Value' => '** Todas **');
            unset($sucursal);
        } else { //Puede acceder solo a una
            $sucursal = $this->getIDSucursal();
            $sucursales[] = array(
                'Id' => $sucursal->getIDSucursal(),
                'Value' => $sucursal->getNombre(),
            );
        }
        return $sucursales;
    }

    /**
     * Devuelve un array con todos los almacenes de la empresa a
     * los que tiene acceso el usuario.
     * Si no se indica empresa, se toma la actual: $_SESSION['emp']
     *
     * @param integer $idEmpresa EL id de Empresa
     * @return array
     */
    public function getAlmacenes($idEmpresa = '', $columna = 'Nombre', $defecto = true) {

        //if ($idEmpresa == '')
        //    $idEmpresa = $_SESSION['emp'];

        if ($columna == '')
            $columna = "Nombre";

        if ($this->IDAlmacen < 1) { //Puede acceder a todos
            $almacen = new Almacenes();
            $almacenes = $almacen->fetchAll($idEmpresa, $columna, $defecto);
        } else { //Puede acceder solo a una
            $almacen = new Almacenes($this->IDAlmacen);
            $almacenes[] = array(
                'Id' => $almacen->getIDAlmacen(),
                'Value' => $almacen->getNombre(),
            );
        }
        unset($almacen);

        return $almacenes;
    }

    /**
     * Devuelve un array con los agentes que son COMERCIALES (ROL=1)
     * y están adscritos a la empresa y sucursal indicada.
     * Si el agente en curso es comercial, solo se mostrará el mismo.
     *
     * @param integer $idEmpresa Opcional
     * @param integer $idSucursal Opcional
     * @return array
     */
    public function getComerciales($idEmpresa = '', $idSucursal = '', $defecto = true) {
        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);

        switch ($usuario->getIDRol()->getIDTipo()) {
            case '1': // ROL COMERCIAL
                $comerciales[] = array('Id' => $usuario->getIDAgente(), 'Value' => $usuario->getNombre());
                break;

            default: // RESTO DE ROLES
                //if ($idEmpresa == '')
                //    $idEmpresa = $_SESSION['emp'];
                if ($idSucursal == '')
                    $idSucursal = $_SESSION['suc'];

                $usuariosPcae = new PcaeUsuarios();

                $em = new EntityManager($this->getConectionName());
                $link = $em->getDbLink();
                if (is_resource($link)) {
                    $query = "select a.IDAgente as Id, concat(u.Nombre,u.Apellidos) as Value
                             from 
                                {$this->getDataBaseName()}.{$this->getTableName()} as a,
                                {$usuariosPcae->getDataBaseName()}.{$usuariosPcae->getTableName()} as u
                            where
                            (a.IDAgente <> 1) AND
                            (a.IDAgente = u.Id) AND
                            (a.IDRol='1') AND
                            (a.Activo='1') AND (
                            (a.IDSucursal='{$idSucursal}') OR (a.IDSucursal='0'))";
                    $em->query($query);
                    $comerciales = $em->fetchResult();
                    $em->desConecta();
                    $comerciales[] = array('Id' => '', 'Value' => '** Todos **');
                }
                unset($em);
                break;
        }
        unset($usuario);

        return $comerciales;
    }

    /**
     * Devuelve un array con los agentes que son REPARTIDORES (ROL=2)
     * y están adscritos a la empresa y sucursal indicada.
     * Si el agente en curso es repartidor, solo se mostrará el mismo.
     *
     * @param integer $idEmpresa Opcional
     * @param integer $idSucursal Opcional
     * @return array
     */
    public function getRepartidores($idEmpresa = '', $idSucursal = '') {
        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);

        switch ($usuario->getIDRol()->getIDTipo()) {
            case '2': // ROLL REPARTIDOR
                $repartidores[] = array('Id' => $usuario->getIDAgente(), 'Value' => $usuario->getNombre());
                break;

            default: // RESTO DE ROLES
                //if ($idEmpresa == '')
                //    $idEmpresa = $_SESSION['emp'];
                if ($idSucursal == '')
                    $idSucursal = $_SESSION['suc'];

                $usuariosPcae = new PcaeUsuarios();

                $em = new EntityManager($this->getConectionName());
                $link = $em->getDbLink();
                if (is_resource($link)) {
                    $query = "select a.IDAgente as Id, concat(u.Nombre,u.Apellidos) as Value
                             from 
                                {$this->getDataBaseName()}.{$this->getTableName()} as a,
                                {$usuariosPcae->getDataBaseName()}.{$usuariosPcae->getTableName()} as u
                            where
                            (a.IDAgente <> 1) AND                            
                            (a.IDAgente = u.Id) AND
                            (a.IDRol='2') AND
                            (a.Activo='1') AND (
                            (a.IDSucursal='{$idSucursal}') OR (a.IDSucursal='0'))";
                    $em->query($query);
                    $repartidores = $em->fetchResult();
                    $em->desConecta();
                    $repartidores[] = array('Id' => '', 'Value' => '** Todos **');
                }
                unset($em);
                break;
        }
        unset($usuario);

        return $repartidores;
    }

    /**
     * Devuelve un array con los agentes que son CAMARISTAS (ROL=3)
     * y están adscritos a la empresa y sucursal indicada.
     * Si el agente en curso es camarista, solo se mostrará el mismo.
     *
     * @param integer $idEmpresa Opcional
     * @param integer $idSucursal Opcional
     * @return array
     */
    public function getCamaristas($idEmpresa = '', $idSucursal = '') {
        $usuario = new Agentes($_SESSION['usuarioPortal']['Id']);

        switch ($usuario->getIDRol()->getIDTipo()) {
            case '3': // ROLL CAMARISTA
                $camaristas[] = array('Id' => $usuario->getIDAgente(), 'Value' => $usuario->getNombre());
                break;

            default: // RESTO DE ROLES
                //if ($idEmpresa == '')
                //    $idEmpresa = $_SESSION['emp'];
                if ($idSucursal == '')
                    $idSucursal = $_SESSION['suc'];

                $em = new EntityManager($this->getConectionName());
                $link = $em->getDbLink();
                if (is_resource($link)) {
                    $query = "select IDAgente as Id, Nombre as Value from {$this->getTableName()} where " .
                            "(a.IDAgente <> 1) AND " .
                            "(Rol='3') AND " .
                            "(Activo='1') AND ( " .
                            "(IDSucursal='{$idSucursal}') OR isnull(IDSucursal))";
                    $em->query($query);
                    $camaristas = $em->fetchResult();
                    $em->desConecta();
                }
                unset($em);
                break;
        }
        unset($usuario);

        return $camaristas;
    }

    public function setRepitePassword($repitePassword) {
        $this->_repitePassword = $repitePassword;
    }

    public function getRepitePassword() {
        return $this->_repitePassword;
    }

    public function getEsComercial() {
        return ($this->getIDRol()->getIDTipo() == '1');
    }

    public function getEsRepartidor() {
        return ($this->getIDRol()->getIDTipo() == '2');
    }

    public function getEsAlmacenero() {
        return ($this->getIDRol()->getIDTipo() == '3');
    }

}

?>