<?php
require "../config/db.local.php";

// CREATE
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO actor (first_name, last_name) VALUES (?,?)");
    $stmt->execute([$_POST['first'], $_POST['last']]);
}

// DELETE
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM actor WHERE actor_id=?")
        ->execute([$_GET['delete']]);
}

// UPDATE
if (isset($_POST['update'])) {
    $pdo->prepare("UPDATE actor SET first_name=?, last_name=? WHERE actor_id=?")
        ->execute([$_POST['first'], $_POST['last'], $_POST['id']]);
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->query(
        "SELECT * FROM actor WHERE actor_id=".(int)$_GET['edit']
    )->fetch();
}

$actors = $pdo->query("SELECT * FROM actor")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
<h2>CRUD Actor</h2>

<form method="post">
<input type="hidden" name="id" value="<?= $edit['actor_id'] ?? '' ?>">
<input name="first" placeholder="Nombre" value="<?= $edit['first_name'] ?? '' ?>">
<input name="last" placeholder="Apellido" value="<?= $edit['last_name'] ?? '' ?>">

<button name="<?= $edit ? 'update' : 'add' ?>">
<?= $edit ? 'Actualizar' : 'Agregar' ?>
</button>
</form>

<table>
<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Acciones</th></tr>
<?php foreach ($actors as $a): ?>
<tr>
<td><?= $a['actor_id'] ?></td>
<td><?= $a['first_name'] ?></td>
<td><?= $a['last_name'] ?></td>
<td>
<a href="?edit=<?= $a['actor_id'] ?>">Editar</a>
<a class="delete" href="?delete=<?= $a['actor_id'] ?>">Eliminar</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<a href="index.php">Volver</a>
</div>

</body>
</html>
