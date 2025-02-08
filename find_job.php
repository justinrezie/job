<?php
session_start();
include 'config/db.php';
require 'partials/header.php';

// Fetch all jobs
$query = "SELECT * FROM jobs ORDER BY posted_at DESC";
$result = $conn->query($query);
?>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #90e9ff7c;
            color: #333;
        }

        .container-sm {
            max-width: 1700px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .job-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .job-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .job-card h3 {
            margin-top: 0;
            color: #0044cc;
            font-size: 1.5em;
        }

        .job-card h3 a {
            text-decoration: none;
            color: #0044cc;
        }

        .job-card h3 a:hover {
            text-decoration: underline;
        }

        .job-card p {
            margin: 8px 0;
            color: #555;
        }

        .view-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 18px;
            background-color: #0044cc;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .view-btn:hover {
            background-color: #0033a0;
        }

        .no-jobs {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            padding: 50px 0;
        }
    </style>

<div class="container-sm">
    <div class="job-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($job = $result->fetch_assoc()): ?>
                <div class="job-card">
                    <h3><a href="job_details.php?id=<?php echo $job['id']; ?>">
                        <?php echo htmlspecialchars($job['title']); ?>
                    </a></h3>
                    <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars(substr($job['description'], 0, 80))); ?>...</p>
                    <a href="job_details.php?id=<?php echo $job['id']; ?>" class="view-btn">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-jobs">No jobs available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
