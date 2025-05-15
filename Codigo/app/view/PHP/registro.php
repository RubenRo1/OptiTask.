<?php
require_once(__DIR__ . '/../../../rutas.php');
require_once(CONTROLLER . 'UsuarioController.php');
require_once(MODEL . 'Usuario.php');

session_start();
$usuarioController = new UsuarioController();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['formCreate']) && $_POST['formCreate'] == 'crearUsuario') {
    if (isset($_POST["correo"], $_POST["contraseña"], $_POST["repetir_contraseña"], $_POST["nombre_usuario"])) {

        $nombreUsuario = htmlspecialchars($_POST["nombre_usuario"]);
        $correo = htmlspecialchars($_POST["correo"]);
        $contraseña = $_POST["contraseña"];
        $repetirContraseña = $_POST["repetir_contraseña"];

        // Validaciones
        if ($contraseña !== $repetirContraseña) {
            $error = "Las contraseñas no coinciden";
        } elseif (strlen($contraseña) < 8) {
            $error = "La contraseña debe tener al menos 8 caracteres";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = "Correo electrónico no válido";
        } elseif ($usuarioController->emailExiste($correo)) {
            $error = "El correo ya está registrado";
        } elseif (strlen($nombreUsuario) < 3) {
            $error = "El nombre de usuario debe tener al menos 3 caracteres";
        } elseif (strlen($nombreUsuario) > 20) {
            $error = "El nombre de usuario no puede tener más de 20 caracteres";
        } else {
            if ($usuarioController->crearUsuario($nombreUsuario, $correo, $contraseña)) {
                $_SESSION['nombre_usuario'] = $nombreUsuario;
                header("Location: inicio.php");
                exit();
            } else {
                $error = "El nombre de usuario ya existe";
            }
        }
    } else {
        $error = "Completa todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>

    <style>
        .container {
            max-width: 500px;
            margin: 5% auto;
            background: #2B2B2B;
            padding: 40px;
            border: solid 1px #414548;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: translateY(-2px);
            box-shadow: 0px 6px 24px rgba(0, 0, 0, 0.25);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            color: #FFFFFF;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group p {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #E0E0E0;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #414548;
            border-radius: 6px;
            background-color: #1E1E1E;
            color: white;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #3A7BFF;
        }

        input[type="submit"] {
            background-color: #3A7BFF;
            color: white;
            border: none;
            width: 100%;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            margin: 25px 0 15px;
            font-weight: bold;
            font-size: 15px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #2A6BEE;
            transform: translateY(-1px);
        }

        input[type="submit"]:active {
            transform: translateY(0);
        }

        #pregunta {
            text-align: center;
            font-size: 14px;
            color: #A0A0A0;
            margin: 20px 0 10px;
        }

        .enlace {
            display: block;
            text-align: center;
            color: #3A7BFF;
            text-decoration: none;
            margin: 8px 0;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .enlace:hover {
            color: #2A6BEE;
        }

        p.error {
            color: #FF6B6B;
            text-align: center;
            margin: 10px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <?php include "../Generales/header.php"; ?>
    <div class="content">
        <div class="container">
            <h2>Registro</h2>

            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="registro.php" method="POST">
                <input type="hidden" name="formCreate" value="crearUsuario">

                <div class="form-group">
                    <p>Correo:</p>
                    <input type="email" name="correo" required>
                </div>

                <div class="form-group">
                    <p>Nombre de usuario:</p>
                    <input type="text" name="nombre_usuario" required>
                </div>

                <div class="form-group">
                    <p>Contraseña:</p>
                    <input type="password" name="contraseña" required>
                </div>

                <div class="form-group">
                    <p>Repetir Contraseña:</p>
                    <input type="password" name="repetir_contraseña" required>
                </div>

                <input type="submit" value="Crear Cuenta">
            </form>

            <p id="pregunta">¿Ya tienes cuenta?</p>
            <a class="enlace" href="../PHP/login.php">Iniciar Sesión</a>
            <a class="enlace" href="../PHP/inicio.php">Volver al Inicio</a>
        </div>
    </div>
</body>

</html>