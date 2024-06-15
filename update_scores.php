<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$atleti_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $session_id = $_POST['session_id'];
    $atleti_ids = $_POST['atleti_ids'];

    $query = "SELECT * FROM atleti WHERE Id IN (" . implode(',', array_map('intval', $atleti_ids)) . ")";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $atleti_data[] = $row;
    }

    if (isset($_POST['id']) && isset($_POST['exam_status'])) {
        foreach ($_POST['id'] as $index => $id) {
            $status = $_POST['exam_status'][$index];

            if ($status !== 'Absent') {
                $kihon_score = $_POST['kihon_score'][$index];
                $kata_score = $_POST['kata_score'][$index];
                $kumite_score = $_POST['kumite_score'][$index];

                $average_score = ($kihon_score + $kata_score + $kumite_score) / 3;

                $query = "UPDATE atleti SET Kihon_Score = '$kihon_score', Kata_Score = '$kata_score', Kumite_Score = '$kumite_score', Average_Score = '$average_score', Exam_Status = '$status' WHERE Id = '$id'";
                mysqli_query($conn, $query);
            } else {
                $query = "UPDATE atleti SET Exam_Status = '$status' WHERE Id = '$id'";
                mysqli_query($conn, $query);
            }
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
    <style>
        .highlighted {
            background-color: yellow;
        }

        .absent {
            background-color: #d3d3d3;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const idCell = row.querySelector('td:first-child');
                const statusSelect = row.querySelector('select[name="exam_status[]"]');
                const kihonInput = row.querySelector('input[name="kihon_score[]"]');
                const kataInput = row.querySelector('input[name="kata_score[]"]');
                const kumiteInput = row.querySelector('input[name="kumite_score[]"]');

                idCell.addEventListener('click', () => {
                    row.classList.toggle('highlighted');
                });

                statusSelect.addEventListener('change', (event) => {
                    const isAbsent = event.target.value === 'Absent';
                    row.classList.toggle('absent', isAbsent);
                    kihonInput.disabled = isAbsent;
                    kataInput.disabled = isAbsent;
                    kumiteInput.disabled = isAbsent;
                });

                if (statusSelect.value === 'Absent') {
                    kihonInput.disabled = true;
                    kataInput.disabled = true;
                    kumiteInput.disabled = true;
                }
            });
        });

        function calculateAverage() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const kihonInput = row.querySelector('input[name="kihon_score[]"]');
                const kataInput = row.querySelector('input[name="kata_score[]"]');
                const kumiteInput = row.querySelector('input[name="kumite_score[]"]');

                const kihon = parseFloat(kihonInput.value) || 0;
                const kata = parseFloat(kataInput.value) || 0;
                const kumite = parseFloat(kumiteInput.value) || 0;

                let sum = 0;
                let count = 0;

                if (kihonInput.value) {
                    sum += kihon;
                    count++;
                }
                if (kataInput.value) {
                    sum += kata;
                    count++;
                }
                if (kumiteInput.value) {
                    sum += kumite;
                    count++;
                }

                const average = count > 0 ? sum / count : 0;
                row.querySelector('.average_score').innerText = average.toFixed(2);

                const statusElement = row.querySelector('select[name="exam_status[]"]');
                if (statusElement.value !== 'Absent') {
                    statusElement.value = average >= 6 ? 'Passed' : 'Failed';
                }
            });
        }
    </script>
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Update Scores</h2>
    <?php if (!empty($atleti_data)): ?>
        <form method="post" oninput="calculateAverage()">
            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
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
                            <td><input type="number" step="0.5" name="kihon_score[]" min="0" max="10"></td>
                            <td><input type="number" step="0.5" name="kata_score[]" min="0" max="10"></td>
                            <td><input type="number" step="0.5" name="kumite_score[]" min="0" max="10"></td>
                            <td class="average_score"></td>
                            <td>
                                <select name="exam_status[]" required>
                                    <option value="Passed">Promosso</option>
                                    <option value="Failed">Bocciato</option>
                                    <option value="Absent">Assente</option>
                                </select>
                            </td>
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
