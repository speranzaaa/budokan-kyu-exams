<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $session_id = $_POST['session_id'];
    $atleti_ids = $_POST['atleti_ids'];
    $atleti_data = [];

    // Recupera i dati degli atleti
    $query = "SELECT * FROM atleti WHERE Id IN (" . implode(',', array_map('intval', $atleti_ids)) . ")";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $atleti_data[] = $row;
    }

    if (isset($_POST['id']) && isset($_POST['kihon_score']) && isset($_POST['kata_score']) && isset($_POST['kumite_score'])) {
        foreach ($_POST['id'] as $index => $id) {
            $kihon_score = $_POST['kihon_score'][$index];
            $kata_score = $_POST['kata_score'][$index];
            $kumite_score = $_POST['kumite_score'][$index];

            $query = "UPDATE atleti SET Kihon_Score = '$kihon_score', Kata_Score = '$kata_score', Kumite_Score = '$kumite_score' WHERE Id = '$id'";
            mysqli_query($conn, $query);

            // Calcola la media e aggiorna lo stato dell'esame
            $average_score = ($kihon_score + $kata_score + $kumite_score) / 3;
            $status = ($average_score >= 6) ? 'Passed' : 'Failed';
            $query = "UPDATE atleti SET Average_Score = '$average_score', Exam_Status = '$status' WHERE Id = '$id'";
            mysqli_query($conn, $query);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Scores</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Update Scores</h2>
    <?php if (!empty($atleti_data)): ?>
        <form method="post">
            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Grado</th> <!-- si può togliere -->
                        <th>Presenze</th> <!-- si può togliere -->
                        <th>Voto Kihon</th>
                        <th>Voto Kata</th>
                        <th>Voto Kumite</th>
                        <th>Media voti</th>
                        <th>Esito Esame</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atleti_data as $atleta): ?>
                        <tr>
                            <td><?php echo $atleta['Id']; ?></td>
                            <td><?php echo $atleta['Nome']; ?></td>
                            <td><?php echo $atleta['Cognome']; ?></td>
                            <td><?php echo $atleta['Grado']; ?></td> <!-- si può togliere -->
                            <td><?php echo $atleta['Presenze']; ?></td> <!-- si può togliere -->
                            <td><input type="number" name="kihon_score[]" min="0" max="10" required></td>
                            <td><input type="number" name="kata_score[]" min="0" max="10" required></td>
                            <td><input type="number" name="kumite_score[]" min="0" max="10" required></td>
                            <td></td> <!-- Calcolo dinamico?? vediamo -->
                            <td></td> <!-- non so; radiobox? come si chiama  -->
                            <input type="hidden" name="id[]" value="<?php echo $atleta['Id']; ?>">
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Update Scores</button>
        </form>
    <?php else: ?>
        <p>No athletes found.</p>
    <?php endif; ?>
    <?php include('templates/footer.php'); ?>
</body>
</html>


