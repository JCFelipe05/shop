<head>
    <title>Paracordial</title>
    <link rel="stylesheet" href="/student008/shop/backend/header_style.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="/student008/shop/backend/index.php" class="logo">Paracordial</a>
            <?php
            $idioma = $_COOKIE['idioma'] ?? 'es';
            ?>
            <p>Idioma seleccionado: <strong><?= strtoupper(htmlspecialchars($idioma)) ?></strong></p>
            <div class="idioma-selector">
                <form method="POST" action="/student008/shop/backend/set_idioma.php">
                    <select name="idioma" onchange="this.form.submit()">
                        <option value="es" <?= $idioma === 'es' ? 'selected' : '' ?>>Español</option>
                        <option value="en" <?= $idioma === 'en' ? 'selected' : '' ?>>English</option>
                        <option value="ca" <?= $idioma === 'ca' ? 'selected' : '' ?>>Català</option>
                    </select>
                </form>
            </div>
            <ul class="nav-menu">
                <li><a href="/student008/shop/backend/index.php" class="nav-link">Inicio</a></li>
                <li><a href="/student008/shop/backend/products.php" class="nav-link">Productos</a></li>
                <li><a href="/student008/shop/backend/profiles.php" class="nav-link">Usuarios</a></li>
                <li><a href="/student008/shop/backend/orders.php" class="nav-link">Pedidos</a></li>
                <li><a href="/student008/shop/backend/reviews.php" class="nav-link">Reseñas</a></li>
                <li><a href="/student008/shop/backend/cart.php" class="nav-link">Carrito</a></li>
                <li><a href="/student008/shop/backend/forms/form_contact.php" class="nav-link">Contacto</a></li>
                <li><a href="/student008/shop/backend/logout.php" class="nav-link">Logout</a></li>
                <li><a href="/student008/shop/backend/api/api_get_products_stefan.php" class="nav-link">Recibir productos supplier</a></li>
                <li><a href="/student008/shop/backend/pie_chart.php" class="nav-link">Charts</a></li>
            </ul>
        </div>
    </nav>
</body>