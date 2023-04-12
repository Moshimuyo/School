<?php
session_start();

// Replace these with your own database connection details
$db_host = 'localhost';
$db_user = 'username';
$db_pass = 'password';
$db_name = 'database';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user has exceeded their login attempts
if (isset($_SESSION['attempts']) && $_SESSION['attempts'] >= 3) {
    $error = 'You have exceeded the maximum number of login attempts. Please check your email to reset your password.';
    header("Location: login.php?error=" . urlencode($error));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['attempts'] = isset($_SESSION['attempts']) ? $_SESSION['attempts'] + 1 : 1;
        $error = 'Invalid username or password.';
header("Location: login.php?error=" . urlencode($error));
exit;
}
}