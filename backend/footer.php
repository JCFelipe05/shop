<link rel="stylesheet" href="/student008/shop/backend/footer_style.css">
<footer class="footer">
    <div class="footer-container">
        <div class="footer-links">
            <a href="index.php">Autor: Julen Coll Felipe</a>
            <?php
            include_once "api/weather_api.php";
            $datosClima = obtenerClimaMahon();
            ?>

            <div class="footer-weather" style="margin: 10px 0; font-size: 0.9em;">
                <?php if ($datosClima): ?>
                    <?php
                    $temp = round($datosClima['Temperature']['Metric']['Value']);
                    $texto = $datosClima['WeatherText'];
                    $icon = str_pad($datosClima['WeatherIcon'], 2, "0", STR_PAD_LEFT);
                    ?>
                    <img src="https://developer.accuweather.com/sites/default/files/<?php echo $icon; ?>-s.png" width="20"
                        style="vertical-align: middle;">
                    <span>Mahón: <?php echo $temp; ?>°C, <?php echo $texto; ?></span>
                <?php else: ?>
                <?php endif; ?>
            </div>
            <a href="privacidad.php">Privacidad</a>
        </div>
        <p>&copy; <?php echo date("Y"); ?> Paracordial. Todos los derechos reservados.</p>
    </div>
</footer>