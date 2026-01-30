<?php
session_start();
$config = require __DIR__ . '/../config/db.local.php';

$conn = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['pass']
);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header('Location: ../public/index.php');
        exit;
    } else {
        $error = 'Credenciales incorrectas';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Iniciar sesión</h2>

<form method="POST">
    <input type="text" name="username" placeholder="Usuario" required><br><br>
    <input type="password" name="password" placeholder="Contraseña" required><br><br>
    <button type="submit">Entrar</button>
</form>

<p style="color:red"><?= $error ?></p>
</body>
</html>
