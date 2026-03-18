<?php
session_start();
include("../includes/conexion.php");

$error = "";

if(isset($_POST['login'])){
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'");

    if($query && $query->num_rows > 0){
        $_SESSION['admin'] = $usuario;
        header("Location: agregar_producto.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zentra - Acceso</title>
    <style>
        /* Fondo con Gradiente Animado */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animación de Burbujas / Partículas */
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(-45deg, #2c3e50, #4b3621, #6b4f3a, #1a1a1a);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
            overflow: hidden;
            position: relative;
        }

        /* Partículas de fondo */
        .bubbles {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            margin: 0;
            padding: 0;
        }
        .bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            background: rgba(255, 255, 255, 0.1);
            bottom: -150px;
            animation: float 20s linear infinite;
            border-radius: 50%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 380px;
            text-align: center;
            z-index: 1;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .logo-container img {
            width: 130px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
            transition: transform 0.3s;
        }
        .logo-container img:hover { transform: scale(1.05); }

        h2 { color: #2c3e50; margin-bottom: 25px; font-weight: 300; letter-spacing: 1px; }

        .input-group { margin-bottom: 18px; position: relative; text-align: left; }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-sizing: border-box;
            font-size: 15px;
            transition: 0.3s;
            background: #f9f9f9;
        }

        input:focus {
            border-color: #6b4f3a;
            background: #fff;
            box-shadow: 0 0 8px rgba(107, 79, 58, 0.2);
            outline: none;
        }

        button {
            width: 100%;
            padding: 15px;
            background: #4b3621;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.4s;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover { 
            background: #2c3e50; 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(0,0,0,0.3); 
        }

        .error-msg {
            color: #d63031;
            background: #fab1a055;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 14px;
        }

        /* Configuración de las burbujas */
        .bubbles li:nth-child(1){ left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .bubbles li:nth-child(2){ left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .bubbles li:nth-child(3){ left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .bubbles li:nth-child(4){ left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .bubbles li:nth-child(5){ left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
    </style>
</head>
<body>

<ul class="bubbles">
    <li></li><li></li><li></li><li></li><li></li>
</ul>

<div class="login-card">
    <div class="logo-container">
        <img src="../uploads/logo1.png" alt="Zentra Logo">
    </div>

    <h2>ZENTRA ADMIN</h2>

    <form method="POST">
        <div class="input-group">
            <input type="text" name="usuario" placeholder="Usuario" 
                   value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Contraseña" 
                   autocomplete="off" required>
        </div>
        
        <button type="submit" name="login">Ingresar</button>
    </form>

    <?php if($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>
</div>

</body>
</html>