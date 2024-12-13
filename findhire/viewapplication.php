<?php
session_start();
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HR') {
    header('Location: login.php');
    exit();
}

$hr_id = $_SESSION['user_id'];

// Get all job posts created by the HR
$jobPosts = getJobPostsByHR($pdo, $hr_id);

// Handle application status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateApplicationStatus'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    updateApplicationStatus($pdo, $application_id, $status);
    header('Location: viewapplication.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Applications for Your Job Posts</h1>

        <?php if (empty($jobPosts)): ?>
            <p>You have not posted any jobs yet.</p>
        <?php else: ?>
            <?php foreach ($jobPosts as $job): ?>
                <div class="job-post">
                    <h3>Job Title: <?= htmlspecialchars($job['job_title']) ?></h3>
                    <p>Job Description: <?= htmlspecialchars($job['job_description']) ?></p>
                    <h4>Applicants:</h4>
                    <?php
                    // Get all applications for this job
                    $applications = getApplicationsByJob($pdo, $job['job_id']);
                    if (empty($applications)): ?>
                        <p>No applications yet.</p>
                    <?php else: ?>
                        <?php foreach ($applications as $application): ?>
                            <div class="application">
                                <p>Applicant: <?= htmlspecialchars($application['username']) ?></p>
                                <p>Status: <?= htmlspecialchars($application['status']) ?></p>
                                <form method="POST" action="viewapplication.php">
                                    <input type="hidden" name="application_id" value="<?= $application['application_id'] ?>">
                                    <input type="radio" name="status" value="Accepted" <?= $application['status'] === 'Accepted' ? 'checked' : '' ?>> Accept
                                    <input type="radio" name="status" value="Rejected" <?= $application['status'] === 'Rejected' ? 'checked' : '' ?>> Reject
                                    <input type="submit" name="updateApplicationStatus" value="Update Status">
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
