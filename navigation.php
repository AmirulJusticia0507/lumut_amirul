<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">My Application</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <a class="nav-link" href="#" id="logoutButton">Logout</a>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent the default action

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to log out?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log out!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to logout.php if confirmed
                        window.location.href = 'logout.php';
                    }
                });
            });
        }
    });
</script>
