<?php

/**
 * Lee los archivos de configuracion yaml de cada modulo.
 *
 * Dispone de los métodos que devuelve los nodos del archivo.
 *
 * @author Sergio Perez
 * @copyright Informatica ALBATRONIC, SL
 * @since 24.05.2011
 */
class Form {

    protected $moduleName;
    protected $yaml;

    /**
     *
     * @param string $moduleName Nombre del modulo
     * @param string $archivoYaml Nombre del archivo que tiene los parametros
     */
    public function __construct($moduleName, $archivoYaml = 'config.yml') {
        $this->moduleName = $moduleName;
        $file = $_SERVER['DOCUMENT_ROOT'] . $_SESSION['appPath'] . "/modules/" . $this->moduleName . "/" . $archivoYaml;
        $this->yaml = sfYaml::load($file);
    }

    /**
     * Devuelve el contenido del nodo $node que debe de estar
     * dentro del nodo principal ($moduleName pasado al constructor).
     *
     * @param string $node El nombre del nodo solicitado
     * @return array Array con el nodo indicado
     */
    public function getNode($node) {
        return $this->yaml[$this->moduleName][$node];
    }

    /**
     * Devuelve un array con los includes para el HEAD del layout
     * Nodo YAML <includesHead>
     * @return array
     */
    public function getIncludesHead() {
        return $this->getNode('includesHead');
    }

    /**
     * Devuelve un array con las variables WEB
     * Nodo YAML <includesVarWeb>
     * @return array
     */
    public function getVarweb() {

        $vW = array();

        $includes = $this->getNode('includesVarWeb');
        if (is_array($includes)) {

            foreach ($includes as $include) {
                $yml = sfYaml::load($include);
                $nodo = $yml['varWeb'];
                foreach ($nodo as $key => $value) {
                    $vW[$key] = $value;
                }
            }
        }

        return $vW;
    }

    /**
     * Devuelve el nombre de la entidad afectada por el formulario
     * que no tiene que coincidir con el nombre de la tabla
     * Nodo YAML <entity>
     * @return string
     */
    public function getEntity() {
        return $this->getNode('entity');
    }

    /**
     * Devuelve el nombre de la conexión a la BD.
     * Nodo YAML <conection>
     * @return string
     */
    public function getConection() {

        $conection = $this->getNode('conection');
        if ($conection == '') {
            $conection = $_SESSION['project']['conection'];
        }
        return $conection;
    }

    /**
     * Devuelve el nombre físico de la BD donde está la entidad en curso
     * @return string Nombre de la BD
     */
    public function getDataBaseName() {
        $em = new EntityManager($this->getConection());
        $dataBaseName = $em->getDataBase();
        unset($em);
        return $dataBaseName;
    }

    /**
     * Devuelve el nombre físico de la tabla teniendo en cuenta el idioma actual
     * Nodo YAML <table>
     * @return string
     */
    public function getTable() {

        $sufijo = "";

        if (($_SESSION['VARIABLES']['EnvMod']['translatable'] == 1) and ($_SESSION['idiomas']['actual'] > 0))
            $sufijo = $_SESSION['idiomas']['actual'];

        return $this->getNode('table') . $sufijo;
    }

    /**
     * Devuelve el nombre de la clave primaria de la tabla
     * Nodo YAML <primarykey>
     * @return string
     */
    public function getPrimaryKey() {
        return $this->getNode('primarykey');
    }

    /**
     * Devuelve el nombre de la columna por la que la entidad
     * está relacionada con la entidad padre.
     * Se utiliza para las relaciones típicas PADRE->HIJOS
     * Nodo YAML <linkBy>
     * @return string
     */
    public function getLinkBy() {
        return $this->getNode('linkBy');
    }

    /**
     * Devuelve el título del formulario de mantenimiento
     * Nodo YAML <title>
     * @return string
     */
    public function getTitle() {
        return $this->getNode('title');
    }

    /**
     * Devuelve el id del video para la ayuda.
     * Esto es para el caso en que los videos de la ayuda estén un servidor
     * y para acceder a ellos haya que hacerlo vía un id.
     * Nodo YAML <id_video>
     * @return string
     */
    public function getIdVideo() {
        return $this->getNode('id_video');
    }

    /**
     * Devuelve la url del video de ayuda.
     * Nodo YAML <url_video>
     * @return string
     */
    public function getUrlVideo() {
        return $this->getNode('url_video');
    }

