<header>
    <nav>
        <div class="nav-left">
            <ul>
                <li class="Tamaño"><a href="inicio.php" class="Logo"><span>O</span>pti<span>T</span>ask</a></li>
                <li><a href="tareas.php">Crear Tarea</a></li>
                <li><a href="detalleTarea.php">Mis Tareas</a></li>
                <li><a href="favoritos.php">Favoritos</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <ul>
                <?php if (isset($_SESSION['nombre_usuario'])): ?>
                    <li style="display: flex; align-items: center; gap: 10px; height: 50px; overflow: hidden;">
                        <a href="cuenta.php" style="display: flex; align-items: center; padding: 5px 10px; border-radius: 10px; transition: background-color 0.3s, box-shadow 0.3s;">
                            <?php if ($usuario->getImagen() == null): ?>

                                <img src="../Imagenes/Logo3.png" alt="Foto de perfil" class="perfil-img">

                            <?php else: ?>

                                <img src="<?= htmlspecialchars($usuario->getImagen()) ?>" alt="Foto de perfil" class="perfil-img">
                            <?php endif; ?>
                            <span style="line-height: 45px; margin-left: 10px;"><?= htmlspecialchars($_SESSION['nombre_usuario']) ?></span>
                        </a>
                    </li>
                <?php else: ?>
                    <li style="display: flex; align-items: center; justify-content: center;">
                        <a href="login.php">Iniciar sesión</a>
                    </li>
                    <li style="display: flex; align-items: center; justify-content: center;">
                        <a href="registro.php">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

</header>

<style>
    .Tamaño {
        font-size: 30px;

    }

    html,
    body {

        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
        background-color: #2B2B2B;
        color: white;
        font-family: comfortaa, sans-serif;
    }

    .content {
        flex-grow: 1;
        padding-top: 30px;
        transition: left 0.3s ease;
        margin-left: 0;
    }

    .content.sidebar-open {

        position: relative;
        left: 0px;

    }

    header {

        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        background-color: #2B2B2B;
        border-bottom: 1px solid #414548;
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
        display: flex;
        align-items: center
    }

    nav ul li a {
        text-decoration: none;
        padding: 9px 15px;
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

    .perfil-img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #3A7BFF;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
    }
</style>