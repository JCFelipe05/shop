<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');
include($root_dir . '/student008/shop/backend/header.php');

try {
    $query = "
        SELECT nombre, total_pedidos, total_gastado
        FROM pedidos_por_cliente
        WHERE total_pedidos > 0
        ORDER BY total_gastado DESC
        LIMIT 10
    ";

    $result = mysqli_query($conn, $query);
    $nombres = [];
    $totales = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $nombres[] = $row['nombre'];
        $totales[] = $row['total_gastado'];
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
        width: 600px;
        height: 400px;
        margin-bottom: 30px;
    }
</style>

<div class="main-wrapper">
    <h2>Pedidos por Cliente - Top 10</h2>
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
        <canvas id="chartClientes"></canvas>
    </div>

    <table class="simple-table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Total Gastado (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nombres as $index => $nombre): ?>
                <tr>
                    <td><strong><?php echo $nombre; ?></strong></td>
                    <td><?php echo number_format($totales[$index], 2); ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const etiquetas = <?php echo json_encode($nombres); ?>;
    const datos = <?php echo json_encode($totales); ?>;

    new Chart(document.getElementById('chartClientes'), {
        type: 'bar',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Total Gastado (€)',
                data: datos,
                backgroundColor: '#4e73df',
                borderColor: '#2e59d9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return value + ' €';
                        }
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