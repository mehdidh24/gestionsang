<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Médecin' ,'Admin']); // Médecin seulement

$db = (new Database())->connect();

// Récupérer uniquement les dons EN STOCK depuis liste_dons
$dons = $db->query("
    SELECT id_don,id_centre,id_donneur
    FROM dons d
    WHERE d.statut!='Valide' AND d.statut!='Rejeté'
    ORDER BY d.id_don DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Message de succès / erreur
$message = "";
if (!empty($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Validation des Tests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Dons en Attente de Validation</h2>

    <?php if ($message): ?>
        <div class="alert alert-success mt-3"><?= $message ?></div>
    <?php endif; ?>

    <?php if (empty($dons)): ?>
        <div class="alert alert-info mt-3">Aucun don en attente de validation.</div>
    <?php else: ?>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID Don</th>
                    <th>Donneur</th>
                    <th>Centre</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($dons as $don): ?>
                <tr>
                    <td><?= $don['id_don'] ?></td>
                    <td><?= htmlspecialchars($don['id_donneur']) ?></td>
                    <td><?= htmlspecialchars($don['id_centre']) ?></td>
                    <td>
                        <!-- Bouton pour valider ou rejeter ce don -->
                        <a href="valider_test.php?id_don=<?= $don['id_don'] ?>" 
                           class="btn btn-primary btn-sm">
                            Valider 
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

