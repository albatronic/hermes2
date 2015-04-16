<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.09.2012 20:32:58
 */

/**
 * @orm:Entity(CpanModulos)
 */
class CpanModulos extends CpanModulosEntity {

    public function __toString() {
        return $this->getNombreModulo();
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'NombreModulo';
        return parent::fetchAll($column, $default);
    }
    
    
    /**
     * Devuelve un array con los módulos que están sujetos a gestión
     * de etiquetas, según el valor de la variable de entorno del proyecto 'modulosConEtiquetas'
     * 
     * El array tendrá n elementos (tantos como módulos), y cada elemento es:
     * 
     * - Id => el id del módulo
     * - Value => el título del módulo (no el nombre)
     * 
     * @return array 
     */
  
    public function getModulosConEtiquetas() {

        $modulos = array();
        
        $variables = new CpanVariables('Pro', 'Env');
        $modulosConEtiquetas = explode(",", trim($variables->getNode('modulosConEtiquetas')));
        unset($variables);

        $objetoModulo = new CpanModulos();
        
        foreach ($modulosConEtiquetas as $moduloConEtiquetas) {
            $modulo = $objetoModulo->find('NombreModulo', trim($moduloConEtiquetas));
            $modulos[] = array(
                'Id' => $modulo->getId(),
                'Value' => $modulo->getTitulo()
            );
        } 
       
        unset($objetoModulo);
        unset($modulo);
        
        return $modulos;
    }    
}
?>