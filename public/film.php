<?php
require "../config/db.php";

// CREATE
if (isset($_POST['add'])) {
    $pdo->prepare(
        "INSERT INTO film (title, rental_rate) VALUES (?,?)"
    )->execute([$_POST['title'], $_POST['rate']]);
}

// DELETE
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM film WHERE film_id=?")
        ->execute([$_GET['delete']]);
}

$films = $pdo->query(
    "SELECT film_id,title,rental_rate FROM film LIMIT 50"
)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
<h2>CRUD Film</h2>

<form method="post">
<input name="title" placeholder="Título">
<input name="rate" placeholder="Precio">
<button name="add">Agregar</button>
</form>

<table>
<tr><th>ID</th><th>Título</th><th>Precio</th><th>Acción</th></tr>
<?php foreach ($films as $f): ?>
<tr>
<td><?= $f['film_id'] ?></td>
<td><?= $f['title'] ?></td>
<td><?= $f['rental_rate'] ?></td>
<td>
<a class="delete" href="?delete=<?= $f['film_id'] ?>">Eliminar</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<a href="index.php">Volver</a>
</div>

</body>
</html>
