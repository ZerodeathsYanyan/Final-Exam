<?php
session_start();
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Ensure the user is HR
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'HR') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['job_id'])) {
    $job_id = (int)$_GET['job_id'];
    // Delete the job post
    deleteJobPost($pdo, $job_id);
    header('Location: index.php');
    exit();
} else {
    echo "Invalid job ID.";
    exit();
}
