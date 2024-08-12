<?php
session_start();
include 'koneksi.php';

// Handle Create Post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO post (title, content, date, username) VALUES (:title, :content, NOW(), :username)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        echo "<script>Swal.fire('Success', 'Post created successfully!', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error', 'Failed to create post.', 'error');</script>";
    }
}

// Handle Edit Post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $idpost = $_POST['idpost'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE post SET title = :title, content = :content WHERE idpost = :idpost");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':idpost', $idpost);

    if ($stmt->execute()) {
        echo "<script>Swal.fire('Success', 'Post updated successfully!', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error', 'Failed to update post.', 'error');</script>";
    }
}

// Handle Delete Post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $idpost = $_POST['idpost'];

    $stmt = $conn->prepare("DELETE FROM post WHERE idpost = :idpost");
    $stmt->bindParam(':idpost', $idpost);

    if ($stmt->execute()) {
        echo "<script>Swal.fire('Success', 'Post deleted successfully!', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error', 'Failed to delete post.', 'error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
</head>
<body>
<?php include 'navigation.php'; ?>
<div class="container mt-5">
    <h2>Manage Posts</h2>

    <!-- Create Post Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Create Post</button>

    <!-- Posts Table -->
    <table id="postsTable" class="display table table-bordered table-striped table-hover responsive nowrap" style="width:100%" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Date</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM post");
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($posts as $post) {
                echo "
                <tr>
                    <td>{$post['idpost']}</td>
                    <td>{$post['title']}</td>
                    <td>{$post['content']}</td>
                    <td>{$post['date']}</td>
                    <td>{$post['username']}</td>
                    <td>
                        <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-idpost='{$post['idpost']}' data-title='{$post['title']}' data-content='{$post['content']}'>Edit</button>
                        <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' data-idpost='{$post['idpost']}'>Delete</button>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    <div class="mb-3">
                        <label for="create-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="create-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-content" class="form-label">Content</label>
                        <textarea class="form-control" id="create-content" name="content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit-idpost" name="idpost">
                    <div class="mb-3">
                        <label for="edit-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-content" class="form-label">Content</label>
                        <textarea class="form-control" id="edit-content" name="content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deleteForm">
                    <input type="hidden" id="delete-idpost" name="idpost">
                    <p>Are you sure you want to delete this post?</p>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const createForm = document.getElementById('createForm');
        createForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(createForm);
            formData.append('action', 'create');
            fetch('crud_post.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                location.reload(); // Reload page to update table
            });
        });

        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const idpost = button.getAttribute('data-idpost');
            const title = button.getAttribute('data-title');
            const content = button.getAttribute('data-content');

            const editIdpost = document.getElementById('edit-idpost');
            const editTitle = document.getElementById('edit-title');
            const editContent = document.getElementById('edit-content');

            editIdpost.value = idpost;
            editTitle.value = title;
            editContent.textContent = content;
        });

        const editForm = document.getElementById('editForm');
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(editForm);
            formData.append('action', 'edit');
            fetch('crud_post.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                location.reload(); // Reload page to update table
            });
        });

        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const idpost = button.getAttribute('data-idpost');

            const deleteIdpost = document.getElementById('delete-idpost');
            deleteIdpost.value = idpost;
        });

        const deleteForm = document.getElementById('deleteForm');
        deleteForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(deleteForm);
            formData.append('action', 'delete');
            fetch('crud_post.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                location.reload(); // Reload page to update table
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    $('#postsTable').DataTable({
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
