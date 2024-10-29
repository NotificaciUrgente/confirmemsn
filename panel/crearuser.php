<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la clave desde el formulario
    $keySubmitted = $_POST['key'];

    // Intentar abrir el archivo de credenciales
    $filePath = '../credenciales.txt';  // Ajusta la ruta según la estructura de tus directorios
    $file = fopen($filePath, 'r');
    if ($file === false) {
        echo "Error al abrir el archivo de credenciales. Asegúrese de que el archivo existe y tiene los permisos adecuados.";
        exit;  // Salir del script para no continuar
    }

    // Leer la clave desde el archivo
    $keyFromFile = trim(fgets($file));
    fclose($file);

    // Comparar la clave ingresada con la clave en el archivo
    if ($keySubmitted === $keyFromFile) {
        // Inicializar la conexión a la base de datos
        $db = new PDO('sqlite:base-login.db');

        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Verificar si el nombre de usuario ya existe
        $checkUser = $db->prepare("SELECT * FROM users WHERE username = ?");
        $checkUser->execute([$username]);

        if ($checkUser->fetch()) {
            echo "El nombre de usuario ya existe. Por favor, elija otro.";
        } else {
            // Intentar insertar el nuevo usuario
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $password])) {
                echo "Usuario creado exitosamente.";
            } else {
                echo "Error al crear el usuario.";
            }
        }
    } else {
        echo "Clave incorrecta. No tiene permiso para crear usuarios.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(120deg, #89f7fe, #66a6ff);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        form {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 300ms;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <label for="key">Clave de Acceso:</label>
        <input type="text" id="key" name="key" required>

        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Crear Usuario</button>
    </form>
</body>
</html>
