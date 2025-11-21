<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
checkAuth();

$db = (new Database())->connect();

// Récupérer tous les dons "en stock" groupés par groupe sanguin
$stmt = $db->prepare("
    SELECT dn.groupe_sanguin, COUNT(d.id_don) AS quantite
    FROM dons d
    JOIN donneurs dn ON d.id_donneur = dn.id_donneur
    WHERE d.statut = 'en stock'
    GROUP BY dn.groupe_sanguin
    ORDER BY dn.groupe_sanguin ASC
");
$stmt->execute();
$stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total stock disponible
$total_disponible = array_sum(array_column($stocks,'quantite'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>État du Stock de Sang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-stat { transition: 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4"><i class="fas fa-tint text-danger me-2"></i>État du Stock de Sang</h1>

    <div class="row mb-4">
        <?php foreach($stocks as $s): ?>
        <div class="col-md-3 mb-3">
            <div class="card card-stat bg-primary text-white text-center">
                <div class="card-body">
                    <h3><?= $s['quantite'] ?></h3>
                    <p><?= $s['groupe_sanguin'] ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="col-md-3 mb-3">
            <div class="card card-stat bg-info text-white text-center">
                <div class="card-body">
                    <h3><?= $total_disponible ?></h3>
                    <p>Total Stock</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Stocks par Groupe Sanguin</h5>
        </div>
        <div class="card-body">
            <?php if(empty($stocks)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>Aucun stock disponible</h5>
                    <p class="text-muted">Le stock de sang est vide.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Groupe Sanguin</th>
                                <th>Quantité Disponible</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($stocks as $s): ?>
                            <tr>
                                <td><?= $s['groupe_sanguin'] ?></td>
                                <td><?= $s['quantite'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
