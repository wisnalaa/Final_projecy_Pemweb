<?php
$koneksi = mysqli_connect("localhost", "root", "", "dbmebel");

// Fetch payment details for editing
if (isset($_POST['fetch_payment_details'])) {
    $id_payment = $_POST['idpayment'];
    $stmt = $koneksi->prepare("SELECT * FROM payment WHERE id = ?");
    $stmt->bind_param("i", $id_payment);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        echo json_encode(['status' => 'sukses', 'payment' => $payment]);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Payment not found']);
    }
    exit;
}
?>
