<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticulosCombinacionesController
 *
 * @author sergio
 */
class ArticulosCombinacionesController {

    var $entity = "ArticulosCombinaciones";
    var $parentEntity = "Articulos";
    var $request;
    
    public function __construct($request) {
        $this->request = $request;
    }
    
    public function IndexAction() {

        $array = array();

        $atributos = new Propiedades();
        $rows = $atributos->fetchAll('Titulo',false);
        unset($atributos);

        foreach ($rows as $row) {

            $array[$row['Id']]['Titulo'] = $row['Value'];

            $valor = new PropiedadesValores();
            $valores = $valor->getValores($row['Id']);
            unset($valor);

            foreach ($valores as $valor)
                $array[$row['Id']]['Valores'][ $valor['Id']] = $valor['Valor'];
        }

        $articulo = new Articulos();
        $rows = $articulo->cargaCondicion("IDArticulo","BelongsTo='{$this->request[2]}'");
        unset($articulo);
        
        foreach ($rows as $row)
            $articulos[] = new Articulos($row['IDArticulo']);
        
        $this->values['articulos'] = $articulos;
        
        $this->values['propiedadesValores'] = $array;
        $this->values['idArticulo'] = $this->request[2];

        return array(
            'template' => $this->entity . '/index.html.twig',
            'values' => $this->values
        );
    }
    
    public function GenerarAction() {

        $idArticulo = $this->request['idArticulo'];
        $this->request[2] = $idArticulo;
        //print_r($this->request['grupoPropiedades']);
        foreach ($this->request['grupoPropiedades'] as $grupo) {
            $propiedad = explode("|",$grupo);
            $seleccion[$propiedad[0]][$propiedad[2]] = $propiedad[3];
        }
        //print_r($seleccion);
        
        $this->generaCombinaciones($idArticulo,$this->request['grupoPropiedades']);
        
        return $this->IndexAction();
    }

    private function generaCombinaciones($idArticulo,$combinaciones) {
        
        foreach ($combinaciones as $value) {
            $nuevo = new Articulos($idArticulo);
            $nuevo->setIDArticulo('');
            $nuevo->setDescripcion($nuevo->getDescripcion() . " " . $value);
            $nuevo->setBelongsTo($idArticulo);
            $nuevo->setPrimaryKeyMD5('');
            $nuevo->setNivelJerarquico($nuevo->getNivelJerarquico()+1);
           
            $nuevo->create();  
        }
        
    }
}

?>
