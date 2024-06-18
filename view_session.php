<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET"){
    $query = "SELECT * FROM `sessions`";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }

    $sessioni = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sessioni[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Session</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Sessioni d'esame</h2>
    <?php if (count($sessioni) > 0): ?>
        <ul>
            <?php foreach ($sessioni as $sessione): ?>
                <form action="session_details.php" method="POST" >
                <input type="hidden" name="id" value="<?php echo $sessione['id']; ?>">
                <li><?php echo $sessione['id'] . ' - ' . $sessione['date'] . ' - ' . $sessione['time'] . ' - ' . $sessione['commission_members']; ?> <button type="submit">Vedi sessione</button></li>
                </form>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Non sono state trovate sessioni.</p>
    <?php endif; ?>
    <?php include('templates/footer.php'); ?>
</body>
</html>
