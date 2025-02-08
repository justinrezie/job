<?php
session_start();
include 'config/db.php';
require 'partials/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the current logged-in user's ID
$user_id = $_SESSION['user_id'];

// Prepare the SQL query to fetch the user's job applications
$query = "
    SELECT a.id, a.job_id, a.cv_path, a.application_date, j.title AS job_title
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.user_id = ?
    ORDER BY a.application_date DESC
";

// Prepare the query
if (!$stmt = $conn->prepare($query)) {
    echo "Prepare statement failed: " . $conn->error;
    exit;
}

// Bind the user ID parameter to the prepared statement
$stmt->bind_param("i", $user_id);

// Execute the query
$stmt->execute();

// Check for execution errors
if ($stmt->error) {
    echo "Error executing query: " . $stmt->error;
    exit;
}

// Get the result set from the executed query
$result = $stmt->get_result();
?>


<style>
/* View Applications Page Styles */
.view-applications-page {
    max-width: 900px;
    margin: 0 auto;
    margin-top: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.view-applications-page h2 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.view-applications-page table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.view-applications-page th,
.view-applications-page td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.view-applications-page th {
    background-color: #f0f0f0;
    font-weight: bold;
}

.view-applications-page td a {
    color: #007bff;
    text-decoration: none;
}

.view-applications-page td a:hover {
    text-decoration: underline;
}

.view-applications-page .no-applications {
    font-size: 18px;
    color: #555;
    text-align: center;
    margin-top: 20px;
}

.view-applications-page .btn {
    display: inline-block;
    padding: 12px 24px;
    font-size: 18px;
    background-color: #007bff;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    transition: background-color 0.3s;
}

.view-applications-page .btn:hover {
    background-color: #0056b3;
}


</style>

<main class="view-applications-page">

<h2>Your Job Applications</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Job Title</th>
                <th>CV</th>
                <th>Applied On</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($application = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($application['cv_path']); ?>" target="_blank">Download CV</a></td>
                    <td><?php echo htmlspecialchars($application['application_date']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>You have not applied for any jobs yet.</p>
<?php endif; ?>

<a href="index.php" class="btn">Back to Dashboard</a>
    <!-- The rest of the View Applications HTML content here -->
    </main>
