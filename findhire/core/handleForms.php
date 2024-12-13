<?php

require_once 'core/dbConfig.php';
require_once 'core/models.php';


function registerUser($pdo, $username, $password) {
    // Check if the username already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Username already exists
        return false;
    }

    // Hash the password before saving it to the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $hashedPassword]);

    // Return the newly created user (or just return true if you prefer)
    return true;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'models.php';
    

    if (isset($_POST['addJobPost'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $hr_id = $_SESSION['user_id']; // Assuming HR is logged in
        addJobPost($pdo, $hr_id, $title, $description);
        header('Location: ../index.php');
        exit();
    }

    if (isset($_POST['deleteJobPost'])) {
        $job_id = $_POST['job_id'];
        deleteJobPost($pdo, $job_id);
        header('Location: ../index.php');
        exit();
    }

    if (isset($_POST['applyForJob'])) {
        $job_id = $_POST['job_id'];
        $applicant_id = $_SESSION['user_id']; // Assuming Applicant is logged in
        $resume_path = $_POST['resume_path']; // Handle file upload before this
        $description = $_POST['description'];
        applyForJob($pdo, $job_id, $applicant_id, $resume_path, $description);
        header('Location: ../index.php');
        exit();
    }

    if (isset($_POST['updateApplicationStatus'])) {
        $application_id = $_POST['application_id'];
        $status = $_POST['status'];
        updateApplicationStatus($pdo, $application_id, $status);
        header('Location: ../viewapplicants.php');
        exit();
    }

    if (isset($_POST['sendMessage'])) {
        $job_id = $_POST['job_id'];
        $sender_id = $_SESSION['user_id']; // Logged-in user
        $recipient_id = $_POST['recipient_id'];
        $message = $_POST['message'];
        sendMessage($pdo, $job_id, $sender_id, $recipient_id, $message);
        header('Location: ../index.php');
        exit();
    }
}


?>