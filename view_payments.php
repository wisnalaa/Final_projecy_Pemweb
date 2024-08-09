<?php
require 'dbconnect.php';
require 'cek.php';
require 'fetch_payment.php'; // New fetch file for payment details

// Fetch Payments
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $stmt = $koneksi->prepare("SELECT * FROM payment WHERE name LIKE ? OR credit LIKE ? OR cvc LIKE ?");
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("sss", $likeSearch, $likeSearch, $likeSearch);
} else {
    $stmt = $koneksi->prepare("SELECT * FROM payment");
}

$stmt->execute();
$result = $stmt->get_result();

// Handle delete payment request
if (isset($_POST['deletepayment'])) {
    $id_payment = $_POST['idpayment'];
    $stmt = $koneksi->prepare("DELETE FROM payment WHERE id = ?");
    $stmt->bind_param("i", $id_payment);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'sukses', 'pesan' => 'Payment berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Error menghapus payment']);
    }
    exit;
}

// Handle add payment request
if (isset($_POST['addpayment'])) {
    $name = $_POST['name'];
    $credit = $_POST['credit'];
    $cvc = $_POST['cvc'];
    $payment_date = $_POST['payment_date'];

    $stmt = $koneksi->prepare("INSERT INTO payment (name, credit, cvc, payment_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $credit, $cvc, $payment_date);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'sukses', 'pesan' => 'Payment berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Error menambahkan payment']);
    }
    exit;
}

// Handle update payment request
if (isset($_POST['updatepayment'])) {
    $id_payment = $_POST['idpayment'];
    $name = $_POST['name'];
    $credit = $_POST['credit'];
    $cvc = $_POST['cvc'];
    $payment_date = $_POST['payment_date'];

    $stmt = $koneksi->prepare("UPDATE payment SET name = ?, credit = ?, cvc = ?, payment_date = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $credit, $cvc, $payment_date, $id_payment);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'sukses', 'pesan' => 'Payment berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Error memperbarui payment']);
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
    <title>Payment Management - Admin</title>
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
                        <a class="nav-link" href="view_payments.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Payments
                        </a>
                        <div class="sb-sidenav-footer">
                            <div class="small">Logged in as:</div>
                            Admin
                        </div>
                    </div>
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
                            Payment Management
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post" action="view_payments.php" class="form-inline mb-3">
                                    <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Credit</th>
                                            <th>CVC</th>
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['credit']}</td>
                                                    <td>{$row['cvc']}</td>
                                                    <td>{$row['payment_date']}</td>
                                                    <td>
                                                       
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No payments found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPaymentModal">Tambah Payment</button>
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

    document.getElementById('addPaymentFormModal').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('view_payments.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sukses') {
                alert(data.pesan);
                location.reload();
            } else {
                alert(data.pesan);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    document.querySelectorAll('.delete-payment-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this payment?')) {
                const id_payment = this.getAttribute('data-id');

                const formData = new FormData();
                formData.append('deletepayment', true);
                formData.append('idpayment', id_payment);

                fetch('view_payments.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'sukses') {
                        alert(data.pesan);
                        location.reload();
                    } else {
                        alert(data.pesan);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    document.querySelectorAll('.edit-payment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id_payment = this.getAttribute('data-id');
            fetchPaymentDetails(id_payment);
            $('#editPaymentModal').modal('show');
        });
    });

    function fetchPaymentDetails(id_payment) {
        const formData = new FormData();
        formData.append('fetch_payment_details', true);
        formData.append('idpayment', id_payment);

        fetch('fetch_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sukses') {
                const payment = data.payment;
                document.getElementById('editPaymentId').value = payment.id;
                document.getElementById('editPaymentName').value = payment.name;
                document.getElementById('editPaymentCredit').value = payment.credit;
                document.getElementById('editPaymentCvc').value = payment.cvc;
                document.getElementById('editPaymentDate').value = payment.payment_date;
            } else {
                alert(data.pesan);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('editPaymentFormModal').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('view_payments.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sukses') {
                alert(data.pesan);
                location.reload();
            } else {
                alert(data.pesan);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    </script>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Tambah Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentFormModal">
                        <div class="form-group">
                            <label for="addPaymentName">Name</label>
                            <input type="text" class="form-control" id="addPaymentName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="addPaymentCredit">Credit</label>
                            <input type="text" class="form-control" id="addPaymentCredit" name="credit" required>
                        </div>
                        <div class="form-group">
                            <label for="addPaymentCvc">CVC</label>
                            <input type="text" class="form-control" id="addPaymentCvc" name="cvc" required>
                        </div>
                        <div class="form-group">
                            <label for="addPaymentDate">Payment Date</label>
                            <input type="date" class="form-control" id="addPaymentDate" name="payment_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPaymentFormModal">
                        <input type="hidden" id="editPaymentId" name="idpayment">
                        <div class="form-group">
                            <label for="editPaymentName">Name</label>
                            <input type="text" class="form-control" id="editPaymentName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editPaymentCredit">Credit</label>
                            <input type="text" class="form-control" id="editPaymentCredit" name="credit" required>
                        </div>
                        <div class="form-group">
                            <label for="editPaymentCvc">CVC</label>
                            <input type="text" class="form-control" id="editPaymentCvc" name="cvc" required>
                        </div>
                        <div class="form-group">
                            <label for="editPaymentDate">Payment Date</label>
                            <input type="date" class="form-control" id="editPaymentDate" name="payment_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
