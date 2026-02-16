<?php
function obtenerClimaMahon() {
    $apiKey = "Api key";
    $locationKey = "304381";
    $url = "https://dataservice.accuweather.com/currentconditions/v1/{$locationKey}?apikey={$apiKey}&language=es";

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Para que devuelva el resultado como string
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Por si hay redirecciones
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);           // Máximo 5 segundos de espera
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignorar errores de certificado SSL (común en localhost)

    // Ejecutar la petición
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "";
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    // Decodificar y devolver datos
    $data = json_decode($response, true);
    return (isset($data[0])) ? $data[0] : null;
}
?>