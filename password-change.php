<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        .change-card {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            background: #fff;
        }
        .change-card-header {
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
            background: teal;
            color: #fff;
            text-align: center;
            padding: 2rem 1rem 1rem 1rem;
        }
        .change-logo {
            display: block;
            margin: 0 auto 0.5rem auto;
            max-width: 120px;
        }
        .change-title {
            font-weight: 600;
            color: #fff;
            text-align: center;
            margin-bottom: 0;
            margin-top: 0.5rem;
        }
        .btn-coral {
            background: teal;
            color: #fff;
            border-radius: 2rem;
            font-weight: bold;
            border: none;
            transition: background 0.2s;
        }
        .btn-coral:hover, .btn-coral:focus {
            background: #ff8882;
            color: #fff;
        }
        .form-control {
            border-radius: 0.5rem;
        }
        .change-links {
            text-align: center;
            margin-top: 1rem;
        }
        .change-links a {
            color: #ff8882;
            text-decoration: none;
        }
        .change-links a:hover {
            color: teal;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-5">
                <div class="card change-card">
                    <div class="change-card-header">
                        <img src="logo.png" alt="Logo" class="change-logo mb-2">
                        <h4 class="change-title mb-0 font-weight-bold">Change Password</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        if(isset($_SESSION['status'])) {
                            echo '<div class="alert alert-info">'.$_SESSION['status'].'</div>';
                            unset($_SESSION['status']);
                        }
                        ?>
                        <form action="password-reset-code.php" method="POST">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" class="form-control" required placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm new password">
                            </div>
                            <button type="submit" name="changePassword" class="btn btn-coral btn-block">Change Password</button>
                        </form>
                        <div class="change-links">
                            <a href="login.php">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>