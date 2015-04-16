<?php

/**
 * Description of ClientesDentrega
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class ClientesDentrega extends ClientesDentregaEntity {

    protected $Publish = '1';
    
    public function __toString() {
        return $this->getDireccion();
    }

    /**
     * Devuelve un array con todas las direcciones de entrega
     * VIGENTES de un cliente
     *
     * Si se omite el segundo parametro, como valor a mostar se genera un string
     * concatenando la dirección, el codigo postal y la poblacion
     *
     * Cada elemento tiene la primarykey y el valor de $column
     *
     * @param integer $idCliente El id del cliente
     * @param string $column Nombre de la columna a mostrar (opcional)
     * @return array Array con las direcciones de entrega
     */
    public function fetchAll($idCliente, $column = "CONCAT(d.Direccion,' ',d.CodigoPostal,' ',p.Municipio)") {
        
        $this->conecta();

        if (is_resource($this->_dbLink)) {
            
            $poblacion = new Municipios();

            $query = "SELECT d.IDDirec as Id,{$column} as Value 
                        FROM 
                            {$this->_dataBaseName}.{$this->_tableName} as d,
                            {$poblacion->getDataBaseName()}.{$poblacion->getTableName()} as p
                        WHERE
                        (d.IDCliente='{$idCliente}') AND (d.Vigente='1') AND (d.IDPoblacion=p.IDMunicipio)
                        ORDER BY {$column} ASC;";
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
            $this->_em->desConecta();
            unset($this->_em);
            unset($poblacion);
        }
        
        $rows[] = array('Id' => '', Value => ':: Dirección de Entrega');

        return $rows;
    }

}
