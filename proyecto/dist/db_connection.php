<?php
// Configuración de la base de datos
$host = "localhost";
$dbname = "casualclothes";
$username = "root";
$password = ""; // En XAMPP, la contraseña de root normalmente está vacía.

try {
    // Crear la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar opciones de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Realizar una consulta de prueba para verificar la conexión
    echo "Conexión exitosa a la base de datos!<br>";

    // Insertar los usuarios en la tabla users
    $insertQuery = "INSERT INTO users (username, email, password) VALUES 
                    ('usuario3', 'usuario3@example.com', 'password3'),
                    ('usuario4', 'usuario4@example.com', 'password4')";
    
    // Ejecutar la consulta de inserción
    $pdo->exec($insertQuery);
    echo "Datos insertados correctamente!<br>";

    // Consultar los datos insertados
    $stmt = $pdo->query("SELECT id, username, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($users) {
        echo "<h3>Usuarios insertados:</h3>";
        foreach ($users as $user) {
            echo "ID: " . $user['id'] . " - Usuario: " . $user['username'] . " - Email: " . $user['email'] . "<br>";
        }
    } else {
        echo "No se encontraron usuarios en la base de datos.<br>";
    }

} catch (PDOException $e) {
    // Mostrar error si la conexión falla
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>

