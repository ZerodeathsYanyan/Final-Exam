<?php
session_start();
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Ensure that the user is an HR
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'HR') {
    header('Location: login.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (!empty($title) && !empty($description)) {
        // Add the job post to the database
        addJobPost($pdo, $_SESSION['user_id'], $title, $description);
        header('Location: index.php');
        exit();
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Job Post</h1>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="addjobpost.php" method="POST">
            <label for="title">Job Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Job Description:</label>
            <textarea id="description" name="description" required></textarea>

            <input type="submit" value="Add Job Post">
        </form>
    </div>
</body>
</html>
