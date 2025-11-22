<?php if($sidebar_visible): ?>
<nav class="bg-black text-white position-fixed vh-100 p-3" style="width:250px;">
    <h4 class="text-center mb-4"><i class="fas fa-tint me-2"></i>Dons Sang</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2"><a class="nav-link text-white" href="/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-white" href="/donneurs/liste_donneurs.php"><i class="fas fa-file-alt me-2"></i>Donneurs</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-white" href="/dons/liste_dons.php"><i class="fas fa-tint me-2"></i>Dons</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-white" href="/transfusions/liste_transfusions.php"><i class="fas fa-hand-holding-medical me-2"></i>Transfusions</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-white" href="/tests/test_dons.php"><i class="fas fa-vial me-2"></i>Tests</a></li>
        <li class="nav-item mt-4"><a class="nav-link text-warning" href="/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
    </ul>
</nav>
<?php endif; ?>

