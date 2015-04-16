<?php

/**
 * GESTION DE VARIABLES WEB Y DE ENTORNO
 *
 * CONSTRUYE EL OBJETO VARIABLES EN BASE AL ambito, tipo y nombre
 *
 * SUS MÉTODOS PERMITEN EDITARLAS, BORRARLAS Y GUARDARLAS EN LOS
 * ARCHIVOS YML DEL PROYECTO
 *
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 09-sep-2012 12:48:45
 */
class CpanVariables extends CpanVariablesEntity {

    /**
     * Fuerzo la privacidad a 2 (Ambos) para
     * que estén disponibles para todos los usuarios
     * @var type 
     */
    protected $Privacy = '2';
    
    protected $_tiposDeVariables = array('Web', 'Env');
    protected $_ambitosDeVariables = array('Pro', 'App', 'Mod');
    protected $_objeto = array();
    protected $_fileEspecificas;
    protected $_titulo;
    protected $_template;

    /**
     * Construye el objeto variables correspondiente al ámbito, tipo y nombre
     *
     * @param string $ambito El ambito de las variables: Pro, App ó Mod
     * @param string $tipo El tipo de variable: Env, Web
     * @param string $nombre El nombre de la app o del modulo según $ambito
     */
    public function __construct($ambito = '', $tipo = '', $nombre = '') {

        if ($this->carga($ambito, $tipo, $nombre)) {

            $this->_objeto = array(
                'ambito' => $ambito,
                'tipo' => $tipo,
                'nombre' => $nombre,
                'template' => $this->_template,
                'titulo' => $this->_titulo,
                'datos' => $this->getDatosYml(),
            );
        }
    }

    public function __toString() {
        return $this->getId();
    }

    /**
     * Devuelve el array objeto que contiene todos los valores:
     *
     *      ambito =>
     *      tipo =>
     *      nombre =>
     *      pathYml =>  la ruta completa al archivo yml de variables
     *      template => el nombre del template a utilizar para mostrar las variables
     *      titulo => string con el titulo a mostrar en el template
     *      datos   => array con las variables y sus valores
     *
     * @return array Array con el objeto completo
     */
    public function getObjeto() {
        return $this->_objeto;
    }

    /**
     * Devuelve un array con los valores de las variables que
     * están en el proyecto en curso
     *
     * @return array Array con los valores de las variables
     */
    public function getValores() {
        return $this->_objeto['datos'];
    }

    /**
     * Devuelve el nombre del template que se debe usar
     * para renderizar las variables en curso
     *
     * @return string El template
     */
    public function getTemplate() {
        return $this->_objeto['template'];
    }

    /**
     * Devuelve el titulo de las variables en curso
     *
     * @return string El titulo
     */
    public function getTitulo() {
        return $this->_objeto['titulo'];
    }

    /**
     * Devuelve un array con la estructura Yml de las variables
     *
     * @return array Array
     */
    public function getDatosYml() {
        return sfYaml::load($this->getYml());
    }

    /**
     * Pone en el objeto el array de variables
     *
     * @param array $variables
     */
    public function setDatosYml(array $variables) {
        $this->_objeto['datos'] = $variables;
    }

    /**
     * Devuelve un array con la definicion de las variables especificas
     * del objeto en curso
     *
     * @return array Array de variables
     */
    public function getArrayEspecificas() {

        if (file_exists($this->_fileEspecificas))
            $arrayEspecificas = sfYaml::load($this->_fileEspecificas);

        if (!is_array($arrayEspecificas))
            $arrayEspecificas = array();

        return $arrayEspecificas;
    }

    /**
     * Actualiza un nodo del objeto.
     *
     * NO GUARDA, la actualización se produce el array que está en memoria.
     *
     * @param string $nombreNodo El nombre del nodo
     * @param array $nodo Array con los valores del nodo
     */
    public function setNode($nombreNodo, array $nodo) {
        $this->_objeto['datos'][$nombreNodo] = $nodo;
    }

    /**
     * Devuelve el contenido de un nodo
     *
     * @param type $nombreNodo
     * @return mixed Texto plano o array
     */
    public function getNode($nombreNodo) {
        return $this->_objeto['datos'][$nombreNodo];
    }

    /**
     * Devuelve un array con los valores de una columna
     *
     * @param string $nombreColumna El nombre de columna
     * @return array Array con los valores de una columna
     */
    public function getColumn($nombreColumna) {
        return $this->_objeto['datos']['columns'][$nombreColumna];
    }

    /**
     * Actualiza el array de valores de una columna
     * NO GUARDA, la actualización se produce el array que está en memoria
     *
     * @param string $nombreColumna El nombre de la columna
     * @param array $valores Array de valores
     */
    public function setColumn($nombreColumna, array $valores) {
        $this->_objeto['datos']['columns'][$nombreColumna] = $valores;
    }

