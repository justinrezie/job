<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT jobs.title, jobs.company, jobs.location, applied_jobs.applied_at
                        FROM applid_jobs
                        JOIN jobs ON applied_jobs.job_id = jobs.id 
                        WHERE applied_jobs.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Applied Jobs</title>
</head>
<body>
    <h1>Your Applied Jobs</h1>

    <?php while ($job = $result->fetch_assoc()): ?>
        <div>
            <h2><?php echo htmlspecialchars($job['title']); ?></h2>
            <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>Application Date:</strong> <?php echo $job['application_date']; ?></p>
        </div>
        <hr>
    <?php endwhile; ?>
</body>
</html>
