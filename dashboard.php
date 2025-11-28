<?php
require_once 'includes/auth.php';
require_once 'config/database.php';



$database = new Database();
$db = $database->connect();


$stmt = $db->prepare("SELECT COUNT(*) FROM donneurs");
$stmt->execute();
$donneurs = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM dons WHERE statut = 'valide'");
$stmt->execute();
$dons = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM centres_collecte");
$stmt->execute();
$centres = $stmt->fetchColumn();


$query_alertes = "SELECT groupe_sanguin, niveau_alerte FROM besoins WHERE niveau_alerte IN ('URGENT', 'CRITIQUE')";
$stmt = $db->prepare($query_alertes);
$stmt->execute();
$alertes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - Gestion Sang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .card-stat {
            color: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-5px);
        }
        .card-stat h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .bg-blue { background-color: #006CFF; }
        .bg-green { background-color: #0A9151; }
        .bg-cyan { background-color: #03C5F9; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h1>Tableau de Bord <small class="text-muted">Bienvenue, <?= htmlspecialchars($_SESSION['nom']) ?></small></h1>

    
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card-stat bg-blue">
                <h3><?= $donneurs ?></h3>
                <p>Donneurs</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card-stat bg-green">
                <h3><?= $dons ?></h3>
                <p>Dons valides</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card-stat bg-cyan">
                <h3><?= $centres ?></h3>
                <p>Centres</p>
            </div>
        </div>
    </div>

   
    <div class="card mt-4">
        <div class="card-header"><h3>Alertes</h3></div>
        <div class="card-body">
            <?php if (!empty($alertes)): ?>
                <?php foreach ($alertes as $a): ?>
                    <div class="alert alert-warning">
                        <strong><?= htmlspecialchars($a['groupe_sanguin']) ?></strong> - <?= htmlspecialchars($a['niveau_alerte']) ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-muted">Aucune alerte.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
