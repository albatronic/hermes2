<?php

/**
 * Description of TpvController
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 08-oct-2012 12:36:02
 */
include_once 'modules/AlbaranesCab/AlbaranesCabController.class.php';

class TpvController extends AlbaranesCabController {
    
    protected $entity = "Tpv";
    protected $parentEntity = "";
    
    public function IndexAction() {

        $albaran = new AlbaranesCab();
        $albaran->setIDSucursal($_SESSION['suc']);
        $this->values['albaran'] = $albaran;
        unset($albaran);

        return array(
            'values' => $this->values,
            'template' => 'Tpv/index.html.twig',
        );
    }

    public function NavegarAction() {

        if ($this->values['permisos']['permisosModulo']['CO']) {
            $idAlbaran = $this->request['AlbaranesCab']['IDAlbaran'];
            switch ($this->request['accion']) {
                case 'nuevo':
                    $albaran = new AlbaranesCab();
                    $albaran->setIDSucursal($_SESSION['suc']);
                    break;
                case 'crear':
                    $albaran = $this->guardar($this->request['AlbaranesCab']);
                    break;
                case 'buscar':
                    $albaran = new AlbaranesCab($idAlbaran);
                    break;
                case 'guardar':
                    $albaran = $this->guardar($this->request['AlbaranesCab']);
                    break;
                case 'borrar':
                    $albaran = $this->borrar($this->request['AlbaranesCab']);
                    break;
                case 'U':
                    $albaran = $this->getUltimo();
                    break;
                case 'P':
                    $albaran = $this->getPrimero();
                    break;
                case 'A':
                    $albaran = $this->getAnterior($idAlbaran);
                    break;
                case 'S':
                    $albaran = $this->getSiguiente($idAlbaran);
                    break;
            }

            /**
              if ($albaran->getIDEstado()->getIDTipo() == '0') {
              // Si el albaran está pte de confirmar, puedo modificar sus líneas y
              // por lo tanto le añado un objeto linea vacío
              $objetoNuevo = new AlbaranesLineas();
              $objetoNuevo->setIDAlbaran($albaran->getIDAlbaran());
              $lineas[] = $objetoNuevo;
              unset($objetoNuevo);
              }
             */
            $lin = new AlbaranesLineas();
            $rows = $lin->cargaCondicion("IDLinea", "IDAlbaran='{$albaran->getIDAlbaran()}'", "IDLinea ASC");
            unset($lin);

            foreach ($rows as $linea) {
                $lineas[] = new AlbaranesLineas($linea['IDLinea']);
            }

            // Cargo los favoritos del tpv
            $fav = new FavoritosTpv();
            $this->values['favoritos'] = $fav->cargaCondicion("Id,IDArticulo,Descripcion","IDTpv='{$_SESSION['tpv']}'","SortOrder ASC");
            unset($fav);

            $this->values['albaran'] = $albaran;
            $this->values['lineas'] = $lineas;
            $template = "Tpv/index.html.twig";
            unset($albaran);
            unset($lineas);
        } else {
            $template = "_global/forbiden.html.twig";
        }

        return array('template' => $template, 'values' => $this->values);
    }

    private function guardar(array $datos) {
        $albaran = new AlbaranesCab();
        $albaran->bind($datos);
        if ($albaran->getIDAlbaran()) {
            $albaran->save();
        } else {
            $albaran->create();
        }
        return $albaran;
    }

    private function borrar(array $datos) {

        $albaran = new AlbaranesCab($datos['IDAlbaran']);

        if ($albaran->getIDEstado()->getIDTipo() == 0) {
            if ($albaran->erase())
                $albaran = new AlbaranesCab();
        }

        return $albaran;
    }

    private function getUltimo() {

        $albaranes = new AlbaranesCab();
        $filtro = "IDSucursal='{$_SESSION['suc']}'";
        $rows = $albaranes->cargaCondicion("IDAlbaran", $filtro, "NumeroAlbaran DESC LIMIT 1");
        unset($albaranes);

        return new AlbaranesCab($rows[0]['IDAlbaran']);
    }

    private function getPrimero() {

        $albaranes = new AlbaranesCab();
        $filtro = "IDSucursal='{$_SESSION['suc']}'";
        $rows = $albaranes->cargaCondicion("IDAlbaran", $filtro, "NumeroAlbaran ASC LIMIT 1");
        unset($albaranes);

        return new AlbaranesCab($rows[0]['IDAlbaran']);
    }

    private function getSiguiente($idAlbaran) {

        $albaranes = new AlbaranesCab($idAlbaran);
        $filtro = "IDSucursal='{$_SESSION['suc']}' AND NumeroAlbaran>'{$albaranes->getNumeroAlbaran()}'";
        $rows = $albaranes->cargaCondicion("IDAlbaran", $filtro, "NumeroAlbaran ASC LIMIT 1");
        unset($albaranes);

        return new AlbaranesCab($rows[0]['IDAlbaran']);
    }

    private function getAnterior($idAlbaran) {

        $albaranes = new AlbaranesCab($idAlbaran);
        $filtro = "IDSucursal='{$_SESSION['suc']}' AND NumeroAlbaran<'{$albaranes->getNumeroAlbaran()}'";
        $rows = $albaranes->cargaCondicion("IDAlbaran", $filtro, "NumeroAlbaran DESC LIMIT 1");
        unset($albaranes);

        return new AlbaranesCab($rows[0]['IDAlbaran']);
    }

    public function CerrarVentaAction() {
        
    }

}

?>
