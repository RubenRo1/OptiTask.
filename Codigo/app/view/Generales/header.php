<header>
    <div class="logo-container">
        <img src="ruta/a/tu/logo.png" alt="Logo" class="logo">
    </div>
    <nav>
        <ul>
            <li><a href="inicio.php">Inicio</a></li>
            <li><a href="tareas.php">Mis Tareas</a></li>
            <li><a href="tareas.php">Contacto</a></li>
            <li><?php echo '<a href="cuenta.php">' . htmlspecialchars($nombre_usuario) . '</a>'; ?></li>

            <?php if (!isset($_SESSION['nombre_usuario'])): ?>
                <ul>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                </ul>
            <?php endif; ?>

        </ul>
    </nav>
</header>

<style>
    /* Estilos básicos para el header */
    /* Estilos globales */
    html,
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
    }

    header {
        background-color: #333;
        color: white;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 0 10px;
        width: 100%;
        margin: 0;
        box-sizing: border-box;
    }

    nav ul {
        list-style-type: none;
        margin: 0 10px;
        padding: 0 10px;
        display: flex;
    }

    nav ul li {
        margin-right: 10px;

    }

    nav ul li a {

        text-decoration: none;
        padding: 10px 15px;
        color: white;
        display: block;
        transition: background-color 0.3s, box-shadow 0.3s;
        border-radius: 10px;
    }

    nav ul li a:hover {
        background-color: grey;
        color: white;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }
</style>