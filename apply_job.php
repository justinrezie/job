<?php
session_start();
include 'config/db.php';
require 'partials/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['job_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = $_GET['job_id'];
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['pdf', 'doc', 'docx'];
        $upload_dir = 'uploads/cvs/';
        $cv_file = $_FILES['cv'];
        
        // Get file extension
        $file_ext = pathinfo($cv_file['name'], PATHINFO_EXTENSION);
        
        // Validate file type
        if (!in_array(strtolower($file_ext), $allowed_extensions)) {
            $error = "Only PDF, DOC, or DOCX files are allowed.";
        } else {
            // Ensure upload directory exists
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Unique file name
            $new_file_name = uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $new_file_name;

            // Move uploaded file
            if (move_uploaded_file($cv_file['tmp_name'], $file_path)) {
                // Insert application into database
                $stmt = $conn->prepare("INSERT INTO applications (user_id, job_id, cv_path, application_date) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("iis", $user_id, $job_id, $file_path);

                if ($stmt->execute()) {
                    echo "<p>Application successful! Your CV has been submitted.</p>";
                    header("Location: view_applications.php");
                    exit;

                } else {
                    $error = "Error submitting your application. Please try again.";
                }
            } else {
                $error = "Failed to upload the file. Please check the file and try again.";
            }
        }
    } else {
        // Handle common upload errors
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
            UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form.",
            UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_FILE => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
        ];

        $error = $upload_errors[$_FILES['cv']['error']] ?? "An unknown error occurred during file upload.";
    }

    // Show error message if there's any
    if (!empty($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
}
?>


    <main class="apply-job-page">
        <h2>
            Apply for Job
        </h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="cv">Upload your CV (PDF, DOC, DOCX):</label>
            <input type="file" name="cv" id="cv" required>
            <button type="submit" class="btn">Submit Application</button>
        </form>
        <a href="index.php" class="btn-back">Back to Jobs</a>
    </main>