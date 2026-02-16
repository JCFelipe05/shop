<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

try {
    $query = "
        SELECT nombre_producto, total_pedidos, total_ingresos
        FROM pedidos_por_producto
        WHERE total_pedidos > 0
        ORDER BY total_pedidos DESC
        LIMIT 8
    ";

    $result = mysqli_query($conn, $query);
    $nombres = [];
    $cantidades = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $nombres[] = $row['nombre_producto'];
        $cantidades[] = $row['total_pedidos'];
    }

} catch (Exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<style>
    .main-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        padding: 20px;
        font-family: sans-serif;
    }

    .chart-container {
        width: 400px;
        height: 400px;
        margin-bottom: 30px;
    }
</style>

<div class="main-wrapper">
    <h2>Productos Más Pedidos - Top 8</h2>
    <div style="margin-bottom: 20px; display: flex; gap: 10px; justify-content: center;">
        <a href="clients_chart.php"
            style="padding: 10px 20px; background-color: #4e73df; color: white; text-decoration: none; border-radius: 5px;">
            Clientes
        </a>
        <a href="products_chart.php"
            style="padding: 10px 20px; background-color: #1cc88a; color: white; text-decoration: none; border-radius: 5px;">
            Productos
        </a>
        <a href="pie_chart.php"
            style="padding: 10px 20px; background-color: #1cc88a; color: white; text-decoration: none; border-radius: 5px;">
            Suppliers
        </a>
    </div>

    <div class="chart-container">
        <canvas id="chartProductos"></canvas>
    </div>

    <table class="simple-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Total Pedidos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nombres as $index => $nombre): ?>
                <tr>
                    <td><strong><?php echo $nombre; ?></strong></td>
                    <td><?php echo $cantidades[$index]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const etiquetas = <?php echo json_encode($nombres); ?>;
    const datos = <?php echo json_encode($cantidades); ?>;

    new Chart(document.getElementById('chartProductos'), {
        type: 'pie',
        data: {
            labels: etiquetas,
            datasets: [{
                data: datos,
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#f6c23e',
                    '#36b9cc',
                    '#e74a3b',
                    '#858796',
                    '#5a5c69',
                    '#fd7e14'
                ],
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 15,
                        padding: 10
                    }
                }
            }
        }
    });
</script>

<?php
mysqli_close($conn);
include($root_dir . '/student008/shop/backend/footer.php');
?>