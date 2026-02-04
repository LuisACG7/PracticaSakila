<?php
require "../middleware/auth.php";
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include __DIR__ . "/../partials/navbar.php"; ?>

<div class="container">
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['user']) ?></h1>

    <p class="style-tag">
        Más de 1000 películas y 200 actores disponibles en nuestra base de datos.
        Navega a Actores o Películas para gestionar el contenido.
    </p>
</div>

</body>
</html>
