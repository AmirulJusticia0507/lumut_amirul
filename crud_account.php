<?php
session_start();
include 'koneksi.php';

// Create Account
if (isset($_POST['create'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $_POST['name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO account (username, password, name, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $name, $role]);

    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Account created successfully!',
            icon: 'success'
        }).then(function() {
            window.location = 'crud_account.php';
        });
    </script>";
}

// Update Account
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    if ($password) {
        $stmt = $conn->prepare("UPDATE account SET name = ?, role = ?, password = ? WHERE username = ?");
        $stmt->execute([$name, $role, $password, $username]);
    } else {
        $stmt = $conn->prepare("UPDATE account SET name = ?, role = ? WHERE username = ?");
        $stmt->execute([$name, $role, $username]);
    }

    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Account updated successfully!',
            icon: 'success'
        }).then(function() {
            window.location = 'crud_account.php';
        });
    </script>";
}

// Delete Account
if (isset($_POST['delete'])) {
    $username = $_POST['username'];

    $stmt = $conn->prepare("DELETE FROM account WHERE username = ?");
    $stmt->execute([$username]);

    echo "<script>
        Swal.fire({
            title: 'Deleted!',
            text: 'Account has been deleted.',
            icon: 'success'
        }).then(function() {
            window.location = 'crud_account.php';
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Account Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

</head>
<body>
<?php include 'navigation.php'; ?>
<div class="container mt-5">
    <h2>Account Management</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Create New Account</button>

    <!-- Table -->
    <table class="display table table-bordered table-striped table-hover responsive nowrap" style="width:100%" id="accountDataTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM account");
            $stmt->execute();
            $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $no = 1;
            foreach ($accounts as $account) {
                echo "
                <tr>
                    <td>{$no}</td>
                    <td>{$account['username']}</td>
                    <td>{$account['name']}</td>
                    <td>{$account['role']}</td>
                    <td>
                        <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-username='{$account['username']}' data-name='{$account['name']}' data-role='{$account['role']}'>Edit</button>
                        <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' data-username='{$account['username']}'>Delete</button>
                    </td>
                </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="crud_account.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create New Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="author">Author</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create" class="btn btn-success">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="crud_account.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit-username" name="username" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password" class="form-label">Password (Leave blank if not changing)</label>
                        <input type="password" class="form-control" id="edit-password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-role" class="form-label">Role</label>
                        <select class="form-select" id="edit-role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="author">Author</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="crud_account.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this account?</p>
                    <input type="hidden" id="delete-username" name="username">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const username = button.getAttribute('data-username');
        const name = button.getAttribute('data-name');
        const role = button.getAttribute('data-role');

        const modalUsername = editModal.querySelector('#edit-username');
        const modalName = editModal.querySelector('#edit-name');
        const modalRole = editModal.querySelector('#edit-role');

        modalUsername.value = username;
        modalName.value = name;
        modalRole.value = role;
    });

    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const username = button.getAttribute('data-username');

        const modalUsername = deleteModal.querySelector('#delete-username');
        modalUsername.value = username;
    });
</script>
<script>
$(document).ready(function() {
    $('#accountDataTable').DataTable({
        responsive: true,
        scrollX: true,
        searching: false, // Matikan pencarian DataTables karena kita sudah punya form pencarian sendiri
        lengthMenu: [10, 25, 50, 100, 500, 1000],
        pageLength: 10,
        dom: 'lBfrtip',
        processing: true,
        serverSide: true
    });
});
</script>
</body>
</html>
