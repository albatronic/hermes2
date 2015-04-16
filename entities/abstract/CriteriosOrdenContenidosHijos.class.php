<?php

/**
 * Description of CriteriosOrdenContenidosHijos
 *
 * DEFINICION DE LOS DIFERENTES CRITERIOS DE ORDENACION
 * A LA HORA DE MOSTRAR LOS CONTENIDOS EN LA WEB
 *
 * SE PUEDEN DEFINIR LOS QUE SE QUIERAN.
 *
 * EL VALOR DEL 'Id' SE UTILIZA PARA LA SENTENCIA SQL Y
 * EL 'Value' ES LO QUE SE MUESTRA AL USUARIO.
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 29-oct-2012 13:03:10
 */
class CriteriosOrdenContenidosHijos extends Tipos {

    protected $tipos = array(
        array('Id' => 'SortOrder ASC',    'Value' => 'Orden 0-9'),
        array('Id' => 'SortOrder DESC',   'Value' => 'Orden 9-0'),
        array('Id' => 'PublishedAt ASC',  'Value' => 'Fecha Publicación a-z'),
        array('Id' => 'PublishedAt DESC', 'Value' => 'Fecha Publicación z-a'),
        array('Id' => 'Titulo ASC',       'Value' => 'Título a-z'),
        array('Id' => 'Titulo DESC',      'Value' => 'Título z-a'),        
    );

}

?>
