<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM account WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
    } else {
        echo "Login failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>
<body>
<div class="container mt-5">
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <span class="input-group-text" id="toggle-password">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <div class="mt-3">
        <a href="forgot_password.php" class="btn btn-link">Forgot Password?</a>
        <a href="register.php" class="btn btn-secondary">Register</a>
    </div>
</div>
<script>
    const togglePassword = document.querySelector('#toggle-password');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute using getAttribue and setAttribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle the eye slash icon
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
