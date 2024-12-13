<?php
// index.php: Dashboard for HR and Applicants
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Redirect users based on their roles


$search = $_GET['search'] ?? '';
$jobPosts = getJobPosts($pdo, $search);
?>
<!DOCTYPE html>
<html>
<head>
    <title>FindHire</title>
</head>
<body>
<h1>Welcome to FindHire</h1>
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search jobs..." value="<?= htmlspecialchars($search) ?>">
    <input type="submit" value="Search">
</form>

<?php if ($role === 'HR'): ?>
    <a href="addjobpost.php">Add Job Post</a>
    <h2>Your Job Posts</h2>
    <?php foreach ($jobPosts as $job): ?>
        <div>
            <h3><?= htmlspecialchars($job['title']) ?></h3>
            <p><?= htmlspecialchars($job['description']) ?></p>
            <a href="updatejobpost.php?job_id=<?= $job['job_id'] ?>">Update</a>
            <form method="POST" action="core/handleForms.php">
                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                <input type="submit" name="deleteJobPost" value="Delete">
            </form>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <h2>Available Job Posts</h2>
    <?php foreach ($jobPosts as $job): ?>
        <div>
            <h3><?= htmlspecialchars($job['title']) ?></h3>
            <p><?= htmlspecialchars($job['description']) ?></p>
            <form method="POST" action="core/handleForms.php">
                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                <input type="submit" name="applyForJob" value="Apply">
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
