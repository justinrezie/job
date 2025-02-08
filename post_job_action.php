<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

$title = $_POST['title'];
$description = $_POST['description'];
$location = $_POST['location'];
$company = $_POST['company'];

$query = "INSERT INTO jobs (title, description, location, company, posted_at) 
        VALUES ('$title', '$description', '$location', '$company', NOW())";
if ($conn->query($query)) {
    echo "Job posted successfully!";
} else {
    echo "Error posting job: " . $conn->error;
}
?>
