<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$jobs = $conn->query("SELECT * FROM jobs ORDER BY posted_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Jobs</title>
</head>
<body>
    <h1>Available Jobs</h1>

    <?php while ($job = $jobs->fetch_assoc()): ?>
        <div>
            <h2><?php echo htmlspecialchars($job['title']); ?></h2>
            <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            <a href="apply_job.php?id=<?php echo $job['id']; ?>">Apply</a>
        </div>
        <hr>
    <?php endwhile; ?>
</body>
</html>
