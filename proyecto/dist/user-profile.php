<?php
session_start();

// Configuración de la base de datos
$host = "localhost";
$dbname = "casualclothes";
$username = "root";
$password = ""; // En XAMPP, la contraseña de root normalmente está vacía.

try {
    // Crear la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

// Obtener el ID del usuario de la sesión
$user_id = $_SESSION['id'];

// Buscar la información del usuario en la base de datos
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró al usuario y devolver los datos
if ($user) {
    echo json_encode(['success' => true, 'username' => $user['username'], 'email' => $user['email']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}
