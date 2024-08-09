<?php
require 'Login_function.php'; 
require 'cek.php';
require 'fetch_product.php';

// Handle image upload
function uploadImage($file) {
    $targetDir = "Images/";
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($file["tmp_name"], $targetFilePath);
    return $fileName;
}

ob_start(); 

// Fetch Products with search functionality
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
if ($searchTerm != '') {
    $stmt = $koneksi->prepare("SELECT * FROM product WHERE name LIKE ?");
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("s", $searchTerm);
} else {
    $stmt = $koneksi->prepare("SELECT * FROM product");
}
$stmt->execute();
$result = $stmt->get_result();

// Handle delete product request
if (isset($_POST['deleteproduct'])) {
    $id_product = $_POST['id_product'];
    $stmt = $koneksi->prepare("DELETE FROM product WHERE id_product = ?");
    $stmt->bind_param("i", $id_product);
    if ($stmt->execute()) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product";
    }
    ob_end_flush(); 
    exit;
}

ob_end_flush(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">FurniturQyu</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" method="POST">
            <div class="input-group">
                <input class="form-control" type="text" name="search" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
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
                            Table Produk FurniturQyu
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Warna</th>
                                            <th>Harga</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah</th>
                                            <th>Image</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['color']}</td>
                                                    <td>{$row['price']}</td>
                                                    <td>{$row['desk']}</td>
                                                    <td>{$row['quantity']}</td>
                                                    <td><img src='Images/{$row['image']}' width='50' height='50'></td>
                                                    <td>
                                                        <div class='btn-group' role='group'>
                                                            <button class='btn btn-warning btn-sm edit-btn' data-id='{$row['id_product']}' data-toggle='modal' data-target='#editModal'>Edit</button>
                                                            <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id_product']}'>Delete</button>
                                                        </div>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No products found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Tambah Barang</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; FurniturQyu</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
     $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        console.log('Tombol Edit diklik, ID:', id); // Log ID

        $.ajax({
            url: 'fetch_product.php',
            type: 'post',
            data: { id: id },
            success: function(response) {
                console.log('Respons server:', response); // Log respons server

                try {
                    var data = JSON.parse(response);
                    console.log('Data terurai:', data); // Log data yang terurai

                    if (data.error) {
                        console.error('Kesalahan dari server:', data.error);
                        alert('Kesalahan: ' + data.error);
                    } else {
                        // Set nilai input pada modal edit
                        $('#edit-id').val(data.id_product);
                        $('#edit-name').val(data.name);
                        $('#edit-description').val(data.desk);
                        $('#edit-quantity').val(data.quantity);
                        $('#edit-price').val(data.price);
                        $('#edit-color').val(data.color);
                        if (data.image) {
                            $('#edit-image').attr('src', 'Images/' + data.image);
                        }
                        $('#editModal').modal('show');
                    }
                } catch (e) {
                    console.error('Kesalahan saat mengurai respons JSON:', e);
                    alert('Terjadi kesalahan saat mengambil data produk.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Kesalahan AJAX:', xhr.responseText);
                alert('Terjadi kesalahan saat mengirim permintaan AJAX.');
            }
        });
    });

            // Contoh AJAX untuk menambahkan produk
            $('#formTambahProduk').submit(function(event) {
                event.preventDefault(); // Mencegah form submit bawaan

                var form = $(this);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: 'Tambah_barang.php',
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handling success case
                        alert('Produk berhasil ditambahkan!');
                        // Redirect to index.php after successful addition if necessary
                        window.location.href = 'index.php';
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan:', error);
                        alert('Terjadi kesalahan saat menambahkan produk.');
                    }
                });
            });


        $(document).on('click', '.delete-btn', function() {
            if (confirm('Anda yakin ingin menghapus produk ini?')) {
                var id_product = $(this).data('id');
                $.ajax({
                    url: 'index.php',
                    type: 'post',
                    data: {deleteproduct: true, id_product: id_product},
                    success: function(response) {
                        alert(response); 
                        location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); 
                        alert('Terjadi kesalahan saat menghapus produk.');
                    }
                });
            }
        });

        
    </script>

    <!-- Add Product Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="Tambah_barang.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Nama Barang:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="desk">Deskripsi:</label>
                            <input type="text" class="form-control" id="desk" name="desk">
                        </div>
                        <div class="form-group">
                            <label for="quantity">Jumlah:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                        </div>
                        <div class="form-group">
                            <label for="price">Harga:</label>
                            <input type="number" class="form-control" id="price" name="price">
                        </div>
                        <div class="form-group">
                            <label for="color">Warna:</label>
                            <input type="text" class="form-control" id="color" name="color">
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Edit Product Modal -->
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Edit Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="Edit_barang.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="edit-id" name="id_product">
                    <div class="form-group">
                        <label for="edit-name">Nama Barang:</label>
                        <input type="text" class="form-control" id="edit-name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="edit-description">Deskripsi:</label>
                        <input type="text" class="form-control" id="edit-description" name="desk">
                    </div>
                    <div class="form-group">
                        <label for="edit-quantity">Jumlah:</label>
                        <input type="number" class="form-control" id="edit-quantity" name="quantity">
                    </div>
                    <div class="form-group">
                        <label for="edit-price">Harga:</label>
                        <input type="number" class="form-control" id="edit-price" name="price">
                    </div>
                    <div class="form-group">
                        <label for="edit-color">Warna:</label>
                        <input type="text" class="form-control" id="edit-color" name="color">
                    </div>
                    <div class="form-group">
                        <label for="edit-image">Image:</label>
                        <input type="file" class="form-control" id="edit-image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
