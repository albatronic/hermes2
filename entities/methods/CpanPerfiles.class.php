<?php
/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 04.09.2012 20:32:58
 */

/**
 * @orm:Entity(CpanPerfiles)
 */
class CpanPerfiles extends CpanPerfilesEntity {
	public function __toString() {
		return $this->getPerfil();
	}
        
    /**
     * Devuelve un array con todos los registros de la entidad
     *
     * No devuelve los objetos marcados como borrados
     *
     * Cada elemento tiene la primarykey y el valor de $column
     *
     * Si no se indica valor para $column, se mostrará los valores
     * de la primarykey
     *
     * Su utilidad es básicamente para generar listas desplegables de valores
     *
     * El array devuelto es:
     *
     * array (
     *      '0' => array('Id' => valor primaryKey, 'Value'=> valor de la columna $column),
     *      '1' => .......
     * )
     *
     * @param string $column El nombre de columna a mostrar
     * @param boolean $default Si se añade o no el valor 'Indique Valor'
     * @return array Array de valores Id, Value
     */
    public function fetchAll($column = '', $default = TRUE) {

        if ($column == '')
            $column = $this->getPrimaryKeyName();

        $filtroPerfil = ($_SESSION['usuarioPortal']['IdPerfil'] == '1') ? '1' : "Id<>1";
        
        $this->conecta();

        if (is_resource($this->_dbLink)) {
            $query = "SELECT " . $this->getPrimaryKeyName() . " as Id, $column as Value FROM `{$this->_dataBaseName}`.`{$this->_tableName}` WHERE (Deleted = '0') and ({$filtroPerfil}) ORDER BY $column ASC";
            $this->_em->query($query);
            $rows = $this->_em->fetchResult();
            $this->setStatus($this->_em->numRows());
            $this->_em->desConecta();
            unset($this->_em);
        }

        if ($default == TRUE) {
            $rows[] = array('Id' => '', Value => ':: Indique un Valor');
            sort($rows);
        }

        return $rows;
    }
}
?>