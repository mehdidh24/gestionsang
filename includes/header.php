<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Déterminer si la sidebar est visible ou non
$sidebar_visible = !isset($_GET['sidebar']) || $_GET['sidebar'] !== 'hide';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gestion Don Sang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    body {
        background: #f4f5f7;
    }
    .navbar-brand {
        font-weight: bold;
        font-size: 19px;
    }
    .sidebar {
        width: 220px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        background-color: rgb(33, 37, 41);
        color: #fff;
        padding: 20px 0;
    }
    .sidebar a {
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        display: block;
    }
    .sidebar a:hover {
        background-color: #495057;
    }
    .main-content {
        margin-left: <?= $sidebar_visible ? '220px' : '0' ?>;
        padding: 20px;
    }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-text text-white ms-auto">
            <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['nom']) ?>
            <span class="badge bg-secondary ms-1"><?= $_SESSION['role'] ?? '' ?></span> |
            <a href="/logout.php" class="text-warning"><i class="fas fa-sign-out-alt me-1"></i>Déconnexion</a>
        </span>
    </div>
</nav>

<?php if($sidebar_visible): ?>
<div class="sidebar">
    <h4 class="text-center mb-4"><i class="fas fa-tint me-2"></i>Dons Sang</h4>
    <a href="/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
    <a href="/utilisateurs.php"><i class="fas fa-user-cog me-2"></i>Utilisateurs</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Admin', 'Sécrétaire'])): ?> 
    <a href="/donneurs/liste_donneurs.php"><i class="fas fa-file-alt me-2"></i>Donneurs</a>
    <a href="/dons/liste_dons.php"><i class="fas fa-tint me-2"></i>Dons</a>
    <?php endif; ?>
    <a href="/transfusions/liste.php"><i class="fas fa-hand-holding-medical me-2"></i>Transfusions</a>
    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Admin', 'Médecin'])): ?>
    <a href="/tests/test_dons.php"><i class="fas fa-vial me-2"></i>Tests</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Admin'])): ?>
    <a href="/besoins/liste_besoins.php"><i class="fas fa-exclamation-triangle me-2"></i>Besoins</a>
    <a href="/etat_stock.php"><i class="fas fa-boxes-stacked me-2"></i>État du Stock</a>
    <a href="/centres/liste_centres.php"><i class="fas fa-hospital me-2"></i>Centres</a>

    <?php endif; ?>
</div>
<?php endif; ?>





