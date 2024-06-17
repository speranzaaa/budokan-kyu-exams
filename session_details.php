<?php

if($_SERVER['REQUEST_METHOD']=="POST"){
    echo $_POST['id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dettagli esame</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <section>
        <h1>Dettagli esame</h1>
        <?php echo $id; ?>
    </section>
    <?php include('templates/footer.php'); ?>
</body>
</html>