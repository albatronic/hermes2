<?php

/**
 *
 * CLASE CONTENEDORA DE TODOS LOS SERVICIOS WEB
 * 
 * CADA WEBSERVICE ESTÁ IMPLEMENTADO EN UN MÉTODO CONCRETO
 * 
 * LAS LLAMADAS A LOS SERVICIOS SE REALIZAN DE FORMA ESTÁTICA
 * 
 * CADA SERVICIO SE APOYA EN EL MÉTODO COMUN 'getRequest' QUE ES EL QUE
 * VÍA CURL REALIZA LA PETICION AL SERVIDOR
 * 
 * EJEMPLO DE USO:
 * 
 * Obtener el origen del visitante web:
 * 
 * $origen = WebService::getOrigenVisitante('http://origenvisante.com');
 * 
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright (c) 2012, Informática ALBATRONIC, SL
 */
class WebService {

    /**
     * Devuelve la palabra $palabra traducida al idioma $idiomaDestino
     * 
     * Utiliza la api de google de pago. Hay que contratar el servicio
     * 
     * 
     * @param string $palabra La palabra a traducir
     * @param string $idiomaOrigen. Por defecto es, español
     * @param string $idiomaDestino. Por defecto en, inglés
     * @return string La palabra en el idioma destino
     */
    static function translate($palabra, $idiomaOrigen = "es", $idiomaDestino = "en") {

        $url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.8&q={$palabra}&langpair={$idiomaOrigen}|{$idiomaDestino}";

        $resultado = self::getRequest($url);
        //print_r($resultado);

        $objetoJson = $resultado['result'];
        $traduccion = $objetoJson->responseData;
        
        return ($traduccion != null)? utf8_decode($traduccion->translatedText): "";
    }

    /**
     * Obtiene un array con información del origen del visitante
     * 
     * Elementos del array:
     * 
     *      Host => texto
     *      IdAddress => texto
     *      CodigoPais => texto
     *      NombrePais => texto
     *      CodigoRegion => texto
     *      NombreRegion => texto
     *      Ciudad => texto
     *      CodigoPostal => texto
     *      Latitud => float
     *      Longitud => float
     *      ISP => texto
     *      Organizacion => texto
     * 
     * @param url $webService La url del webservice
     * @return array Array con el resultado
     */
    static function getOrigenVisitante($webService) {

        $resultado = self::getRequest($webService);
        print_r($resultado);
        $array = explode(",", $resultado['result']);

        foreach ($array as $key => $value)
            $array[$key] = str_replace('"', '', $value);

        return array(
            'Host' => $array[0],
            'IpAddress' => $array[1],
            'CodigoPais' => $array[2],
            'NombrePais' => $array[3],
            'CodigoRegion' => $array[4],
            'NombreRegion' => $array[5],
            'Ciudad' => $array[6],
            'CodigoPostal' => $array[7],
            'Latitud' => $array[8],
            'Longitud' => $array[9],
            'ISP' => $array[10],
            'Organizacion' => $array[11],
        );
    }

    /**
     * Hace una peticion vía CURL a la url $url y obtiene el resultado
     * en un array que tiene dos elementos, en el elemento 'result'
     * está el resultado propiamente dicho en formato JSON y en el
     * elemento 'info' está el eventual código de error
     *
     * @param string $url La url con la peticion
     * @param string $metodo El método de peticion: GET ó POST, por defecto GET
     * @param string $parametrosPost Los eventuales parametros post en formato: nombre1=valor1&nombre2=valor2
     * @param boolean $formatoJson TRUE si los parametros post vienen en formato Json. Por defecto TRUE
     * @return array Array con dos elementos: result, info
     */
    static function getRequest($url, $metodo = 'GET', $parametrosPost = '', $formatoJson = true) {

        $metodo = strtoupper(trim($metodo));

        if (!in_array($metodo, array('GET', 'POST', 'DELETE'))) {
            $metodo = 'GET';
        }

        $options = array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE,
        );

        switch ($metodo) {
            case 'POST':
                $options[CURLOPT_POST] = 1;
                $options[CURLOPT_POSTFIELDS] = $parametrosPost;
                if ($formatoJson) {
                    $options[CURLOPT_HTTPHEADER] = array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($parametrosPost)
                    );
                }
                break;
            case 'DELETE':
                $options[CURLOPT_CUSTOMREQUEST] = "DELETE";
                $options[CURLOPT_POSTFIELDS] = $parametrosPost;
                break;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return array(
            'result' => $result,
            'info' => $info,
        );
    }

    /**
     * Genera una clave hash
     * 
     * @param array $items Array con los items que se utilizarán para construir el hash
     * @param string $clave La clave. Opcional. Por defecto la indicada en self::$clave
     * @param string $algoritmo El algoritmo a utilizar. Por defecto "sha256"
     * @return string
     */
    private static function getSignature(array $items, $clave = '', $algoritmo = 'sha256') {

        if (!$clave)
            $clave = self::$clave;

        $items[] = $clave;

        $contexto = hash_init($algoritmo);

        foreach ($items as $value)
            hash_update($contexto, $value);

        $hash = hash_final($contexto);

        return strtoupper($hash);
    }

}

?>
