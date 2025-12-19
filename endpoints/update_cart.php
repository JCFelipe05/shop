<?php
// Permitir orígenes específicos
$allowed_origins = [
    'http://localhost',
    'http://127.0.0.1',
    'https://JCFelipe05.github.io' 
];

// Obtener el origen de la petición
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Verificar si el origen está permitido
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

// Headers CORS necesarios
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

?>
<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'];
include($root_dir . '/student008/shop/backend/config/connection.php');

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Usuario no autenticado'
    ]);
    exit();
}

if (!$conn) {
    echo json_encode([
        'success' => false,
        'error' => 'Error de conexión'
    ]);
    exit();
}

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);
$productId = isset($data['product_id']) ? (int) $data['product_id'] : 0;
$action = isset($data['action']) ? $data['action'] : ''; // 'increase' o 'decrease'

if ($productId <= 0 || !in_array($action, ['increase', 'decrease'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Datos inválidos'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener cantidad actual
$sql = "SELECT cantidad FROM 008_carrito 
        WHERE id_producto = $productId AND id_cliente = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode([
        'success' => false,
        'error' => 'Producto no encontrado en el carrito'
    ]);
    exit();
}

$row = mysqli_fetch_assoc($result);
$currentQuantity = (int) $row['cantidad'];

if ($action === 'increase') {
    // Aumentar cantidad
    $newQuantity = $currentQuantity + 1;
    $sql = "UPDATE 008_carrito 
            SET cantidad = $newQuantity 
            WHERE id_producto = $productId AND id_cliente = $user_id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            'success' => true,
            'quantity' => $newQuantity,
            'action' => 'increased'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => mysqli_error($conn)
        ]);
    }
} else { // decrease
    if ($currentQuantity > 1) {
        // Reducir cantidad
        $newQuantity = $currentQuantity - 1;
        $sql = "UPDATE 008_carrito 
                SET cantidad = $newQuantity 
                WHERE id_producto = $productId AND id_cliente = $user_id";

        if (mysqli_query($conn, $sql)) {
            echo json_encode([
                'success' => true,
                'quantity' => $newQuantity,
                'action' => 'decreased'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => mysqli_error($conn)
            ]);
        }
    } else {
        // Eliminar del carrito
        $sql = "DELETE FROM 008_carrito 
                WHERE id_producto = $productId AND id_cliente = $user_id";

        if (mysqli_query($conn, $sql)) {
            echo json_encode([
                'success' => true,
                'quantity' => 0,
                'action' => 'removed'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => mysqli_error($conn)
            ]);
        }
    }
}

mysqli_close($conn);
?>