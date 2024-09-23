<?php
session_start();
include_once('connection.php');

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$tipo = $_SESSION['tipo'];  // Use this if you need to show different info for admins/users
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Profile of <?= htmlspecialchars($username) ?></h1>

    <!-- Display user-specific info -->
    <p>Welcome, <?= htmlspecialchars($username) ?>!</p>

    <?php if ($tipo === 'admin'): ?>
        <p>You are an admin.</p>
    <?php else: ?>
        <p>You are a regular user.</p>
    <?php endif; ?>

    <a href="logout.php">Logout</a>
</body>

</html>
