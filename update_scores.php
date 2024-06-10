<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$session_id = $_GET['session_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $kihon_score = $_POST['kihon_score'];
    $kata_score = $_POST['kata_score'];
    $kumite_score = $_POST['kumite_score'];
    $query = "UPDATE students SET kihon_score='$kihon_score', kata_score='$kata_score', kumite_score='$kumite_score' WHERE id='$id'";
    mysqli_query($conn, $query);
}

$students_query = "SELECT * FROM students WHERE session_id='$session_id'";
$students = mysqli_query($conn, $students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Scores</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Update Scores</h2>
    <form method="post">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Kihon Score</th>
                    <th>Kata Score</th>
                    <th>Kumite Score</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = mysqli_fetch_assoc($students)): ?>
                    <tr>
                        <td><?php echo $student['name']; ?></td>
                        <td><input type="number" name="kihon_score" value="<?php echo $student['kihon_score']; ?>" required></td>
                        <td><input type="number" name="kata_score" value="<?php echo $student['kata_score']; ?>" required></td>
                        <td><input type="number" name="kumite_score" value="<?php echo $student['kumite_score']; ?>" required></td>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                            <button type="submit">Update</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
    <?php include('templates/footer.php'); ?>
</body>
</html>
