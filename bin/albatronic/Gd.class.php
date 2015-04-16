<?php

/**
 * Clase para tratamiento de imágenes.
 *
 * Permite recortar imágenes sin cambiar su proporcionaliadad
 *
 * Ej. de uso:
 *
 * $myThumb = new Gd();
 * $myThumb->load('pathToSourceImage');
 * $myThumb->crop(50,50,'right');
 * $myThumb->save('pathToTargetImage');
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @date 29-sep-2012 12:46:13
 */
class Gd {

    var $image;
    var $type;
    var $width;
    var $height;

    /**
     * Carga la imagen y lee su tipo, anchura y altura
     *
     * @param string $imagePath path completo de la imagen
     */
    public function loadImage($imagePath) {

        //---Tomar las dimensiones de la imagen
        $info = getimagesize($imagePath);

        $this->width = $info[0];
        $this->height = $info[1];
        $this->type = $info[2];

        //---Dependiendo del tipo de imagen crear una nueva imagen
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif($imagePath);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($imagePath);
                break;
        }
    }

    /**
     * Guarda la imagen en el path indicado en $name
     *
     * @param string $name Path de la imagen destino
     * @param integer $quality Calidad de la imagen destino (0 a 100)
     * @return boolean TRUE si éxito al guardar
     */
    public function save($name, $quality = 75) {

        $ok = FALSE;

        //---Guardar la imagen en el tipo de archivo correcto
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $ok = imagejpeg($this->image, $name, $quality);
                break;
            case IMAGETYPE_GIF:
                $ok = imagegif($this->image, $name);
                break;
            case IMAGETYPE_PNG:
                $pngquality = floor(($quality - 10) / 10);
                $this->_preallocate_transparency();
                $ok = imagepng($this->image, $name, $pngquality);
                break;
        }

        return $ok;
    }

    /**
     * Usually when people use PNGs, it's because they need alpha channel 
     * support (that means transparency kids). So here we jump through some 
     * hoops to create a big transparent rectangle which the resampled image 
     * will be copied on top of. This will prevent GD from using its default 
     * background, which is black, and almost never correct. Why GD doesn't do 
     * this automatically, is a good question.
     *
     * @param $w int width of target image
     * @param $h int height of target image
     * @return void
     */
    public function _preallocate_transparency() {
        if (!empty($this->type) && !empty($this->image) && ($this->type == IMAGETYPE_PNG)) {
            if (function_exists('imagecolorallocatealpha')) {
                imagealphablending($this->image, false);
                imagesavealpha($this->image, true);
                $transparent = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
                imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $transparent);
            }
        }
    }

    /**
     * Muestrar la imagen en el navegador sin salvarla previamente
     */
    public function show() {

        //---Mostrar la imagen dependiendo del tipo de archivo
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                imagejpeg($this->image);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->image);
                break;
            case IMAGETYPE_PNG:
                imagepng($this->image);
                break;
        }
    }

    /**
     * Redimensiona la imagen en ancho o alto manteniendo sus proporciones
     *
     * @param int $ancho El nuevo ancho
     * @param int $alto El nuevo alta
     */
    public function resize($ancho, $alto, $modo) {

        switch ($modo) {
            case 'ajustar':
                if ($this->width >= $this->height) {
                    // Imagen cuadrada o más ancha que alta
                    $proporcion = ($this->width / $this->height);
                    $nuevoAncho = $ancho;
                    $nuevoAlto = $ancho / $proporcion;
                    if ($nuevoAlto > $alto) {
                        $nuevoAlto = $alto;
                        $nuevoAncho = $alto * $proporcion;
                    }
                } else {
                    // Imagen más alta que ancha
                    $proporcion = ($this->height / $this->width);
                    $nuevoAlto = $alto;
                    $nuevoAncho = $alto / $proporcion;
                    if ($nuevoAncho > $ancho) {
                        $nuevoAncho = $ancho;
                        $nuevoAlto = $ancho * $proporcion;
                    }
                }

                $image = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                imagecopyresampled($image, $this->image, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $this->width, $this->height);
                break;

            case 'center':
                if ($this->width > $ancho or $this->height > $alto) {
                    $nuevoAncho = ($this->width > $ancho) ? $ancho : $this->width;
                    $nuevoAlto = ($this->height > $alto) ? $alto : $this->height;
                    $desplazamientoHorizontal = abs($this->width - $nuevoAncho) / 2;
                    $desplazamientoVertical = abs($this->height - $nuevoAlto) / 2;
                    $image = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresampled($image, $this->image, 0, 0, $desplazamientoHorizontal, $desplazamientoVertical, $nuevoAncho, $nuevoAlto, $nuevoAncho, $nuevoAlto);
                } else {
                    $nuevoAncho = $ancho;
                    $nuevoAlto = $alto;
                    $desplazamientoHorizontal = 0;
                    $desplazamientoVertical = 0;
                    $image = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresampled($image, $this->image, 0, 0, $desplazamientoHorizontal, $desplazamientoVertical, $nuevoAncho, $nuevoAlto, $this->width, $this->height);
                }

                break;
        }


        //---Crear la imagen dependiendo de la propiedad a variar
        //---Actualizar la imagen y sus dimensiones
        $this->width = imagesx($image);
        $this->height = imagesy($image);
        $this->image = $image;
    }

    /**
     * Crea un thumbnail de la imagen con las medidas especificadas y manteniendo
     * las proporciones visuales de la imagen intactas.
     *
     * $pos puede tomar los valors "left", "top", "right", "bottom" o "center"
     *
     * @param int $cwidth Anchura del thumbnail
     * @param int $cheight Altura del thumbnail
     * @param string $modo Posición desde donde extraer el thumbnail. Defecto 'center'
     */
    public function crop($cwidth, $cheight, $modo = 'ajustar') {

        $this->resize($cwidth, $cheight, $modo);

        //---Crear el background de la imagen
        $image = imagecreatetruecolor($cwidth, $cheight);
        $color = imagecolorallocatealpha($image, 255, 255, 255, 127);
        imagefill($image, 0, 0, $color);

        switch ($modo) {

            case 'ajustar':
                imagecopyresampled($image, $this->image, abs($this->width - $cwidth) / 2, abs($this->height - $cheight) / 2, 0, 0, $this->width, $this->height, $this->width, $this->height);
                break;

            case 'center':
                imagecopyresampled($image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), abs(($this->height - $cheight) / 2), $this->width, $this->height, $this->width, $this->height);
                break;

            case 'left':
                imagecopyresampled($image, $this->image, 0, 0, 0, abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight);
                break;

            case 'right':
                imagecopyresampled($image, $this->image, 0, 0, $this->width - $cwidth, abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight);
                break;

            case 'top':
                imagecopyresampled($image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), 0, $cwidth, $cheight, $cwidth, $cheight);
                break;

            case 'bottom':
                imagecopyresampled($image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), $this->height - $cheight, $cwidth, $cheight, $cwidth, $cheight);
                break;
        }

        $this->image = $image;
    }

}
?>

