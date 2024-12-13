CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('HR', 'Applicant') NOT NULL
);

CREATE TABLE job_posts (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(255) NOT NULL,
    job_description TEXT NOT NULL,
    posted_by INT,
    FOREIGN KEY (posted_by) REFERENCES users(user_id)
);

CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    applicant_id INT,
    resume LONGBLOB,
    status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (job_id) REFERENCES job_posts(job_id),
    FOREIGN KEY (applicant_id) REFERENCES users(user_id)
);

CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT,
    hr_id INT,
    message TEXT,
    FOREIGN KEY (applicant_id) REFERENCES users(user_id),
    FOREIGN KEY (hr_id) REFERENCES users(user_id)
);