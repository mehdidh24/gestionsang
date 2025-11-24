<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin','Médecin']);

$db = (new Database())->connect();



$stmt = $db->prepare("
    SELECT d.id_don, d.id_centre, d.statut, 
           dn.id_donneur, dn.cin, dn.groupe_sanguin, dn.rhesus
    FROM dons d
    LEFT JOIN donneurs dn ON d.id_donneur = dn.id_donneur
    WHERE d.statut = ?
    ORDER BY d.id_don DESC
");

$stmt->execute(['en stock']);
$dons = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tests des Dons</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Dons en Attente de Test</h2>

    

    <?php if (empty($dons)): ?>
        <div class="alert alert-info mt-3">Aucun don en attente de test.</div>
    <?php else: ?>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID Don</th>
                    <th>ID Donneur</th>
                    <th>CIN</th>
                    <th>Groupe + Rhesus</th>
                    <th>Centre</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($dons as $don): ?>
                <tr>
                    <td><?= $don['id_don'] ?></td>
                    <td><?= $don['id_donneur'] ?? '—' ?></td>
                    <td><?= htmlspecialchars($don['cin']) ?></td>
                    <td><?= htmlspecialchars($don['groupe_sanguin'] . $don['rhesus']) ?></td>
                    <td><?= htmlspecialchars($don['id_centre']) ?></td>
                    <td><?= htmlspecialchars($don['statut']) ?></td>
                    <td>
                        <a href="valider_test.php?id_don=<?= $don['id_don'] ?>&statut=VALIDE"
                           class="btn btn-success btn-sm"
                           onclick="return confirm('Valider ce don ?');">
                           Valider
                        </a>
                        <a href="valider_test.php?id_don=<?= $don['id_don'] ?>&statut=REJETÉ"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Rejeter ce don ?');">
                           Rejeter
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

