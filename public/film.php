<?php
require "../middleware/auth.php";
require "../config/db.local.php";

$limit = 25;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

/* CREAR */
if (isset($_POST['add'])) {
    $pdo->prepare(
        "INSERT INTO film (title, rental_rate) VALUES (?,?)"
    )->execute([$_POST['title'], $_POST['rate']]);
}

/* ACTUALIZAR */
if (isset($_POST['update'])) {
    $pdo->prepare(
        "UPDATE film SET title=?, rental_rate=? WHERE film_id=?"
    )->execute([$_POST['title'], $_POST['rate'], $_POST['id']]);
}

/* ELIMINAR */
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM film WHERE film_id=?")
        ->execute([$_GET['delete']]);
}

/* EDITAR */
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM film WHERE film_id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* LISTADO */
$films = $pdo->query(
    "SELECT film_id,title,rental_rate FROM film 
     LIMIT $limit OFFSET $offset"
)->fetchAll();

$total = $pdo->query("SELECT COUNT(*) FROM film")->fetchColumn();
$pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include __DIR__ . "/../partials/navbar.php"; ?>

<div class="container">
<h2>CRUD Film</h2>

<form method="post">
<input type="hidden" name="id" value="<?= $edit['film_id'] ?? '' ?>">
<input name="title" placeholder="Título" value="<?= $edit['title'] ?? '' ?>" required>
<input name="rate" placeholder="Precio" value="<?= $edit['rental_rate'] ?? '' ?>" required>

<button name="<?= $edit ? 'update' : 'add' ?>">
<?= $edit ? 'Actualizar' : 'Agregar nueva película' ?>
</button>

<?php if ($edit): ?>
<a href="film.php">Cancelar edición</a>
<?php endif; ?>
</form>

<table>
<tr>
<th>ID</th><th>Título</th><th>Precio</th><th>Acciones</th>
</tr>
<?php foreach ($films as $f): ?>
<tr>
<td><?= $f['film_id'] ?></td>
<td><?= $f['title'] ?></td>
<td><?= $f['rental_rate'] ?></td>
<td>
<a href="?edit=<?= $f['film_id'] ?>">Editar</a>
<a class="delete" href="?delete=<?= $f['film_id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<div class="pagination">
<?php for ($i=1; $i<=$pages; $i++): ?>
<a href="?page=<?= $i ?>" class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
<?php endfor; ?>
</div>
<p>
<a href="index.php">Volver</a>
</div>

</body>
</html>
