<?php
session_start();
include 'connection.php';

if(isset($_POST['signUp'])) {
    $fullName = $_POST['fullName'];
    $userrole = 2; // Assuming user role is set to 2 for regular users
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        header("Location: signup.php?error=password_mismatch");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Call stored procedure using SQLSRV
    $params = [
        [$fullName, SQLSRV_PARAM_IN],
        [$hashedPassword, SQLSRV_PARAM_IN],
        [$email, SQLSRV_PARAM_IN],
        [$phone, SQLSRV_PARAM_IN],
        [$address, SQLSRV_PARAM_IN],
        [&$status_message, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_STRING('UTF-8')]
    ];
    $stmt = sqlsrv_query($conn, "{CALL CreateUser(?, ?, ?, ?, ?, ?)}", $params);

    if ($stmt === false) {
        echo "<script>alert('Registration failed: " . addslashes(print_r(sqlsrv_errors(), true)) . "'); window.location.href='signup.php';</script>";
        exit();
    }

    if ($status_message !== 'User created successfully') {
        echo "<script>alert('Registration failed: " . addslashes($status_message) . "'); window.location.href='signup.php';</script>";
        exit();
    }

    // Fetch the new user's ID using their email
    $user_id = null;
    $user_id_stmt = sqlsrv_query($conn, "SELECT UserID FROM decadhen.Users WHERE UserEmail = ?", [$email]);
    if ($user_id_row = sqlsrv_fetch_array($user_id_stmt, SQLSRV_FETCH_ASSOC)) {
        $user_id = $user_id_row['UserID'];
        // Create a new cart for the user
        sqlsrv_query($conn, "INSERT INTO decadhen.Cart (CartID, UserID, Status, CreatedDate) VALUES (?, ?, 'active', GETDATE())", [$user_id, $user_id]);
    }

    echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
    exit();
}

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch password, UserRolesID, UserName, and UserID
    $query = "SELECT UserPassword, UserRolesID, UserName, UserID FROM decadhen.Users WHERE UserEmail = ?";
    $stmt = sqlsrv_query($conn, $query, [$email]);
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $stored_hash = $row['UserPassword'];
        $userRolesID = $row['UserRolesID'];
        $userName = $row['UserName'];
        $userID = $row['UserID'];

        if (password_verify($password, $stored_hash)) {
            $firstName = explode(' ', trim($userName))[0];
            $_SESSION['first_name'] = $firstName;
            $_SESSION['user_id'] = $userID;
            $_SESSION['user_roles_id'] = $userRolesID;

            // Fetch active cart ID
            $cart_result = sqlsrv_query($conn, "SELECT CartID FROM decadhen.Cart WHERE UserID = ? AND Status = 'active'", [$userID]);
            if ($cart_row = sqlsrv_fetch_array($cart_result, SQLSRV_FETCH_ASSOC)) {
                $_SESSION['cart_id'] = $cart_row['CartID'];
            }

            if ($userRolesID == 1) {
                echo "<script>
                    alert('Admin login successful! Redirecting to Admin Hub.');
                    window.location.href = 'adminhub.php';
                </script>";
            } else {
                echo "<script>
                    alert('Login successful! Welcome back to Dhen\\'s Kitchen.');
                    window.location.href = 'index.php';
                </script>";
            }
            exit();
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid email or password.'); window.location.href='login.php';</script>";
        exit();
    }
}
?>