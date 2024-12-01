<?php
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
if (!isset($data['username'], $data['email'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit();
}

// Escapar los datos para evitar inyecciones SQL
$username = htmlspecialchars($data['username']);
$email = htmlspecialchars($data['email']);
$password = htmlspecialchars($data['password']);

// Validar que el correo electrónico no esté vacío y tenga el formato correcto
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
    exit();
}

// Verificar si el correo ya está registrado
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']);
    exit();
}

// Encriptar la contraseña antes de almacenarla
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insertar el nuevo usuario en la base de datos
$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
$stmt->execute([
    'username' => $username,
    'email' => $email,
    'password' => $hashedPassword
]);

// Responder con éxito
echo json_encode(['success' => true, 'message' => 'Registro exitoso']);
?>
