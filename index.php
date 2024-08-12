<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">My Application</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Beranda</a>
                </li>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="crud_account.php">Akun: CRUD Akun</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="crud_post.php">Post: CRUD Post</a>
                    </li>
                <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'Author'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="crud_post.php">Post: CRUD Post</a>
                    </li>
                <?php endif; ?>
                <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>Welcome to the Home Page</h1>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
