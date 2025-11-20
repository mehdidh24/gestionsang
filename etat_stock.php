<?php
require_once 'config/database.php';
$db = (new Database())->connect();

$query = "
SELECT 
    dn.groupe_sanguin,
    SUM(d.quantité) - IFNULL(SUM(t.quantité), 0) AS quantite_disponible
FROM dons d
JOIN donneurs dn ON dn.id_donneur = d.id_donneur
LEFT JOIN transfusions t ON t.id_don = d.id_don
WHERE d.statut='en stock'
GROUP BY dn.groupe_sanguin
ORDER BY dn.groupe_sanguin
";


$stocks = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>État du Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>État du Stock par Groupe Sanguin</h2>
    <table class="table table-bordered table-striped mt-2">
        <thead class="table-dark">
            <tr>
                <th>Groupe Sanguin</th>
                <th>Quantité Disponible (ml)</th>
                <th>Besoins (ml)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($stocks as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['groupe_sanguin']) ?></td>
                <td><?= $s['quantite_disponible'] ?></td>
                <td><?= $s['quantité_besoin'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
