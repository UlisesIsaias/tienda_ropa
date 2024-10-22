<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Si no ha iniciado sesión, redireccionar al usuario al formulario de inicio de sesión
    header('Location: login.php');
    exit();
}

// Verificar si el usuario tiene el rol de administrador
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Función para cerrar sesión
if (isset($_GET['logout'])) {
    // Eliminar todas las variables de sesión
    session_unset();
    // Destruir la sesión
    session_destroy();
    // Redirigir a la página de inicio de sesión
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BANCHS-Moda online</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <section class="cnt-grid">
        <article class="contenido">
            <?php if ($isAdmin) : ?>
                <a href="admin_stock.php"><h3>Administrar Stock</h3></a>
            <?php endif; ?>
            <a href="carrito.php"><h3>Carrito</h3></a>
            <a href="?logout" style="margin-top: 10px; display: block; color:white;">Cerrar sesión</a>
        </article>
        <article class="contenido">
            <a href="mujer.php"><h3>Mujer</h3></a>
            <a href="hombre.php"><h3>Hombre</h3></a>
            <a href="kids.php"><h3>Kids</h3></a>
        </article>
        <article class="contenido">
            <img src="src/fg.png" alt="Logo">
        </article>
        <article class="contenido">
            <a href="mujer.php"><img src="src/m.jpeg" alt=""></a>
        </article>
        <article class="contenido">
            <a href="hombre.php"><img src="src/h.jpeg" alt=""></a>
        </article>
        <article class="contenido">
            <a href="kids.php"><img src="src/k.jpeg" alt=""></a>
        </article>
    </section>
</body>
</html>