    /**
     * Devuelve true o false según el acceso al formulario
     * requiera estar autenticado previamente o no
     * Nodo YAML <login_required>
     * @return boolean
     */
    public function getLoginRequired() {
        $valor = strtoupper($this->getNode('login_required'));

        return ( ($valor == 'YES') or ($valor == 'TRUE') );
    }

    /**
     * Devuelve true o false según el acceso al formulario
     * esté sujeto a control de permisos
     * Nodo YAML <permission_control>
     * @return boolean
     */
    public function getPermissionControl() {
        $valor = strtoupper($this->getNode('permission_control'));

        return ( ($valor == 'YES') or ($valor == 'TRUE') );
    }

    /**
     * Devuelve true o false según el acceso al formulario
     * esté sujeto a control de favoritos
     * Nodo YAML <favourite_control>
     * @return boolean
     */
    public function getFavouriteControl() {
        $valor = strtoupper($this->getNode('favourite_control'));

        return ( ($valor == 'YES') or ($valor == 'TRUE') );
    }

    /**
     * Devuelve el nombre de la columna por la que se realizará
     * la búsqueda por defecto.
     * Nodo YAML: <search_default>
     * @return string
     */
    public function getListSearchDefault() {
        return $this->getNode('search_default');
    }

    /**
     * Devuelve el array con los criterios de ordenación del listado
     * Nodo YAML: <order_by>
     * @return array
     */
    public function getListOrderBy() {
        return $this->getNode('order_by');
    }

    /**
     * Devuelve el número de registros por página del listado en pantalla
     * Nodo YAML <records_per_page>
     * @return integer
     */
    public function getListRecordsPerPage() {
        return (integer) $this->getNode('records_per_page');
    }

    /**
     * Devuelve el nombre de fichero de ayuda. (solo el nombre, no la ruta)
     * Nodo YAML <help_file>
     * @return string
     */
    public function getHelpFile() {
        return trim($this->getNode('help_file'));
    }

    /**
     * Devuelve los nombres de las columnas a mostrar en el listado
     * separados por comas
     * Nodo YAML <columns><column>
     * @return string
     */
    public function getListColumns() {

        $columnas = '';

        if ($this->getNode('columns')) {
            foreach ($this->getNode('columns') as $key => $value) {
                if ((strtoupper($value['list']) == 'YES') or ($value['list'] === true)) {
                    if ($columnas != '')
                        $columnas .= ", ";
                    $columnas .= (string) $key;
                }
            }
        }

        return $columnas;
    }

    /**
     * Devuelve un array con los titulos,tipos y longitudes de las columnas
     * y los parametros para el eventual link.
     * Nodo YAML <columns><column>
     * @return array Con los títulos de las columnas para el listado
     */
    public function getListTitleColumns() {
        $titulos = array();

        if ($this->getNode('columns')) {
            foreach ($this->getNode('columns') as $key => $value) {
                if ((strtoupper($value['list']) == 'YES') or ($value['list'] === true)) {
                    $titulos[$key] = array(
                        'title' => $value['title'],
                        'type' => $value['validator']['type'],
                        'length' => $value['validator']['length'],
                    );
                    if ($value['link']['route']) {
                        $titulos[$key]['link'] = $value['link'];
                    }
                }
            }
        }
        return $titulos;
    }

    /**
     * Devuelve un array con las columnas del FILTRO del listado:
     * el índice es el nombre de la columna y el valor es el título
     * Nodo YAML <columns><column>
     * @return array
     */
    public function getListArrayColumns() {
        $columns = array();

        if ($this->getNode('columns')) {
            foreach ($this->getAtributos($this->moduleName) as $key => $value)
                if ((strtoupper($value['filter']) == 'YES') or ($value['filter'] === 1))
                    $columns[$key] = $value['caption'];
        }

        return $columns;
    }

    /**
     * Recibe el nombre del campo y devuelve su titulo
     *
     * @param string $fieldName El nombre del campo (nodo field)
     * @return string El titulo de la columna
     */
    public function getTitleColumn($fieldName) {

        $columns = $this->getNode('columns');
        return $columns[$fieldName]['title'];
    }

    /**
     * Devuelve un array con los titulos de todas las columnas
     *
     * El índice del array es el field de la columna
     *
     * @return array Array con los titulo
     */
    public function getTitles() {

        $titles = array();

        if ($this->getNode('columns'))
            foreach ($this->getNode('columns') as $key => $value)
                $titles[$key] = $value['title'];

        return $titles;
    }

    /**
     * Devuelve el valor por defecto de la columna
     * Nodo <default>
     *
     * @param string $column El nombre de la columna
     * @return string El valor por defecto
     */
    public function getDefaultValue($column) {

        $columnas = $this->getNode('columns');
        return $columnas[$column]['default'];
    }

