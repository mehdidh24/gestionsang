<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

// Vérifier l'authentification
checkAuth();

$db = (new Database())->connect();

$donneurs = [];
// Récupérer les statistiques
$stmt = $db->prepare("SELECT COUNT(*) FROM donneurs");
$stmt->execute();
$donneurs = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM dons WHERE statut = ?");
$stmt->execute(['valide']);
$dons = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM centres_collecte");
$stmt->execute();
$centres = $stmt->fetchColumn();
// Alertes de stock
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
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <h2>Tableau de Bord <small class="text-muted">Bienvenue, <?= htmlspecialchars($_SESSION['nom']) ?></small></h2>

<div class="row mt-4">
  <div class="col-md-4 mb-3">
    <a href="liste_donneurs.php" class="card-link">
      <div class="card-box bg-blue">
        <div class="big-number"><?= $donneurs ?></div>
        <div>Donneurs</div>
      </div>
    </a>
  </div>
  
  <div class="col-md-4 mb-3">
    <a href="liste_dons.php" class="card-link">
      <div class="card-box bg-green">
        <div class="big-number"><?= $dons ?></div>
        <div>Dons valides</div>
      </div>
    </a>
  </div>
  
  <div class="col-md-4 mb-3">
    <a href="liste_centres.php" class="card-link">
      <div class="card-box bg-cyan">
        <div class="big-number"><?= $centres ?></div>
        <div>Centres</div>
      </div>
    </a>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header">Alertes</div>
  <div class="card-body">
    <?php if (!empty($alertes)): ?>
      <?php foreach ($alertes as $a): ?>
        <div class="alert alert-warning">
          <strong><?= htmlspecialchars($a['groupe_sanguin']) ?></strong> - 
          <?= htmlspecialchars($a['niveau_alerte']) ?>
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
