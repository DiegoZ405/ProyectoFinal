<?php
header("Content-Type: application/json");

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "casualclothes";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Consulta SQL para obtener el producto con id=5
$sql = "SELECT `name`, `description`, `price` FROM `products` WHERE `id` = 5";
$result = $conn->query($sql);

// Verificar si se obtuvo el producto
$product = null;
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo json_encode(["error" => "Producto no encontrado."]);
    exit;
}

// Cerrar la conexión
$conn->close();

// Devolver datos como JSON
echo json_encode($product);
