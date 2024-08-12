<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE account SET password = :new_password WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':new_password', $new_password);
    $stmt->execute();

    echo "Password telah direset.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>
<body>
<div class="container mt-5">
    <form method="POST" action="reset_password.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="reset-password" name="password" required>
                <span class="input-group-text" id="toggle-reset-password">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
<script>
    const toggleResetPassword = document.querySelector('#toggle-reset-password');
    const resetPassword = document.querySelector('#reset-password');

    toggleResetPassword.addEventListener('click', function (e) {
        const type = resetPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        resetPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
