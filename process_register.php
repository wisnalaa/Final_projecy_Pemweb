<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbmebel";

// Create connection
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Process registration if form is submitted
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $notelp = isset($_POST['notelp']) ? $_POST['notelp'] : null;

    // Escape special characters to avoid SQL injection
    $username = mysqli_real_escape_string($koneksi, $username);
    $password = mysqli_real_escape_string($koneksi, $password);
    $email = mysqli_real_escape_string($koneksi, $email);
    $notelp = mysqli_real_escape_string($koneksi, $notelp);

    // Check if username already exists
    $check_query = "SELECT * FROM login WHERE username = '$username'";
    $result = $koneksi->query($check_query);

    if ($result->num_rows > 0) {
        echo "Username already taken. Please choose another username.";
    } else {
        // Construct the SQL query based on whether 'notelp' is provided
        if ($notelp) {
            $insert_query = "INSERT INTO login (username, password, email, notelp) VALUES ('$username', '$password', '$email', '$notelp')";
        } else {
            $insert_query = "INSERT INTO login (username, password, email) VALUES ('$username', '$password', '$email')";
        }

        if ($koneksi->query($insert_query) === TRUE) {
            // Registration successful, display notification and redirect
            echo "<script>
                    alert('Registration successful! Redirecting to login page...');
                    window.location.href = 'Login.php';
                  </script>";
        } else {
            echo "Error: " . $insert_query . "<br>" . $koneksi->error;
        }
    }
}

// Close connection
$koneksi->close();
?>
