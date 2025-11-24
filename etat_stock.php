<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
checkAuth();
checkRole(['Admin']);
$db = (new Database())->connect();

$stmt = $db->prepare("
    SELECT 
        dn.groupe_sanguin,
        dn.rhesus,
        COUNT(*) AS total_dons,
        SUM(CASE WHEN d.statut = 'en stock' THEN 1 ELSE 0 END) AS stock,
        SUM(CASE WHEN d.statut = 'utilisé' THEN 1 ELSE 0 END) AS utilises,
        SUM(CASE WHEN d.statut = 'rejeté' THEN 1 ELSE 0 END) AS rejetés,
        SUM(CASE WHEN d.statut = 'valide' THEN 1 ELSE 0 END) AS validés,
        SUM(CASE WHEN d.statut = 'expiré' THEN 1 ELSE 0 END) AS expirés


    FROM dons d
    JOIN donneurs dn ON d.id_donneur = dn.id_donneur
    GROUP BY dn.groupe_sanguin, dn.rhesus
    ORDER BY dn.groupe_sanguin ASC, dn.rhesus ASC
");
$stmt->execute();
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques Groupes Sanguins & Rhésus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">

    <h1 class="mb-4">
        <i class="fas fa-tint text-danger me-2"></i>
        Statistiques par Groupe Sanguin et Rhésus
    </h1>

    <?php if (empty($stats)): ?>
        <div class="alert alert-info">
            Aucune donnée trouvée. Aucun don enregistré.
        </div>

    <?php else: ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Groupe</th>
                <th>Rhésus</th>
                <th>Total Dons</th>
                <th>En Stock</th>
                <th>Utilisés</th>
                <th>Rejetés</th>
                <th>Validés</th>
                <th>Expirés</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats as $s): ?>
            <tr>
                <td><strong><?= htmlspecialchars($s['groupe_sanguin']) ?></strong></td>
                <td><?= htmlspecialchars($s['rhesus']) ?></td>
                <td><?= $s['total_dons'] ?></td>
                <td><?= $s['stock'] ?></td>
                <td><?= $s['utilises'] ?></td>
                <td><?= $s['rejetés'] ?></td>
                <td><?= $s['validés'] ?></td>
                <td><?= $s['expirés'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'includes/footer.php'; ?>

</body>
</html>

