<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

require "../config/db.local.php";

/* PAGINACIÓN */
$limit = 25;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

/* CREATE */
if (isset($_POST['add'])) {
    $pdo->prepare(
        "INSERT INTO actor (first_name, last_name) VALUES (?,?)"
    )->execute([$_POST['first'], $_POST['last']]);
    header("Location: actor.php");
    exit;
}

/* UPDATE */
if (isset($_POST['update'])) {
    $pdo->prepare(
        "UPDATE actor SET first_name=?, last_name=? WHERE actor_id=?"
    )->execute([$_POST['first'], $_POST['last'], $_POST['id']]);
    header("Location: actor.php");
    exit;
}

/* DELETE */
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM actor WHERE actor_id=?")
        ->execute([$_GET['delete']]);
    header("Location: actor.php");
    exit;
}

/* EDIT */
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM actor WHERE actor_id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* LIST */
$stmt = $pdo->prepare(
    "SELECT * FROM actor LIMIT :limit OFFSET :offset"
);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$actors = $stmt->fetchAll();

/* TOTAL */
$total = $pdo->query("SELECT COUNT(*) FROM actor")->fetchColumn();
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
<h2>CRUD ACTOR</h2>

<form method="post">
<input type="hidden" name="id" value="<?= $edit['actor_id'] ?? '' ?>">
<input name="first" placeholder="Nombre" value="<?= $edit['first_name'] ?? '' ?>" required>
<input name="last" placeholder="Apellido" value="<?= $edit['last_name'] ?? '' ?>" required>

<button name="<?= $edit ? 'update' : 'add' ?>">
<?= $edit ? 'Actualizar' : 'Agregar nuevo actor' ?>
</button>

<?php if ($edit): ?>
<a href="actor.php">Cancelar edición</a>
<?php endif; ?>
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
<a class="delete" href="?delete=<?= $a['actor_id'] ?>" onclick="return confirm('¿Eliminar actor?')">Eliminar</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<div class="pagination">
<?php for ($i=1; $i<=$pages; $i++): ?>
<a href="?page=<?= $i ?>" <?= $i==$page?'class="active"':'' ?>><?= $i ?></a>
<?php endfor; ?>
</div>

<p>
<a href="index.php">Volver</a>
</div>

</body>
</html>
