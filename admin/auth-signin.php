<?php
session_start();
include "./db_connect.php"; // Ensure database connection is included


?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8" />
    <title>Sign In | Larkon - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully responsive premium admin dashboard template" />
    <meta name="author" content="Techzaa" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Vendor CSS -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
     <!-- SweetAlert2 JS -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="h-100">
    <div class="d-flex flex-column h-100 p-3">
        <div class="d-flex flex-column flex-grow-1">
            <div class="row h-100">
                <div class="col-xxl-7">
                    <div class="row justify-content-center h-100">
                        <div class="col-lg-6 py-lg-5">
                            <div class="d-flex flex-column h-100 justify-content-center">
                                <div class="auth-logo mb-4">
                                    <a href="index.html" class="logo-dark">
                                        <img src="assets/images/logo-dark.png" height="24" alt="logo dark">
                                    </a>
                                    <a href="index.html" class="logo-light">
                                        <img src="assets/images/logo-light.png" height="24" alt="logo light">
                                    </a>
                                </div>

                                <h2 class="fw-bold fs-24">Sign In</h2>
                                <p class="text-muted mt-1 mb-4">Enter your email address and password to access admin panel.</p>

                                <div class="mb-5">
                                    <form method="POST" class="authentication-form">
                                        <?php if (isset($error_message)): ?>
                                            <script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Oops!',
                                                    text: '<?php echo $error_message; ?>'
                                                });
                                            </script>
                                        <?php endif; ?>

                                        <div class="mb-3">
                                            <label class="form-label" for="example-email">Email</label>
                                            <input type="email" id="example-email" name="example-email" class="form-control" placeholder="Enter your email" required>
                                        </div>
                                        <div class="mb-3">
                                            <a href="auth-password.html" class="float-end text-muted text-unline-dashed ms-1">Reset password</a>
                                            <label class="form-label" for="example-password">Password</label>
                                            <input type="password" id="example-password" name="example-password" class="form-control" placeholder="Enter your password" required>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                            </div>
                                        </div>

                                        <div class="mb-1 text-center d-grid">
                                            <button class="btn btn-soft-primary" type="submit">Sign In</button>
                                        </div>
                                    </form>
                                </div>

                                <p class="text-danger text-center">
                                    Don't have an account? <a href="auth-signup.php" class="text-dark fw-bold ms-1">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 overflow-hidden">
                        <div class="d-flex flex-column h-100">
                            <img src="assets/images/small/img-10.jpg" alt="" class="w-100 h-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.js"></script>
    <script src="assets/js/app.js"></script>

</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     // Retrieve and sanitize input
     $email = trim($_POST['example-email'] ?? '');
     $password = trim($_POST['example-password'] ?? '');
 
     if (empty($email) || empty($password)) {
         $error_message = "Email and Password are required.";
     } else {
         // Prepare SQL to fetch user details
         $stmt = $conn->prepare("SELECT id, name, email, password FROM admin_users WHERE email = ?");
         if (!$stmt) {
             die("Prepare failed: " . $conn->error);
         }
 
         $stmt->bind_param("s", $email);
         $stmt->execute();
         $result = $stmt->get_result();
 
         // Verify user existence and password
         if ($result->num_rows === 1) {
             $user = $result->fetch_assoc();
             if (password_verify($password, $user['password'])) {
                 // Store session variables
                 $_SESSION['user_id'] = $user['id'];
                 $_SESSION['user_name'] = $user['name'];
                 $_SESSION['user_email'] = $user['email'];
 
                 // Redirect to dashboard
                 echo "<script>
                     Swal.fire({
                         icon: 'success',
                         title: 'Login Successful!',
                         text: 'Redirecting to dashboard...',
                         showConfirmButton: false,
                         timer: 2000
                     }).then(() => {
                         window.location.href = 'index.php';
                     });
                 </script>";
                 exit;
             } else {
                 $error_message = "Invalid email or password.";
             }
         } else {
             $error_message = "No account found with this email.";
         }
 
         $stmt->close();
     }
 }
?>
