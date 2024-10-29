<?php
session_start();

try {
    // Asegúrate de que no hay espacios antes de 'base-login.db'
    $db = new PDO('sqlite:base-login.db');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Preparar consulta SQL
        $stmt = $db->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verificar contraseña
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            header("Location: index-login.php");
            exit;
        } else {
            header("Location: ../index.html");
            exit;
        }
    }
} catch (PDOException $e) {
    // Manejar error de conexión o de consulta
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
