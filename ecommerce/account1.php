<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: Login.html');
    exit;
}

$username = $_SESSION['username'];

// Connect to the ecommerce_user_profile_db database
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "ecommerce_user_profile_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user profile data
$stmt = $conn->prepare("SELECT name, email, bio, birthday, country, phone, address, profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($name, $email, $bio, $birthday, $country, $phone, $address, $profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile App - Account</title>
    <!-- Add your stylesheets and scripts here -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Overpass&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/Account.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <!-- Header content -->
        <div class="header">
            <div class="logo">
                <a href="html/Account.html">
                    <img src="Images/logo.png" alt="Logo" width="150">
                </a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Cari...">
            </div>
            <div class="user-links">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="Images/person.png" alt="person" width="30">
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="Account.html">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="Login.html" id="logoutBtn">Logout</a>
                    </div>
                </div>
                <a href="#">
                    <img src="Images/love.png" alt="love" width="30">
                </a>
                <a href="#">
                    <img src="Images/shopping-cart.png" alt="cart" width="30">
                </a>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div class="row">
            <div class="col1">
                <a href="Homepage.html">Home </a> 
                <a href="Shop.html">Shop</a> 
                <a href="Produk.html">Products </a>  
                <a href="AboutUs.html">About Us </a>
                <a href="contact.html">Contacts </a> 
            </div>
        </div>
    </header>
    </header>
    <!-- Notification -->
    <div id="notification" style="display:none;" class="alert alert-success">
        Berhasil tersimpan
    </div>

    <!-- Profile Form -->
    <form id="profileForm" action="php/save_user.php" method="post">
        <div class="container light-style flex-grow-1 container-p-y">
            <h4 class="font-weight-bold py-3 mb-4">Account settings</h4>
            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-info">Info</a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="account-general">
                                    <div class="card-body media align-items-center">
                                        <img src="Images/profile.png" alt="profile" class="d-block ui-w-80">
                                        <div class="media-body text-center">
                                        </div>
                                    </div>
                                </form>
                                <hr class="border-light m-0">
                                <!-- User Information Form -->
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control mb-1" name="username" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="text" class="form-control mb-1" name="email" value="">
                                        <div class="alert alert-warning mt-3">
                                            Your email is not confirmed. Please check your inbox.<br>
                                            <a href="javascript:void(0)">Resend confirmation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Change Password Form -->
                            <div class="tab-pane fade" id="account-change-password">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Current password</label>
                                        <input type="password" class="form-control" name="current_password">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New password</label>
                                        <input type="password" class="form-control" name="new_password">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Repeat new password</label>
                                        <input type="password" class="form-control" name="repeat_new_password">
                                    </div>
                                </div>
                            </div>
                            <!-- Additional Information Form -->
                            <div class="tab-pane fade" id="account-info">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Full Address</label>
                                        <textarea class="form-control" name="bio" rows="5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nunc arcu, dignissim sit amet sollicitudin iaculis, vehicula id urna. Sed luctus urna nunc. Donec fermentum, magna sit amet rutrum pretium, turpis dolor molestie diam, ut lacinia diam risus eleifend sapien. Curabitur ac nibh nulla. Maecenas nec augue placerat, viverra tellus non, pulvinar risus.</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Birthday</label>
                                        <input type="date" class="form-control" name="birthday" value="2003-11-09">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <select class="custom-select" name="country">
                                            <option>Indonesia</option>
                                            <option selected>Indonesia</option>
                                            <option>UK</option>
                                            <option>Germany</option>
                                            <option>France</option>
                                            <option>Malaysia</option>
                                            <option>China</option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="address" value="Bumi Manti 4 no 75">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control mb-1" name="phone" value="081290809703">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>
    <main>
        <h1>Account Details</h1>
        <img src="<?php echo $profile_picture; ?>" alt="Profile Picture">
        <p><strong>Username:</strong> <?php echo $username; ?></p>
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Bio:</strong> <?php echo $bio; ?></p>
        <p><strong>Birthday:</strong> <?php echo $birthday; ?></p>
        <p><strong>Country:</strong> <?php echo $country; ?></p>
        <p><strong>Phone:</strong> <?php echo $phone; ?></p>
        <p><strong>Address:</strong> <?php echo $address; ?></p>
    </main>
    <footer>
        <!-- Footer content -->
        <section id="footer">
        <div class="footer">
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="Images/logo.png" alt="Logo">
                </div>
                <div>
                    <p>Email: furniturQyu@gmail.com</p>
                    <br>
                    <p>Alamat: Jalan Contoh No. 123</p>
                    <br>
                    <p>Nomor HP: 08123456789</p>
                </div>
            </div>
            
            <div class="footer-center">
                <div class="useful-links">
                    <h4>Useful Links</h4>
                    <a href="#">About Us</a>
                    <a href="#">Contact Us</a>
                    <a href="#">Blog</a>
                </div>
                <div class="idea-advice">
                    <h4>Idea & Advice</h4>
                    <a href="#">Reviews</a>
                    <a href="#">Get Design Help</a>
                    <a href="#">Material Care</a>
                </div>
            </div>
    
            <div class="footer-right">
                <div class="payment-methods">
                    <h4>Payments Method</h4>
                    <img src="Images/pay.png" alt="pay">
                </div>
            </div>
        </div>
    </section>

    </footer>
    
</body>

</html>
<script>
   $(document).ready(function() {
    // Fetch user profile data
    $.ajax({
        url: 'php/fetch_user_profile.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response); 

            if (response.status) {
                $('input[name="username"]').val(response.data.username);
                $('input[name="name"]').val(response.data.name);
                $('input[name="email"]').val(response.data.email);
                $('textarea[name="bio"]').val(response.data.bio);
                $('input[name="birthday"]').val(response.data.birthday);
                $('select[name="country"]').val(response.data.country);
                $('input[name="address"]').val(response.data.address);
                $('input[name="phone"]').val(response.data.phone);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("Failed to fetch user profile data.");
        }
    });

    // Handle form submission
    $('#profileForm').on('submit', function(event) {
        event.preventDefault(); 
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response); 
                $('#notification').text("Berhasil tersimpan").show().delay(3000).fadeOut(); 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Failed to submit data.');
            }
        });
    });

    $('#cancelBtn').on('click', function() {
        $('#profileForm')[0].reset();
    });
});