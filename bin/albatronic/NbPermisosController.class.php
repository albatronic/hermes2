<?php

/**
 * Description of NbPermisosController
 *
 * Este es un controlador comun a todas las apps.
 * Se utiliza para gestionar los permisos de acceso a las
 * opciones y subopciones de menu de cada perfil de usuario
 *
 * Cada app debe tener un modulo 'Permisos' y un controlador 'PermisosController' que
 * extienda a este.
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 20-ene-2012
 *
 */
class NbPermisosController extends Controller {

    protected $entity = "Permisos";
    protected $parentEntity = "";

    /**
     * Devuelve todas los permisos del perfil de usuario
     * indicado en la posicion 2 del request.
     * @return array
     */
    public function listopcionesAction() {
        $this->listado->filter['columnSelected'] = $this->form->getLinkBy();

        $funcionalidad = new Funcionalidades();
        $arrayFuncioneslidades = $funcionalidad->getArrayFuncionalidades();

        switch ($this->request['METHOD']) {
            case 'GET':
                $this->listado->filter['value'] = $this->request[2];
                $this->values['linkBy']['value'] = $this->request[2];
                break;

            case 'POST':
                $this->listado->filter['value'] = $this->request['IDPerfil'];
                $this->values['linkBy']['value'] = $this->request['IDPerfil'];
                $this->values['IDOpcion'] = $this->request['IDOpcion'];

                $menu = new Menu($this->values['linkBy']['value']);

                $subopciones = $menu->getSubopciones($this->request['IDOpcion'], $this->values['linkBy']['value']);
                unset($menu);

                //El campo Permisos lo transformo en un array con los
                //permisos independientes para poder tratar cada uno por separado
                foreach ($subopciones as $key => $value) {
                    $permisos = explode(",", $subopciones[$key]['Funcionalidades']);
                    foreach ($arrayFuncioneslidades as $permiso)
                        $subopciones[$key]['Permisos'][$permiso['Id']] = (strpos($subopciones[$key]['Funcionalidades'], $permiso['Id']) !== false);
                }
                break;
        }

        $template = $this->entity . '/list.html.twig';

        $menu = new Menu($this->values['linkBy']['value']);

        $this->values['listado']['opciones'] = $menu->getOpciones($this->values['linkBy']['value']);
        $this->values['listado']['subopciones'] = $subopciones;
        $this->values['opciones'] = $menu->getOpciones();
        $this->values['subopciones'] = $menu->getSubopciones($this->request['IDOpcion']);
        $this->values['funcionalidades'] = $arrayFuncioneslidades;

        unset($menu);
        return array('template' => $template, 'values' => $this->values);
    }

    /**
     * Añade una opcion de menu al perfil indicado
     * Recibe por post: IDPerfil y IDOpcion
     */
    public function anadiropcionAction() {

        $permiso = new Permisos();
        $permiso->setIdPerfil($this->request['IDPerfil']);
        $permiso->setNombreModulo($this->request['IDOpcion']);
        $permiso->setFuncionalidades("AC,VW");
        $permiso->create();

        $this->request[2] = $this->request['IDPerfil'];
        return $this->listopcionesAction();
    }

    /**
     * Borra una opcion de menu al perfil indicado
     * Recibe por post: IDPerfil y IDOpcion
     */
    public function borraropcionAction() {

        $modulo = new Modulos();
        $rows = $modulo->cargaCondicion("NombreModulo", "CodigoApp='{$this->request['IDOpcion']}'");
        unset($modulo);

        $permiso = new Permisos();
        foreach ($rows as $row)
            $permiso->queryDelete("IdPerfil='{$this->request['IDPerfil']}' and NombreModulo='{$row['NombreModulo']}'");
        unset($permiso);

        $this->request[2] = $this->request['IDPerfil'];
        return $this->listopcionesAction();
    }

    /**
     * Añade una subopcion de menu.
     * Recibe por post: IDPerfil, IDOpcion y IDSubopcion
     */
    public function anadirsubopcionAction() {

        $permiso = new Permisos();
        $permiso->setIdPerfil($this->request['IDPerfil']);
        $permiso->setNombreModulo($this->request['IDSubopcion']);
        $permiso->setFuncionalidades("");
        $permiso->create();

        return $this->listopcionesAction();
    }

    /**
     * Borra una subopcion de menu al perfil indicado
     * Recibe por post: IDPerfil, IDOpcion y IDSubopcion
     */
    public function borrarsubopcionAction() {

        $permiso = new Permisos();
        $permiso->queryDelete("IdPerfil='{$this->request['IDPerfil']}' and NombreModulo='{$this->request['IDSubopcion']}'");
        unset($permiso);

        $this->request[2] = $this->request['IDPerfil'];
        return $this->listopcionesAction();
    }

    /**
     * Pone todas las funcionalidades a un perfil y modulo
     * @return type
     */
    public function PonerTodoAction() {

        $funcionalidad = new Funcionalidades();
        $funcionalidades = $funcionalidad->getStringFuncionalidades();
        unset($funcionalidad);

        $idPerfil = $this->request['IDPerfil'];
        $idSubopcion = $this->request['IDSubopcion'];

        $permiso = new Permisos();
        $permiso->queryUpdate(array('Funcionalidades' => $funcionalidades), "IDPerfil='{$idPerfil}' and NombreModulo='{$idSubopcion}'");

        $this->request[2] = $this->request['IDPerfil'];
        return $this->listopcionesAction();
    }

    /**
     * Quita todas las funcionalidades de un perfil y modulo
     * @return type
     */
    public function QuitarTodoAction() {

        $idPerfil = $this->request['IDPerfil'];
        $idSubopcion = $this->request['IDSubopcion'];

        $permiso = new Permisos();
        $permiso->queryUpdate(array('Funcionalidades' => ''), "IDPerfil='{$idPerfil}' and NombreModulo='{$idSubopcion}'");

        $this->request[2] = $this->request['IDPerfil'];
        return $this->listopcionesAction();
    }

}

?>
