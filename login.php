<?php
require 'config.php';
require 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE username = ?"
    );

    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if (
        $user &&
        password_verify(
            $password,
            $user['password_hash']
        )
    ) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        $_SESSION['role'] =
            $user['role'] ?? 'user';

        header('Location: index.php');
        exit;
    }

    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<?php if ($error): ?>
    <div class="error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST">
<h2>Login</h2>
    <label>Username</label>
    <input
        type="text"
        name="username"
        required
    >

    <label>Password</label>
    <input
        type="password"
        name="password"
        required
    >

    <button type="submit">
        Login
    </button>
    <p>
    Don't have an account?
    <a href="register.php">Register</a>
</p>

</form>

</body>
</html>