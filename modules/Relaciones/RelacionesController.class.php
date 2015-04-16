<?php

/**
 * Description of RelacionesController
 * 
 * Controlador para gestionar las relaciones N a M
 * entre entidades e id de entidades
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @date 1-feb-2013
 * @copyright (c) Informática Albatronic, sl
 */
class RelacionesController extends Controller {

    protected $entidadOrigen;
    protected $idOrigen;
    protected $entidadDestino;

    protected $entity = "Relaciones";

    public function __construct($request) {

        // Cargar lo que viene en el request, incluidos los eventuales
        // ficheros a subir
        $this->request = $request;

        // Cargar la configuracion del modulo (modules/moduloName/config.yml)
        $this->form = new Form($this->entity);

        // Cargar los includes del Head html
        $includesHead = $this->form->getIncludesHead();
        $this->values['twigCss'] = $includesHead['twigCss'];
        $this->values['twigJs'] = $includesHead['twigJs'];

    }

    public function IndexAction() {

        switch ($this->request['METHOD']) {
            case 'GET':
                $this->entidadOrigen = $this->request[1];
                $this->idOrigen = $this->request[2];
                break;
            case 'POST':
                $this->entidadOrigen = $this->request['entidadOrigen'];
                $this->idOrigen = $this->request['idOrigen'];
                $this->entidadDestino = $this->request['entidadDestino'];
                // Obtener el arbol con los registros de la entidad destino.
                $entidad = new $this->entidadDestino();
                $arbol = method_exists($entidad, "getArbolHijos") ? $entidad->getArbolHijos('',$this->request['entidadOrigen'],$this->request['idOrigen']) : array();
                break;
        }

        // Cargas las variables del modulo origen
        $this->cargaVariables($this->entidadOrigen);

        // Hacer el array de las entidades que son relacionables
        // con la entidad origen. Estas están definidas, separadas por coma, en la variable
        // de entorno 'modulosRelacionables' del módulo origen
        $modulosRelacionables = array();
        $entidades = explode(",", trim($this->varEnvMod['modulosRelacionables']));
        if (is_array($entidades)) {
            $modulos = new Modulos();
            foreach ($entidades as $entidad) {
                $entidad = trim($entidad);
                $rows = $modulos->cargaCondicion("Titulo", "NombreModulo='$entidad'");
                if ($rows[0]['Titulo'])
                    $modulosRelacionables[$entidad] = $rows[0]['Titulo'];
            }
            unset($modulos);
        }

        $this->values['modulosRelacionables'] = $modulosRelacionables;
        $this->values['entidadOrigen'] = $this->entidadOrigen;
        $this->values['idOrigen'] = $this->idOrigen;
        $this->values['entidadDestino'] = $this->entidadDestino;
        $this->values['arbol'] = $arbol;

        return array(
            'template' => 'Relaciones/index.html.twig',
            'values' => $this->values,
        );
    }

    protected function cargaVariables($modulo) {
        // Variables de entorno del modulo
        $variables = new CpanVariables('Mod', 'Env', $modulo);
        $this->varEnvMod = $variables->getValores();
        $this->values['varEnvMod'] = $this->varEnvMod;
        if (count($this->values['varEnvMod']) == 0)
            $this->values['errores'][] = "No se han definido las variables de entorno del módulo '{$modulo}'";
    }

}

?>
