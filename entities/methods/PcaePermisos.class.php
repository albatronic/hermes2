<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 17.10.2012 00:16:48
 */

/**
 * @orm:Entity(PcaePermisos)
 */
class PcaePermisos extends PcaePermisosEntity {

    public function __toString() {
        return $this->getId();
    }

    /**
     * Asigno permiso al usuario-proyecto-app
     * 
     * Y además creo el usuario en el cpanel del proyecto.
     * 
     * @return boolean
     */
    public function create() {

        $id = parent::create();

        if ($id) {
            // Para saber el perfil que tiene el usuario con la empresa.
            $usuario = new PcaeUsuarios($this->IdUsuario);
            $perfil = $usuario->getPerfilEmpresa($this->IdEmpresa);
            $idPerfil = $perfil->getId();
            unset($usuario);
            unset($perfil);

            $proyectoApp = new PcaeProyectosApps();
            $filtro = "IdProyecto='{$this->IdProyecto}' AND IdApp='{$this->IdApp}'";

            $rows = $proyectoApp->cargaCondicion("*", $filtro);
            unset($proyectoApp);
            $row = $rows[0];
            if ($row['Id']) {
                $connection = array(
                    'dbEngine' => $row['DbEngine'],
                    'host' => $row['Host'],
                    'user' => $row['User'],
                    'password' => $row['Password'],
                    'dataBase' => $row['Database'],
                );

                $em = new EntityManager($connection);
                if ($em->getDbLink()) {
                    $query = "select Id from {$connection['dataBase']}.CpanUsuarios where IdUsuario='{$this->IdUsuario}'";
                    $em->query($query);
                    $rows = $em->fetchResult();
                    $id = $rows[0]['Id'];

                    if ($id) {
                        $query = "update {$connection['dataBase']}.CpanUsuarios set IdPerfil='{$idPerfil}' where Id='{$id}'";
                        $em->query($query);
                    } else {
                        $query = "insert into {$connection['dataBase']}.CpanUsuarios (IdUsuario,IdPerfil,IdRol,IdTipoUsuario) values ('{$this->IdUsuario}','{$idPerfil}','1','1');";
                        $em->query($query);
                        $lastId = $em->getInsertId();
                        $query = "update {$connection['dataBase']}.CpanUsuarios set SortOrder='{$lastId}', PrimaryKeyMD5='".md5($lastId)."' WHERE Id='{$lastId}'";
                        $em->query($query);
                    }
                    $em->desConecta();
                }
                unset($em);
            }
        }

        return $id;
    }

}

?>