    /**
     * Devuelve un array con los reglas de validación
     * El array tiene un elemento por cada campo a validar.
     * El indice del array es el nombre del campo.
     * El array SOLO incluye las columnas NO que pueden ser NULAS
     * También incluye el valor por defecto.
     *
     * Nodo YAML <columns><column><validator>
     * @return array
     */
    public function getRules() {

        $rules = array();

        $columns = $this->getNode('columns');

        if (is_array($columns)) {
            foreach ($columns as $key => $value) {
                if (strtoupper($value['validator']['nullable']) == 'NO') {
                    $rules[$key] = array(
                        'title' => $value['title'],
                        'type' => $value['validator']['type'],
                        'length' => $value['validator']['length'],
                        'minimo' => $value['validator']['min'],
                        'maximo' => $value['validator']['max'],
                        'default' => $value['default'],
                        'message' => $value['validator']['message'],
                    );
                }
            }
        }

        return $rules;
    }

    /**
     * Si el formulario debe tener listado, se devuelve TRUE
     * dependiendo del valor del nodo <feature_list>
     *
     * @return boolean TRUE si el formulario tiene listado
     */
    public function getTieneListado() {
        $valor = strtoupper($this->getNode('feature_list'));

        return ( ($valor == 'YES') or ($valor == 'TRUE') );
    }

    /**
     * Devuelve un array con los filtros adicionales que se construiran
     * en el FiltroGenerico.html.twig
     *
     * El indice del array indica el orden en el que se mostraran los filtros
     *
     * El array es de la forma:
     * array(
     *      field   => Nombre de la columna de la tabla por la que filtrar
     *      caption => Texto a mostrar delante del input o select
     *      entity  => Si está vacio, se mostrara un input.
     *                 Si tiene valor, debe ser el nombre de una entidad y el
     *                 nombre de la columna a mostrar separados por coma.
     *                 Se construirá un select llamando al método fetchAll de la
     *                 entidad indicada.
     * )
     *
     * @return array $filters Los filtros adicionales
     */
    public function getFilters() {
        $filters = array();
        $desplazamiento = 0;

        if ($this->getNode('columns')) {
            foreach ($this->getNode('columns') as $key => $value) {
                if (isset($value['aditional_filter'])) {
                    $index = (integer) trim((string) $value['aditional_filter']['order']) + $desplazamiento;
                    $type = strtolower(trim((string) $value['aditional_filter']['type']));
                    if (!$type)
                        $type = "input";
                    if (($type != 'input') and ($type != 'select') and ($type != 'check') and ($type != 'range'))
                        $type = 'input';
                    $event = (isset($value['aditional_filter']['event'])) ? trim((string) $value['aditional_filter']['event']) : '';
                    $default = (isset($value['aditional_filter']['default']))? trim((string) $value['aditional_filter']['default']) : '';
                    $operator = (isset($value['aditional_filter']['operator'])) ? trim((string) $value['aditional_filter']['operator']) : '';
                    if ($operator == '')
                        $operator = '=';

                    $filters[$index]['field'] = trim((string) $key);
                    $filters[$index]['caption'] = trim((string) $value['aditional_filter']['caption']);
                    $filters[$index]['entity'] = trim((string) $value['aditional_filter']['entity']);
                    $filters[$index]['method'] = trim((string) $value['aditional_filter']['method']);
                    $filters[$index]['params'] = trim((string) $value['aditional_filter']['params']);
                    $filters[$index]['type'] = $type;
                    $filters[$index]['data_type'] = trim((string) $value['validator']['type']);
                    $filters[$index]['event'] = $event;
                    $filters[$index]['default'] = $default;
                    $filters[$index]['operator'] = $operator;

                    if ($type == 'range') {
                        $desplazamiento++;
                        $filters[$index + 1] = $filters[$index];
                        $filters[$index]['operator'] = ">=";
                        $filters[$index + 1]['operator'] = "<=";
                        if ($filters[$index]['default'] == '') {
                            if ($filters[$index]['data_type'] == 'date') {
                                $filters[$index]['default'] = "00/00/0000";
                                $filters[$index + 1]['default'] = "99/99/9999";
                            }
                        } else {
                            // Si es de tipo 'range' y se ha indicado un valor por defecto,
                            // se entiende que vienen los dos valores del rango separados por coma.
                            $valoresDefecto = explode(",", $filters[$index]['default']);
                            $filters[$index]['default'] = $valoresDefecto[0];
                            $filters[$index + 1]['default'] = $valoresDefecto[1];
                        }
                    }
                }
            }
        }

        // Reordenación de los filtros y renumeración a partir de 1
        ksort($filters);
        $nuevo = array();
        $i = 1;
        foreach ($filters as $value) {
            $nuevo[$i] = $value;
            $i++;
        }

        return $nuevo;
    }

