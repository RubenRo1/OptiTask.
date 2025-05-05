<header>
    <nav>
        <div class="nav-left">
            <ul>
                <li><a href="inicio.php" class="Logo"><span>O</span>pti<span>T</span>ask</a></li>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="tareas.php">Mis Tareas</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </div>
        <div class="nav-right">
            <ul>
                <?php if (isset($_SESSION['nombre_usuario'])): ?>
                    <li style="display: flex; align-items: center; gap: 1px;">

                        <img src="../Imagenes/Logo.png" alt="Foto de perfil" style="width: 65px; height: 45px; border-radius: 50%;">
                        <a class="Space" href="cuenta.php"><?= htmlspecialchars($_SESSION['nombre_usuario']) ?></a>

                    </li>

                <?php else: ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>


<style>
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
        width: 100%;
        padding: 0 20px;
        box-sizing: border-box;
    }

    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    nav .nav-left ul,
    nav .nav-right ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    nav ul li {
        margin-right: 10px;
    }

    nav ul li a {
        text-decoration: none;
        padding: 10px 15px;
        color: white;
        display: block;
        border-radius: 10px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    nav ul li a:hover {
        background-color: grey;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .Logo {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #3A7BFF;
    }

    .Logo span {
        color: #00C897;
    }

    .Space {}
</style>