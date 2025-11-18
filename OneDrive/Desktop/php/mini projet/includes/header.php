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
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-tint me-2"></i>Gestion Don de Sang
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <!-- Navigation principale -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <?php if(isset($_SESSION['role']) && in_array($_SESSION['role'], ['SECRETAIRE', 'ADMIN'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="donneurs/liste.php">
                        <i class="fas fa-users me-1"></i>Donneurs
                    </a>
                </li>
                <?php endif; ?>
                <?php if(isset($_SESSION['role']) && in_array($_SESSION['role'], ['SECRETAIRE', 'MEDECIN', 'ADMIN'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="dons/liste.php">
                        <i class="fas fa-tint me-1"></i>Dons
                    </a>
                </li>
                <?php endif; ?>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="centres/liste.php">
                        <i class="fas fa-hospital me-1"></i>Centres
                    </a>
                </li>
                <?php endif; ?>
                <?php if(isset($_SESSION['role']) && in_array($_SESSION['role'], ['MEDECIN', 'ADMIN'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="tests/liste.php">
                        <i class="fas fa-flask me-1"></i>Tests
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="liste.php">
                        <i class="fas fa-hand-holding-medical me-1"></i>Transfusions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="liste_besoins.php">
                        <i class="fas fa-exclamation-triangle me-1"></i>Besoins
                    </a>
                </li>
            </ul>

            <!-- Zone utilisateur -->
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
