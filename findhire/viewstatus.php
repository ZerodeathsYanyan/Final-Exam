<?php
session_start();
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$applicant_id = $_SESSION['user_id'];

// Get applications for this applicant
$applications = getApplicationsByApplicant($pdo, $applicant_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Your Application Status</h1>

        <?php if (empty($applications)): ?>
            <p>You have not applied to any jobs yet.</p>
        <?php else: ?>
            <?php foreach ($applications as $application): ?>
                <div class="application">
                    <h3>Job Title: <?= htmlspecialchars($application['title']) ?></h3>
                    <p>Job Description: <?= htmlspecialchars($application['description']) ?></p>
                    <p>Status: <?= htmlspecialchars($application['status']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
