<?php

/**
 * GENERAR EL ARCHIVO DE CONFIGURACION YML DE UNA TABLA
 * PARA SU POSTERIOR USO EN EL FORMULARIO DE MANTENIMIENTO
 *
 * NECESITA APOYARSE EN LA CLASE 'TableDescriptor'
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC, SL 15.03.2011
 * @version 1.0
 */

class ConfigYmlBuilder {

    private $buffer;
    private $filename;
    private $td;
    private $primeraColumna = '';
    private $prefijo;

    public function __construct($conection, $table, $prefijo = '') {

        $this->td = new TableDescriptor($conection, $table);
        $this->filename = str_replace(" ", "", $table);
        $this->prefijo = $prefijo;
    }

    /**
     * Devuelve el código yml de configuracion del formulario de mantenimiento
     * @return text
     */
    public function getConfigYml() {
        $this->creaConfigYml();

        return $this->buffer;
    }

    public function getFieldsVarWeb() {
        $this->creaFieldsVarWeb();

        return $this->buffer;
    }

    public function getFieldsVarEntorno() {
        $this->creaFieldsVarEntorno();

        return $this->buffer;
    }

    /**
     * Devuelve un array con tantos nodos como columnas.
     * La key de cada nodo es el nombre de la columna y el valor son los atributos
     *
     * No se generan las columnas incluidas en columnasComunes::$columnasExcepcionConfig
     *
     * @return array Array con los atributos de las columnas
     */
    private function getArrayColumns() {
        $this->primeraColumna = '';
        $arrayColumns = array();
        $nfiltros = 0;

        foreach ($this->td->getColumns() as $column) {
            if (!in_array($column['Field'], columnasComunes::$columnasExcepcionConfig)) {
                if (tiposVariables::$tipos[$column['Type']] === 'date')
                    $column['Length'] = 10;

                $arrayColumns[$column['Field']]['title'] = $column['Field'];

                if ($column['Field'] == $this->td->getPrimaryKey()) {
                    $arrayColumns[$column['Field']]['visible'] = FALSE;
                    $arrayColumns[$column['Field']]['updatable'] = FALSE;
                    $arrayColumns[$column['Field']]['filter'] = FALSE;
                    $arrayColumns[$column['Field']]['list'] = FALSE;
                } else {
                    if ($this->primeraColumna == '')
                        $this->primeraColumna = $column['Field'];
                    if (
                            (!isset($column['ReferencedColumn'])) and ( $column['Type'] != 'date') and ( $column['Type'] != 'tinyint') and ( !in_array($column['Field'], columnasComunes::$columnasExcepcion))
                    )
                        $arrayColumns[$column['Field']]['filter'] = TRUE;
                    else
                        $arrayColumns[$column['Field']]['filter'] = FALSE;
                    $arrayColumns[$column['Field']]['list'] = FALSE;
                    $arrayColumns[$column['Field']]['visible'] = TRUE;
                    $arrayColumns[$column['Field']]['updatable'] = TRUE;
                }
                $arrayColumns[$column['Field']]['default'] = $column['Default'];
                $arrayColumns[$column['Field']]['help'] = null;
                $arrayColumns[$column['Field']]['permission'] = null;
                $arrayColumns[$column['Field']]['translatable'] = FALSE;
                $arrayColumns[$column['Field']]['searchable'] = FALSE;

                $arrayColumns[$column['Field']]['link'] = array(
                    'route' => null,
                    'param' => null,
                    'title' => null,
                    'target' => null,
                    'link' => null,
                );

                //FILTRO ADICIONAL. SE PONE SI:
                // LA COLUMNA REFERENCIA A OTRA ENTIDAD, O
                // ES DE TIPO DATE, O
                // ES DE TIPO TINYINT (ValoresSN)
                if (isset($column['ReferencedColumn'])) {
                    $nfiltros++;
                    $arrayColumns[$column['Field']]['#aditional_filter'] = array(
                        'order' => $nfiltros,
                        'caption' => $column['Field'],
                        'entity' => $column['ReferencedEntity'],
                        'method' => 'fetchAll',
                        'params' => 'Descripcion',
                        'type' => 'select',
                        'operator' => '=',
                        'event' => null,
                    );
                } else {
                    if ($column['Type'] == 'date') {
                        $nfiltros++;
                        $arrayColumns[$column['Field']]['#aditional_filter'] = array(
                            'order' => $nfiltros,
                            'caption' => $column['Field'],
                            'type' => 'range',
                            'operator' => '>=',
                        );
                        $nfiltros++;
                    } elseif ($column['Type'] == 'tinyint') {
                        $nfiltros++;
                        $arrayColumns[$column['Field']]['#aditional_filter'] = array(
                            'order' => $nfiltros,
                            'caption' => $column['Field'],
                            'entity' => 'ValoresSN',
                            'method' => 'fetchAll',
                            'params' => 'Descripcion',
                            'type' => 'select',
                            'operator' => '=',
                            'event' => null,
                        );
                    }
                }

                //VALIDATDOR. NO SE PONE PARA LA PRIMARYKEY
                if ($column['Field'] != $this->td->getPrimaryKey()) {
                    if (isset($column['ReferencedColumn'])) {
                        $valorNullAble = "NO";
                    } else {
                        $valorNullAble = $column['Null'];
                    }

                    $arrayColumns[$column['Field']]['validator'] = array(
                        'nullable' => $valorNullAble,
                        'type' => tiposVariables::$tipos[$column['Type']],
                        'length' => $column['Length'],
                        'min' => null,
                        'max' => null,
                        'message' => 'Valor Requerido',
                    );
                }
            }
        }

        return $arrayColumns;
    }

