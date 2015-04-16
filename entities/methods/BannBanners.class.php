<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.12.2012 09:27:33
 */

/**
 * @orm:Entity(BannBanners)
 */
class BannBanners extends BannBannersEntity {

    public function __toString() {
        return $this->Titulo;
    }

    public function fetchAll($column = '', $default = TRUE) {
        if ($column == '')
            $column = 'Titulo';
        return parent::fetchAll($column, $default);
    }

    /**
     * Antes de insertar hay que comprobar que no se exceda
     * el número máximo de banners permitido por zona
     */
    public function validaLogico() {
        parent::validaLogico();

        if ($this->getPrimaryKeyValue() == '') {
            // Voy a insertar
            $zona = new BannZonas($this->IdZona);
            $banner = new BannBanners();
            $rows = $banner->cargaCondicion("count(Id) as nMax", "IdZona='{$this->IdZona}'");
            if ($zona->getNumeroMaximoBanners() < ($rows[0]['nMax'] + 1))
                $this->_errores[] = "Se ha superado el número de banners para la zona {$zona->getTitulo()}. Consulte con el administrador de la web";
            unset($banner);
            unset($zona);
        }

        if (count($this->_errores) == 0)
            $this->OrdenMostrarEnListado = $this->SortOrder;
    }

    /**
     * Devuelve el objeto enlazado o nulo si no existe enlace
     * 
     * @return \Entidad|null Objeto enlazado
     */
    public function getObjetoEnlazado() {

        if (class_exists($this->Entidad))
            return new $this->Entidad($this->IdEntidad);
        else
            return null;
    }

    /**
     * Devuelve un array con los elemementos necesarios para
     * construir un <a href=''> 
     * 
     * Si el banner está enlazado con contra entidad, se tomará la url de dicha entidad
     * En caso contrario se tomará (si existe) la UrlTarget del slider
     * 
     * Tiene dos elmentos:
     * 
     * - url => Es la url en si con el prefijo, que puede ser: nada, http, o https)
     * - targetBlank => Es un flag booleano para saber si el enlace se abrirá en popup o no
     * 
     * @return array Array
     */
    public function getHref() {

        $array = array();

        // Comprobar si el banner está enlazado con otra entidad
        if ($this->Entidad) {
            $objetoEnlazado = $this->getObjetoEnlazado();
            $array = $objetoEnlazado->getHref();
            unset($objetoEnlazado);
        } else {

            $url = $this->getUrlTarget();

            if ($url) {
                $prefijo = ($this->UrlIsHttps) ? "https://" : "http://";
                $url = $prefijo . $url . $this->getUrlParameters();
                $array = array('url' => $url, 'targetBlank' => $this->UrlTargetBlank);
            }
        }

        return $array;
    }

}

?>