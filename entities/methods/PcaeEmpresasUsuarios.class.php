<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 16.10.2012 16:33:56
 */

/**
 * @orm:Entity(PcaeEmpresasUsuarios)
 */
class PcaeEmpresasUsuarios extends PcaeEmpresasUsuariosEntity {

    public function __toString() {
        return $this->getId();
    }

    public function create() {
        
        $this->setPublish(1);
        return parent::create();
    }
    
    /**
     * Comprueba la unicidad de IdEmpresa-IdUsuario
     */
    public function validaLogico() {

        parent::validaLogico();

        $empUsu = new PcaeEmpresasUsuarios();
        $rows = $empUsu->cargaCondicion("Id", "IdEmpresa='{$this->IdEmpresa}' and IdUsuario='{$this->IdUsuario}'");
        unset($empUsu);
        if (count($rows))
            $this->_errores[] = "Ya existe el usuario en la empresa";
    }

    /**
     * Quita la vinculación entre la empresa y el usuario y todos
     * los permisos que tuviera para los proyectos y apps de la empresa
     * 
     * @return boolean TRUE si el borraro se ha hecho con exito
     */
    public function erase() {
        $idEmpresa = $this->IdEmpresa;
        $idUsuario = $this->IdUsuario;

        $ok = parent::erase();

        if ($ok) {
            // Borrar todos los permisos de acceso del usuario-empresa
            $em = new EntityManager($this->getConectionName());
            if ($em->getDbLink()) {
                $query = "DELETE FROM {$em->getDataBase()}.PcaePermisos WHERE IdEmpresa='{$idEmpresa}' AND IdUsuario='{$idUsuario}'";
                $em->query($query);
                $this->_errores = $em->getError();
                $em->desConecta();
            }
            unset($em);
        }

        return $ok;
            
    }
}

?>