    private function creaConfigYml() {
        $sinPrefijo = ucwords(str_replace($this->prefijo, "", $this->filename));

        $cabecera = "# Module: " . $sinPrefijo . "\n";
        $cabecera .= "# Document : modules/" . $sinPrefijo . "/config.yml\n#\n";
        $cabecera .= "# @author: Sergio Pérez <sergio.perez@albatronic.com>\n# @copyright: Informática ALBATRONIC SL\n# @date " . date('d.m.Y H:i:s') . "\n";
        $cabecera .= "#\n---\n";

        $arrayDeColumnas = $this->getArrayColumns();

        $array[$sinPrefijo] = array(
            'app' => '',
            'isModuleRoot' => '0',
            'linkModule' => array(
                'fromColumn' => '',
                'toEntity' => '',
                'toColumn' => '',
            ),
            'modulosRelacionables' => '',
            'translatable' => false,
            'searchable' => false,
            'showCommonFields' => false,
            'numMaxRecords' => '',
            'numberOfImages' => '0',
            'withMetadata' => '0',
            'addMetadata' => '0',
            'deleteMetadata' => '0',
            'withGalery' => '0',
            'withDocuments' => '0',
            'withVideos' => 0,
            'withAudios' => '0',
            'maxSizes' => array(
                'image' => '400000',
                'document' => '',
                'video' => '',
                'audio' => '',
            ),
            'galery' => array(
                'maxWidthImage' => '500',
                'maxHeightImage' => '400',
                'generateThumbnail' => '0',
                'widthThumbnail' => '50',
                'heightThumbnail' => '50',
            ),
            'urlFriendlyManagement' => false,
            'fieldGeneratorUrlFriendly' => $this->primeraColumna,
            'metatagTitleManagement' => false,
            'fieldGeneratorMetatagTitle' => $this->primeraColumna,
            'fieldGeneratorMetatagDescription' => $this->primeraColumna,
            'fieldGeneratorMetatagKeywords' => $this->primeraColumna,
            'controller' => $sinPrefijo,
            'action' => 'Index',
            'template' => 'index.html.twig',
            'parametros' => '',
            //'includesHead' => array(
            //    'twigCss' => '_global/css.html.twig',
            //    'twigJs' => '_global/js.html.twig',
            //),
            'login_required' => TRUE,
            'permission_control' => TRUE,
            'favourite_control' => FALSE,
            'help_file' => 'help.html.twig',
            'title' => ucwords($sinPrefijo),
            'id_video' => strtolower($sinPrefijo),
            'url_video' => null,
            'feature_list' => TRUE,
            'entity' => $sinPrefijo,
            'table' => $this->td->getTable(),
            'primarykey' => $this->td->getPrimaryKey(),
            'linkBy' => '',
            'records_per_page' => 10,
            'export' => 'false',
            'isModuleRoot' => 0,
            'order_by' => array(
                array(
                    'title' => $this->td->getPrimaryKey() . " a-z",
                    'criteria' => $this->td->getPrimaryKey() . " ASC",
                ),
                array(
                    'title' => $this->td->getPrimaryKey() . " z-a",
                    'criteria' => $this->td->getPrimaryKey() . " DESC",
                ),
            ),
            'search_default' => $this->td->getPrimaryKey(),
            'referenced_entities' => $this->td->getParentEntities(),
            'columns' => $arrayDeColumnas,
        );

        $yml = sfYaml::dump($array, 4);

        $this->buffer = $cabecera . $yml;
    }

    private function creaFieldsVarWeb() {
        $sinPrefijo = ucwords(str_replace($this->prefijo, "", $this->filename));

        $buf = "# VARIABLES WEB ESPECIFICAS DEL MODULO " . $sinPrefijo . "\n";
        $buf .= "#\n";
        $buf .= "# Module: " . $sinPrefijo . "\n";
        $buf .= "# author Sergio Pérez <sergio.perez@albatronic.com>\n";
        $buf .= "# copyright Informática ALBATRONIC, SL\n";
        $buf .= "# date " . date('d-m-Y H:i:s') . "\n";
        $buf .= "#\n";
        $buf .= "# ejemplo:\n";
        $buf .= "#\n";
        $buf .= "# nombreVariable:\n";
        $buf .= "#   caption: el texto del caption\n";
        $buf .= "#   values: array Id, Value con los valores posibles de la variable (opcional)\n";
        $buf .= "#   default: valor por defecto\n";
        $buf .= "---\n";

        $this->buffer = $buf;
    }

    private function creaFieldsVarEntorno() {
        $sinPrefijo = ucwords(str_replace($this->prefijo, "", $this->filename));

        $buf = "# VARIABLES DE ENTORNO ESPECIFICAS DEL MODULO " . $sinPrefijo . "\n";
        $buf .= "#\n";
        $buf .= "# Module: " . $sinPrefijo . "\n";
        $buf .= "# author Sergio Pérez <sergio.perez@albatronic.com>\n";
        $buf .= "# copyright Informática ALBATRONIC, SL\n";
        $buf .= "# date " . date('d-m-Y H:i:s') . "\n";
        $buf .= "#\n";
        $buf .= "# ejemplo:\n";
        $buf .= "#\n";
        $buf .= "# nombreVariable: el texto del caption\n";
        $buf .= "---\n";

        $this->buffer = $buf;
    }

}