    /**
     * Devuelve un array con los tipos de listados que están configurados
     * para el modulo y que están definidos en NOMBREMODULO/listados.xml
     * y que pueden ser accedidos por el usuario en curso en base a su perfil
     *
     * En el nodo <idPerfil> se indican los IDs (separados por comas) de los perfiles que tendrán acceso
     * al listado. Si el nodo está vacio, se entiende que pueden acceder todos.
     *
     * En el array devuelto solo se indica el Id y el title del listado.
     * Para obtener los parametros concretos de cada listado se debe utilizar
     * el método getFormatoListado($idListado)
     *
     * @return array $formatos Array con los tipos/formatos de listados disponibles
     */
    public function getFormatosListados() {
        $formatos = array();

        if ($this->yaml['listados']) {
            $perfilUsuario = $_SESSION['usuarioPortal']['IdPerfil'];
            foreach ($this->yaml['listados'] as $value) {
                $perfiles = (string) $value['idPerfil'];
                $arrayPerfiles = explode(',', $perfiles);
                if (($perfiles == '') or (in_array($perfilUsuario, $arrayPerfiles)))
                    $formatos[] = (string) $value['title'];
            }
        }

        return $formatos;
    }

    /**
     * Devuelve todos los parámetros de configuración de un listado.
     * El ID del listado corresponde al orden en que el listado está
     * definido dentro de listados.yaml. El primer listado definido
     * tendrá el ID 0.
     *
     * @param integer $idListado El ID del tipo de listado
     * @return array $parametros Parametros de configuracion del listado
     */
    public function getFormatoListado($idListado) {
        return $this->yaml['listados'][$idListado];
    }

    /**
     * Devuelve un array con los atributos de cada columna del módulo $modulo
     *
     * Los atributos son: caption, visible, updatable, default, permission, help
     *
     * Los valores de los atributos se obtienen  de las variables de entorno del módulo,
     * y si no existen, se cargan del config.yml correspondiente
     *
     * @param string $modulo El nombre del módulo
     * @return array Array de atributos
     */
    public function getAtributos($modulo) {

        $atributos = array();

        // PRIMERO LEO LOS ATRIBUTOS DE LAS COLUMNAS QUE ESTÁN EN CONFIG.YML
        $columnasConfig = $this->getNode('columns');

        // LUEGO LOS SUSTITUYO POR LOS ESPECIFICOS QUE ESTAN EN LAS VAR DE ENTORNO DE PROYECTO
        // DE TAL MANERA QUE PREVALECEN LOS DEFINIDOS EN LAS VARIABLES DE ENTORNO, PERO
        // SI NO EXISTIERA LA VARIABLE DE ENTORNO CORRESPONDIENTE ENTONCES PONGO LA DEL CONFIG.
        $variables = new CpanVariables('Mod', 'Env', $modulo);

        if (is_array($variables->getDatosYml())) {
            $arrayColumnas = $variables->getNode('columns');
            foreach ($arrayColumnas as $key => $value) {
                $atributos[$key] = $value;

                if (is_array($columnasConfig[$key])) {
                    foreach ($columnasConfig[$key] as $keyConfig => $valueConfig) {
                        if (!isset($atributos[$key][$keyConfig]))
                            $atributos[$key][$keyConfig] = $valueConfig;
                    }
                }
            }
        } else {
            // Aún no se han definido las variables, por lo tanto cargo los atributos
            // en base al array de correspondencia de atributos predeterminados
            foreach ($columnasConfig as $keyColumna => $valueColumna)
                foreach (VariablesEnv::$varEnvMod as $keyVar => $keyColumnaConfig)
                    $atributos[$keyColumna][$keyVar] = (isset($valueColumna[$keyColumnaConfig])) ? $valueColumna[$keyColumnaConfig] : "";
        }

        unset($variables);

        // Si el usuario es super pongo la visibilidad a TRUE
        //if ($_SESSION['usuarioPortal']['Id'] == '1')
        //    foreach ($atributos as $key => $value)
        //        ++$atributos[$key]['visible'];
        /**
          if (!$atributos[$key]['visible']) {
          $atributos[$key]['visible'] = '1';
          $atributos[$key]['caption'] .= " (oculta)";
          }
         */
        return $atributos;
    }

}

?>
