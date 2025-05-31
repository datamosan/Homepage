<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        .custom-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden; /* Ensures header respects card's border-radius */
        }
        .custom-card-header {
            background: teal;
            color: #fff;
            text-align: center;
            padding: 2rem 1rem 1rem 1rem;
            /* No border-radius here! */
        }
        .custom-btn {
            background: teal;
            color: #fff;
            border-radius: 2rem;
            font-weight: bold;
            transition: background 0.2s;
            border: none;
        }
        .custom-btn:hover, .custom-btn:focus {
            background: #ff8882; 
            color: #fff;
        }
        .logo {
            display: block;
            margin: 0 auto 1rem auto;
            max-width: 120px;
        }
        .text-primary {
            color: #ff8882 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-5">
                <div class="custom-card card">
                    <div class="custom-card-header">
                        <img src="logo.png" alt="Logo" class="logo mb-2">
                        <h4 class="mb-0 font-weight-bold">Reset Password</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        if(isset($_SESSION['status'])) {
                            echo '<div class="alert alert-info">'.$_SESSION['status'].'</div>';
                            unset($_SESSION['status']);
                        }
                        ?>
                        <form action="password-reset-code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="email" class="font-weight-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                            </div>
                            <button type="submit" name="resetPassword" class="btn custom-btn btn-block">Send Password Reset Link</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="login.php" class="text-primary">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>