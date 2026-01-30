<?php
session_start();
require __DIR__ . '/../config/db.local.php'; // aquí ya existe $pdo

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare(
        "SELECT username, password FROM users WHERE username = ?"
    );
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header('Location: ../public/index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body class="login-body">

<div class="login-box">
    <h2>Iniciar sesión</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
    <p> Usuario: admin | Contraseña: admin123
    </p>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>

</body>
</html>
