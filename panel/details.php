<?php
// Conectar a la base de datos
$db = new PDO('sqlite:datos_almacenados.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Asegurarse de que se devuelve una respuesta adecuada
header('Content-Type: application/json');

// Obtener el ID del usuario desde la petición GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode($user);
} else {
    // Devolver un error si no se proporciona ID
    echo json_encode(["error" => "No user ID provided"]);
}
exit;
?>
