<?php
session_start();
require_once 'includes/database.php';

$form_user = $_POST['txtusername'];
$form_password = $_POST['txtpassword'];

if (empty($form_user) || empty($form_password)) {
    echo '<script>alert("Please Fill up all fields!");</script>';
    echo "<script>window.location.href='default.php';</script>";
    exit;
}

$conn = get_db_connection();

$sql = "SELECT user_id, username, password, ac_type, user_status FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $form_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($form_password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['ac_type'] = $user['ac_type'];
        $_SESSION['user_status'] = $user['user_status'];

        if ($user['ac_type'] === 'Administrator' && $user['user_status'] == 0) {
            $_SESSION['status'] = "admin";
            echo "<script>alert('Welcome Back Webmaster Redirecting to personal home page')</script>";
            echo "<script>window.location.href='adminarea/adminhome.php';</script>";
        } else if ($user['ac_type'] === 'user' && $user['user_status'] == 1) {
            echo "<script>alert('Welcome Back')</script>";
            echo "<script>window.location.href='index-1.php';</script>";
        } else {
            echo "<script>window.location.href='index-1.php';</script>";
        }
    } else {
        echo '<script>alert("username and/or password not found! \n\n Signup or Login again");</script>';
        session_unset();
        session_destroy();
        echo "<script>window.location.href='default.php';</script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "ERROR: Could not prepare statement. " . mysqli_error($conn);
}

mysqli_close($conn);
?>