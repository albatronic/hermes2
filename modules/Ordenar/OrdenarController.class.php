<?php

/**
 * CONTROLLER FOR GconContenidos
 * @author: Sergio Perez <sergio.perez@albatronic.com>
 * @copyright: INFORMATICA ALBATRONIC SL 
 * @date 27.12.2012 12:35:39

 * Extiende a la clase controller
 */
class OrdenarController extends Controller {

    protected $entity = "Ordenar";

    public function __construct($request) {
        // Cargar lo que viene en el request
        $this->request = $request;

        // Cargar la configuracion del modulo (modules/moduloName/config.yml)
        $this->form = new Form($this->entity);

        $includesHead = $this->form->getIncludesHead();

        $this->values['twigCss'] = $includesHead['twigCss'];
        $this->values['twigJs'] = $includesHead['twigJs'];
    }

    public function IndexAction() {

        switch ($this->request['METHOD']) {
            case 'GET':
                $entidad = $this->request['2'];
                $filtro = "({$this->request['3']} = '{$this->request['4']}') and (Publish='1')";
                $columnaMostrar = ($this->request['5'] == '') ? "Titulo" : $this->request['5'];
                $criterioOrden = "orden0";
                break;
            case 'POST':
                $entidad = $this->request['entidad'];
                $filtro = $this->request['filtro'];
                $columnaMostrar = $this->request['columnaMostrar'];
                $criterioOrden = $this->request['criterioOrden'];
                break;
        }

        // Obtener las posibles columnas por las que ordenar.
        // Están en la variable de entorno del módulo
        $variables = new CpanVariables('Mod', 'Env', $entidad);
        $this->values['criteriosOrden'] = $variables->getNode('ordenesWeb');
        $columnas = $variables->getNode('columns');
        unset($variables);
        // Pongo el caption del campo
        foreach ($this->values['criteriosOrden'] as $key => $criterio) {
            $this->values['criteriosOrden'][$key]['caption'] = $columnas[$key]['caption'];
        }

        $this->values['criteriosOrden']['orden0'] = array(
            'caption' => 'Orden',
            'columnaMostrar' => $columnaMostrar,
            'filtro' => $filtro,
            'columnaOrden' => 'SortOrder',
        );

        $columnaMostrarOrdenacion = $this->values['criteriosOrden'][$criterioOrden]['columnaMostrar'];
        $filtrar = $this->values['criteriosOrden'][$criterioOrden]['filtro'];
        $columnaOrden = $this->values['criteriosOrden'][$criterioOrden]['columnaOrden'];

        $objeto = new $entidad();
        $valores = $objeto->cargaCondicion("{$objeto->getPrimaryKeyName()} as Id,{$columnaMostrarOrdenacion} as Value", $filtrar, "{$columnaOrden} ASC");
        unset($objeto);

        $this->values['entidad'] = $entidad;
        $this->values['criterioOrden'] = $criterioOrden;
        $this->values['filtro'] = $filtro;
        $this->values['columnaMostrar'] = $columnaMostrar;
        $this->values['datos'] = $valores;

        return array(
            'template' => 'Ordenar/index.html.twig',
            'values' => $this->values,
        );
    }

    /**
     * Reordenada los elmentos
     * @return type
     */
    public function ReordenarAction() {

        $entidad = $this->request['entidad'];
        $filtro = $this->request['filtro'];
        $criterioOrden = $this->request['criterioOrden'];

        if ($criterioOrden == "orden0") {
            $filtro = $this->request['filtro'];
            $columnaOrden = "SortOrder";
        } else {
            // Obtener las posibles columnas por las que ordenar.
            $variables = new CpanVariables('Mod', 'Env', $entidad);
            $ordenesWeb = $variables->getNode('ordenesWeb');
            unset($variables);

            $filtro = $ordenesWeb[$criterioOrden]['filtro'];
            $columnaOrden = $ordenesWeb[$criterioOrden]['columnaOrden'];
        }

        $objeto = new $entidad();
        $dbName = $objeto->getDataBaseName();
        $tableName = $objeto->getTableName();
        $primaryKeyName = $objeto->getPrimaryKeyName();

        $em = new EntityManager($objeto->getConectionName());
        if ($em->getDbLink()) {
            // Recorro los elementos que vienen en el acordeon, y los reordeno
            $orden = 0;
            foreach ($this->request['acordeon'] as $elemento) {
                $query = "UPDATE {$dbName}.{$tableName} SET {$columnaOrden} = '{$orden}' WHERE ({$filtro}) AND ({$primaryKeyName} = '{$elemento}')";
                $em->query($query);
                $orden += 1;
            }
            $em->desConecta();
        }
        unset($em);

        // Si estoy en el idioma principal, también cambio el orden en el resto de idiomas
        if ($_SESSION['idiomas']['actual'] == 0) {

            // Recorro los idiomas adicionales
            foreach ($_SESSION['idiomas']['disponibles'] as $key => $value) {
                if ($key > 0) {
                    $_SESSION['idiomas']['actual'] = $key;
                    
                    $objeto = new $entidad();
                    $dbName = $objeto->getDataBaseName();
                    $tableName = $objeto->getTableName();
                    $primaryKeyName = $objeto->getPrimaryKeyName();

                    $em = new EntityManager($objeto->getConectionName());
                    if ($em->getDbLink()) {
                        // Recorro los elementos que vienen en el acordeon, y los reordeno
                        $orden = 0;
                        foreach ($this->request['acordeon'] as $elemento) {
                            $query = "UPDATE {$dbName}.{$tableName} SET {$columnaOrden} = '{$orden}' WHERE ({$filtro}) AND ({$primaryKeyName} = '{$elemento}')";
                            $em->query($query);
                            $orden += 1;
                        }
                        $em->desConecta();
                    }
                    unset($em);
                }
            }
            $_SESSION['idiomas']['actual'] = 0;
        }

        return $this->IndexAction();
    }

}

?>
