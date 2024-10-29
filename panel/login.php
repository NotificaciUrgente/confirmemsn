<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DarkScam</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: Arial, sans-serif;
            color: #333;
        }
        form {
            padding: 40px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: appear 600ms ease-out forwards;
            width: 300px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            margin-bottom: 20px;
            width: calc(100% - 16px);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 10px 20px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 300ms;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .credits {
            text-align: center;
            font-size: 0.8em;
            color: #fff;
            margin-top: 15px;
        }
        @keyframes appear {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <form action="verify.php" method="post">
        <h2>DarkScam</h2>
        <div class="credits">by: @dark_shoppe</div>
        <label for="username">Usuario</label>
        <input type="text" id="username" name="username" placeholder="Usuario" required>
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Contraseña" required>
        <input type="submit" value="Inicia sesión">
    </form>
</body>
</html>
