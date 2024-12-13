<?php

require_once 'core/dbConfig.php';


function loginUser($pdo, $username, $password) {
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false; // Return false if login fails
}



function getJobPosts($pdo, $search = '') {
    $query = "SELECT * FROM job_posts";
    if (!empty($search)) {
        $query .= " WHERE title LIKE :search OR description LIKE :search";
    }
    $query .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to add a job post
function addJobPost($pdo, $hr_id, $title, $description) {
    $stmt = $pdo->prepare("INSERT INTO job_posts (hr_id, title, description) VALUES (:hr_id, :title, :description)");
    $stmt->execute(['hr_id' => $hr_id, 'title' => $title, 'description' => $description]);
}

// Function to delete a job post
function deleteJobPost($pdo, $job_id) {
    $stmt = $pdo->prepare("DELETE FROM job_posts WHERE job_id = :job_id");
    $stmt->execute(['job_id' => $job_id]);
}

// Function to apply for a job
function applyForJob($pdo, $job_id, $applicant_id, $resume_path, $description) {
    $stmt = $pdo->prepare("INSERT INTO applications (job_id, applicant_id, resume_path, description) VALUES (:job_id, :applicant_id, :resume_path, :description)");
    $stmt->execute(['job_id' => $job_id, 'applicant_id' => $applicant_id, 'resume_path' => $resume_path, 'description' => $description]);
}

// Function to update application status
function updateApplicationStatus($pdo, $application_id, $status) {
    $stmt = $pdo->prepare("UPDATE applications SET status = :status WHERE application_id = :application_id");
    $stmt->execute(['status' => $status, 'application_id' => $application_id]);
}

// Function to send a message
function sendMessage($pdo, $job_id, $sender_id, $recipient_id, $message) {
    $stmt = $pdo->prepare("INSERT INTO messages (job_id, sender_id, recipient_id, message) VALUES (:job_id, :sender_id, :recipient_id, :message)");
    $stmt->execute(['job_id' => $job_id, 'sender_id' => $sender_id, 'recipient_id' => $recipient_id, 'message' => $message]);
}
// Get applications by applicant
function getApplicationsByApplicant($pdo, $applicant_id) {
    $stmt = $pdo->prepare("SELECT a.*, j.job_title as title, j.job_description as description FROM applications a 
                            JOIN job_posts j ON a.job_id = j.job_id
                            WHERE a.applicant_id = :applicant_id");
    $stmt->execute(['applicant_id' => $applicant_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get applications by job
function getApplicationsByJob($pdo, $job_id) {
    $stmt = $pdo->prepare("SELECT a.*, u.username FROM applications a 
                            JOIN users u ON a.applicant_id = u.user_id
                            WHERE a.job_id = :job_id");
    $stmt->execute(['job_id' => $job_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get job posts by HR
function getJobPostsByHR($pdo, $hr_id) {
    $stmt = $pdo->prepare("SELECT * FROM job_posts WHERE posted_by = :hr_id");
    $stmt->execute(['hr_id' => $hr_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>