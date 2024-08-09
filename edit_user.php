<script>
$(document).ready(function() {
    $(document).on('click', '.delete-user-btn', function() {
        if (confirm('Anda yakin ingin menghapus pengguna ini?')) {
            var id_user = $(this).data('id');
            $.ajax({
                url: 'view_users.php',
                type: 'post',
                data: {deleteuser: true, id_user: id_user},
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

    $(document).on('click', '.edit-user-btn', function() {
        var id_user = $(this).data('id');
        // Muat data pengguna ke dalam modal edit
        $.ajax({
            url: 'get_user.php',
            type: 'get',
            data: {id_user: id_user},
            success: function(response) {
                var user = JSON.parse(response);
                $('#editUserModal #username').val(user.username);
                $('#editUserModal #email').val(user.email);
                $('#editUserModal #role').val(user.role);
                $('#editUserModal #id_user').val(user.id_user);
                $('#editUserModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); 
                alert('Terjadi kesalahan saat memuat data pengguna.');
            }
        });
    });

    $('#editUserModal form').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: 'update_user.php',
            type: 'post',
            data: $(this).serialize(),
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

    $('#addUserModal form').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: 'tambah_user.php',
            type: 'post',
            data: $(this).serialize(),
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
});
</script>
<?php
require 'Login_function.php'; 
require 'cek.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['iduser'] ?? null;
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $role = $_POST['role'] ?? null;

    if ($id_user && $username && $email && $role) {
        if ($password) {
            // Update password if provided
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("UPDATE login SET username = ?, email = ?, password = ?, role = ? WHERE id_user = ?");
            $stmt->bind_param("ssssi", $username, $email, $hashedPassword, $role, $id_user);
        } else {
            // Do not update password if not provided
            $stmt = $koneksi->prepare("UPDATE login SET username = ?, email = ?, role = ? WHERE id_user = ?");
            $stmt->bind_param("sssi", $username, $email, $role, $id_user);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "sukses", "pesan" => "Pengguna berhasil diupdate"]);
        } else {
            echo json_encode(["status" => "gagal", "pesan" => "Terjadi kesalahan saat mengupdate pengguna"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "gagal", "pesan" => "Semua field harus diisi"]);
    }
}
?>
