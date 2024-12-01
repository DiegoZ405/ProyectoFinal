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

// Leer los datos JSON del frontend
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos estén presentes
if (!isset($data['email'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit();
}

// Escapar los datos para evitar inyecciones SQL
$email = htmlspecialchars($data['email']);
$password = htmlspecialchars($data['password']);

// Buscar el usuario por correo electrónico
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario existe y la contraseña es correcta
if ($user && password_verify($password, $user['password'])) {
    // Iniciar la sesión y almacenar el ID del usuario
    $_SESSION['id'] = $user['id']; // Asegúrate de que el nombre de la columna sea 'id'

    echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
} else {
    // Error en las credenciales
    echo json_encode(['success' => false, 'message' => 'Correo electrónico o contraseña incorrectos']);
}
