<?php
require 'dbconnect.php';
require 'cek.php';
require 'fetch_user.php';

// Fetch Users
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $stmt = $koneksi->prepare("SELECT * FROM login WHERE username LIKE ? OR email LIKE ? OR role LIKE ?");
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("sss", $likeSearch, $likeSearch, $likeSearch);
} else {
    $stmt = $koneksi->prepare("SELECT * FROM login");
}

$stmt->execute();
$result = $stmt->get_result();

// Handle delete user request
if (isset($_POST['deleteuser'])) {
    $id_user = $_POST['iduser'];
    $stmt = $koneksi->prepare("DELETE FROM login WHERE iduser = ?");
    $stmt->bind_param("i", $id_user);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'sukses', 'pesan' => 'User berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Error menghapus user']);
    }
    exit;
}

// Handle add user request
if (isset($_POST['adduser'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $koneksi->prepare("INSERT INTO login (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'sukses', 'pesan' => 'User berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Error menambahkan user']);
    }
    exit;
}

// Handle update user request
if (isset($_POST['updateuser'])) {
    $id_user = $_POST['iduser'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare("UPDATE login SET username = ?, email = ?, password = ?, role = ? WHERE iduser = ?");
        $stmt->bind_param("ssssi", $username, $email, $password, $role, $id_user);
    } else {
        $stmt = $koneksi->prepare("UPDATE login SET username = ?, email = ?, role = ? WHERE iduser = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $id_user);
    }
    if ($stmt->execute()) {
        echo json_encode(['status' => 'sukses', 'pesan' => 'User berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Error memperbarui user']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>User Management - Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">FurniturQyu</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="Logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading"></div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-couch"></i></div>
                            Produk
                        </a>
                        <a class="nav-link" href="view_users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="view_payments.php"> <!-- New link for payment management -->
                            <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Payments
                        </a>
                        <div class="sb-sidenav-footer">
                            <div class="small">Logged in as:</div>
                            Admin
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">FurniturQyu</h1>
                    <div class="card mb-4">
                        <div class="col-xl-3 col-md-6"></div>
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            User Management
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post" action="view_users.php" class="form-inline mb-3">
                                    <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                if ($row['role'] !== 'admin') { // Skip displaying admin row
                                                    echo "<tr>
                                                        <td>{$row['username']}</td>
                                                        <td>{$row['email']}</td>
                                                        <td>{$row['role']}</td>
                                                        <td>
                                                            <div class='btn-group' role='group'>
                                                                <button class='btn btn-warning btn-sm edit-user-btn' data-id='{$row['iduser']}'>Edit</button>
                                                                <button class='btn btn-danger btn-sm delete-user-btn' data-id='{$row['iduser']}'>Delete</button>
                                                            </div>
                                                        </td>
                                                    </tr>";
                                                }
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>No users found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Tambah User</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; FurniturQyu</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>

document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('view_users.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sukses') {
                alert(data.pesan);
                $('#addUserModal').modal('hide'); // Menutup modal setelah berhasil tambah pengguna
                location.reload(); // Me-refresh halaman untuk memperbarui data pengguna
            } else {
                alert(data.pesan);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Event listener untuk form di dalam modal tambah user
    $(document).on('submit', '#addUserModal form', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'view_users.php',
            type: 'post',
            data: $(this).serialize() + '&adduser=true',
            success: function(response) {
                var result = JSON.parse(response);
                alert(result.pesan);
                if (result.status === "sukses") {
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); 
                alert('Terjadi kesalahan saat menambahkan pengguna.');
            }
        });
    });

    // Event listener untuk form di dalam modal edit user
    $(document).on('submit', '#editUserModal form', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'view_users.php',
            type: 'post',
            data: $(this).serialize() + '&updateuser=true',
            success: function(response) {
                var result = JSON.parse(response);
                alert(result.pesan);
                if (result.status === "sukses") {
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); 
                alert('Terjadi kesalahan saat memperbarui pengguna.');
            }
        });
    });

    // Event listener untuk tombol edit user
    $(document).on('click', '.edit-user-btn', function() {
        var id_user = $(this).data('id');
        $.ajax({
            url: 'fetch_user.php',
            type: 'post',
            data: {id_user: id_user},
            success: function(response) {
                var user = JSON.parse(response);
                $('#editUserModal #usernameEditModal').val(user.username);
                $('#editUserModal #emailEditModal').val(user.email);
                $('#editUserModal #roleEditModal').val(user.role);
                $('#editUserModal #iduserEditModal').val(user.iduser);
                $('#editUserModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); 
                alert('Terjadi kesalahan saat mengambil data pengguna.');
            }
        });
    });

    // Event listener untuk tombol hapus user
    $(document).on('click', '.delete-user-btn', function() {
        if (confirm('Anda yakin ingin menghapus pengguna ini?')) {
            var id_user = $(this).data('id');
            $.ajax({
                url: 'view_users.php',
                type: 'post',
                data: {deleteuser: true, iduser: id_user},
                success: function(response) {
                    var result = JSON.parse(response);
                    alert(result.pesan);
                    if (result.status === "sukses") {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); 
                    alert('Terjadi kesalahan saat menghapus pengguna.');
                }
            });
        }
    });
    </script>

    <!-- Add User Modal -->
    <div class="modal" id="addUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addUserFormModal"> <!-- Ganti ID form di sini -->
                    <div class="form-group">
                        <label for="usernameModal">Username:</label>
                        <input type="text" class="form-control" id="usernameModal" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="emailModal">Email:</label>
                        <input type="email" class="form-control" id="emailModal" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordModal">Password:</label>
                        <input type="password" class="form-control" id="passwordModal" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="roleModal">Role:</label>
                        <select class="form-control" id="roleModal" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="editUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editUserFormModal"> <!-- Ganti ID form di sini -->
                    <div class="form-group">
                        <label for="usernameEditModal">Username:</label>
                        <input type="text" class="form-control" id="usernameEditModal" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="emailEditModal">Email:</label>
                        <input type="email" class="form-control" id="emailEditModal" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordEditModal">Password (opsional):</label>
                        <input type="password" class="form-control" id="passwordEditModal" name="password">
                    </div>
                    <div class="form-group">
                        <label for="roleEditModal">Role:</label>
                        <select class="form-control" id="roleEditModal" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <input type="hidden" id="iduserEditModal" name="iduser">
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