    /**
     * Almacena las variables en base a
     * lo contenido en $this->_objeto
     *
     * Si son variables Web de Módulo, también pone la visibilidad
     * en las variables de entorno de dicho módulo
     *
     * @return boolean TRUE si se guardó correctamente
     */
    public function save() {

        if (is_array($this->_objeto['datos']))
            $this->setYml(sfYaml::dump($this->_objeto['datos'], 3));

        $this->Publish = '1';

        if ($this->Id)
            $ok = parent::save();
        else {
            $ok = parent::create();
        }

        if ($ok and ($this->_objeto['tipo'] == 'Web'))
            $this->ponVisibilidad();

        return $ok;
    }

    /**
     * Borrar físicamente el resgistro
     * @return boolean TRUE se se ha borrado correctamente
     */
    public function erase() {
        $ok = parent::erase();
        if ($ok and ($this->_objeto['ambito'] == 'Mod') and ($this->_objeto['tipo'] == 'Web'))
            $this->quitaVisibilidad();

        return $ok;
    }

    /**
     * Comprueba que el ambito y tipo de variables indicadas
     * correspondan a alguno de los valores posibles definidos
     * en $this->ambitosdeVariables y $this->tiposDeVariables respectivamente
     *
     * @param string $ambito El ambito de las variables: Pro, App ó Mod
     * @param string $tipo El tipo de variable: Env, Web
     * @param string $nombre El nombre de la app o del modulo
     *
     * @return boolean TRUE si el ambito y tipo son válidos
     */
    public function carga($ambito, $tipo, $nombre) {

        $ok = ( (in_array($ambito, $this->_ambitosDeVariables)) and (in_array($tipo, $this->_tiposDeVariables)) );

        if ($ok) {
            switch ($ambito) {
                case 'Pro':
                    $variable = "varPro";
                    $this->_titulo = 'Variables ' . $tipo . ' del Proyecto "' . $_SESSION['project']['title'] . '"';
                    $this->_template = "CpanVariables/fieldsVarPro{$tipo}.html.twig";
                    break;

                case 'App':
                    $variable = "varApp_{$nombre}";
                    $app = new Aplicaciones();
                    $app = $app->find('CodigoApp', $nombre);
                    $this->_titulo = 'Variables ' . $tipo . ' de la Aplicación "' . $app->getNombreApp() . '"';
                    unset($app);
                    $this->_template = "CpanVariables/fieldsVar{$ambito}{$tipo}.html.twig";
                    $this->_fileEspecificas = APP_PATH . "modules/{$nombre}/var{$tipo}.yml";
                    break;

                case 'Mod':
                    $variable = "varMod_{$nombre}";
                    $modulo = new Modulos();
                    $modulo = $modulo->find('NombreModulo', $nombre);
                    $this->_titulo = 'Variables ' . $tipo . ' del Módulo "' . $modulo->getTitulo() . '"';
                    unset($modulo);
                    $this->_template = "CpanVariables/fieldsVar{$ambito}{$tipo}.html.twig";
                    $this->_fileEspecificas = APP_PATH . "modules/{$nombre}/var{$tipo}.yml";
                    break;
            }

            $variable .= "_{$tipo}";
            //$filtro = "IdProyectosApps='{$_SESSION['project']['Id']}' AND Variable='{$variable}'";
            $filtro = "Variable='{$variable}'";
            $rows = $this->cargaCondicion('*', $filtro);

            if (isset($rows[0])) {
                $this->bind($rows[0]);
            } else {
                $this->setIdProyectosApps($_SESSION['project']['Id']);
                $this->setVariable($variable);
            }
        } else
            $this->errores[] = "Los valores indicados en ambito y/o tipo no son válidos";

        return $ok;
    }

    /**
     * Quita las variables de entorno del proyecto/app/modulo en curso
     * relativas al control de visibilidad de sus variables web
     *
     * @return void
     */
    private function quitaVisibilidad() {

        $variables = new CpanVariables($this->_objeto['ambito'], 'Env', $this->_objeto['nombre']);
        $variables->setNode('showVarWeb', array());
        $variables->save();
        unset($variables);
    }

    /**
     * Pone las variables de entorno del proyecto/app/modulo en curso
     * relativas al control de visibilidad de sus variables web
     *
     * Respeta los valores que hubiera en el yml del proyecto respecto
     * a las variables web definidas.
     *
     * @return void
     */
    private function ponVisibilidad() {

        $variables = new CpanVariables($this->_objeto['ambito'], 'Env', $this->_objeto['nombre']);
        $valoresActuales = $variables->getNode('showVarWeb');

        $valores['globales'] = array();
        $valores['especificas'] = array();

        if (is_array($this->_objeto['datos']['globales'])) {
            foreach ($this->_objeto['datos']['globales'] as $key => $value)
                if (!isset($valoresActuales['globales'][$key]))
                    $valores['globales'][$key] = 0;
                else
                    $valores['globales'][$key] = $valoresActuales['globales'][$key];
        }

        if (is_array($this->_objeto['datos']['especificas'])) {
            foreach ($this->_objeto['datos']['especificas'] as $key => $value)
                if (!isset($valoresActuales['especificas'][$key]))
                    $valores['especificas'][$key] = 0;
                else
                    $valores['especificas'][$key] = $valoresActuales['especificas'][$key];
        }

        $variables->setNode('showVarWeb', $valores);
        $variables->save();

        unset($variables);
    }

}

?>
