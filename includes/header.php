<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
?>
<!Doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
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
    .card-box {
        padding: 25px;
        border-radius: 12px;
        color: #fff;
    }
    .bg-blue { background:#006CFF; }
    .bg-green { background:#0A9151; }
    .bg-cyan { background:#03C5F9; }
    .big-number {
        font-size:40px;
        font-weight:bold;
    }
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        
        <a class="navbar-brand" href="/dashboard.php">
            <i class="fas fa-tint me-2"></i>Gestion Don de Sang
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <a class="nav-link" href="../dashboard.php">
                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            <li>
                <a class="nav-link" href="../utilisateurs.php">
                    <i class="fas fa-user-cog me-1"></i>Utilisateurs
                </a>
            </li>
           
    
            <li class="nav-item">
                <a href="../transfusions/liste.php" class="nav-link">
                    <i class="fas fa-hand-holding-medical me-1"></i>Transfusions
                </a>
            </li>

            <li class="nav-item">
                <a href="../besoins/liste_besoins.php" class="nav-link">
                    <i class="fas fa-exclamation-triangle me-1"></i>Besoins
                </a>
            </li>

            <li class="nav-item">
                <a href="../etat_stock.php" class="nav-link">
                    <i class="fas fa-boxes-stacked me-1"></i>État du Stock
                </a>
            </li>

            <li class="nav-item">
                <a href="../tests/test_dons.php" class="nav-link">
                    <i class="fas fa-vial me-1"></i>Tests des Dons
                </a>
            </li>

            <li class="nav-item">
                <a href="../donneurs/liste_donneurs.php" class="nav-link">
                    <i class="fas fa-file-alt me-1"></i>Donneurs
                </a>
            </li>

            <li class="nav-item">
                <a href="../dons/liste_dons.php" class="nav-link">
                    <i class="fas fa-tint me-1"></i>Dons
                </a>
            </li>

            <li class="nav-item">
                <a href="../centres/liste_centres.php" class="nav-link">
                    <i class="fas fa-hospital me-1"></i>Centres
                </a>
            </li>

            </ul>

            <span class="navbar-text text-white">
                <i class="fas fa-user me-1"></i>
                <?= htmlspecialchars($_SESSION['nom']) ?>
                <span class="badge bg-secondary ms-1"><?= $_SESSION['role'] ?? '' ?></span> |
                <a href="logout.php" class="text-warning">
                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                </a>
            </span>
        </div>
    </div>
</nav>

<div class="container mt-4"></div>
