<?php
session_start();
include 'config/db.php';
require 'partials/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$job_id = $_GET['id'];

// Fetch job details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "Job not found!";
    exit;
}

?>
<main class="job-details container">
    <h1><?php echo htmlspecialchars($job['title']); ?></h1>
    <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
    <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="apply_job.php?job_id=<?php echo $job_id; ?>" class="btn-apply">Apply for this Job</a>
    <?php else: ?>
        <p><a href="login.php">Log in to apply for this job</a></p>
    <?php endif; ?>
</main>


<?php require 'partials/footer.php'; ?>
