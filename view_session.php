<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM sessions";
$sessions = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Sessions</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>View Sessions</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Commission Members</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($session = mysqli_fetch_assoc($sessions)): ?>
                <tr>
                    <td><?php echo $session['id']; ?></td>
                    <td><?php echo $session['date']; ?></td>
                    <td><?php echo $session['time']; ?></td>
                    <td><?php echo $session['commission_members']; ?></td>
                    <td>
                        <a href="add_remove_students.php?session_id=<?php echo $session['id']; ?>">Manage Students</a>
                        <a href="update_scores.php?session_id=<?php echo $session['id']; ?>">Update Scores</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php include('templates/footer.php'); ?>
</body>
</html>
