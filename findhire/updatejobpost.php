<?php
session_start();
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Ensure the user is HR
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'HR') {
    header('Location: login.php');
    exit();
}

$error = '';
if (isset($_GET['job_id'])) {
    $job_id = (int)$_GET['job_id'];
    // Retrieve the job post details for editing
    $stmt = $pdo->prepare("SELECT * FROM job_posts WHERE job_id = :job_id");
    $stmt->bindValue(':job_id', $job_id, PDO::PARAM_INT);
    $stmt->execute();
    $jobPost = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$jobPost) {
        echo "Job post not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        if (!empty($title) && !empty($description)) {
            // Update the job post
            $stmt = $pdo->prepare("UPDATE job_posts SET title = :title, description = :description WHERE job_id = :job_id");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'job_id' => $job_id
            ]);
            header('Location: index.php');
            exit();
        } else {
            $error = 'Please fill in all fields.';
        }
    }
} else {
    echo "No job ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Job Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Update Job Post</h1>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="updatejobpost.php?job_id=<?= $job_id ?>" method="POST">
            <label for="title">Job Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($jobPost['title']) ?>" required>

            <label for="description">Job Description:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($jobPost['description']) ?></textarea>

            <input type="submit" value="Update Job Post">
        </form>
    </div>
</body>
</html>
