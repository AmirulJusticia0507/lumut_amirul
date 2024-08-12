<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO account (username, password, name, role) VALUES (:username, :password, :name, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>
<body>
<div class="container mt-5">
    <form method="POST" action="register.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="register-password" name="password" required>
                <span class="input-group-text" id="toggle-register-password">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role">
                <option value="Admin">Admin</option>
                <option value="Author">Author</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
<script>
    const toggleRegisterPassword = document.querySelector('#toggle-register-password');
    const registerPassword = document.querySelector('#register-password');

    toggleRegisterPassword.addEventListener('click', function (e) {
        const type = registerPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        registerPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
