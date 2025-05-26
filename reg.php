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

    // Debugging output
    echo "<script>alert('Debug: $fullName, $hashedPassword, $email, $phone, $address');</script>";

    // Prepare and execute the stored procedure
    $stmt = $conn->prepare("CALL CreateUser(?,?,?,?,?, @statusmsg)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssss", $fullName, $hashedPassword, $email, $phone, $address);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // Retrieve the OUT parameter
    $result = $conn->query("SELECT @statusmsg AS status_message");
    if ($result) {
        $row = $result->fetch_assoc();
        $status_message = $row['status_message'];
        if ($status_message !== 'User created successfully') {
            // Show error dialog
            echo "<script>alert('Registration failed: " . addslashes($status_message) . "'); window.location.href='signup.php';</script>";
            exit();
        }
        // Show success dialog
        echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
        exit();
    } else {
        // Show error dialog for status message retrieval
        echo "<script>alert('Failed to retrieve status message: " . addslashes($conn->error) . "'); window.location.href='signup.php';</script>";
        exit();
    }
}

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch password, UserRolesID, UserName, and UserID
    $query = "SELECT UserPassword, UserRolesID, UserName, UserID FROM Users WHERE UserEmail = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_hash, $userRolesID, $userName, $userID);
    if ($stmt->fetch() && password_verify($password, $stored_hash)) {
        // Get first name from UserName
        $firstName = explode(' ', trim($userName))[0];
        $_SESSION['first_name'] = $firstName;
        $_SESSION['user_id'] = $userID; // Store UserID in session

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
        echo "Invalid email or password\nError: " .$conn->error;
    }
    $stmt->close();
}
?>