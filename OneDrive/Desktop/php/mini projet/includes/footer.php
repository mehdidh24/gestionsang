<footer class="bg-dark text-light text-center py-3 fixed-bottom">
    <div class="container">
        <small>
            <i class="fas fa-tint me-1"></i>
            Gestion Sang &copy; <?php echo date('Y'); ?> 
            - 
            <i class="fas fa-user me-1 ms-2"></i>
            <?php echo htmlspecialchars($_SESSION['nom']); ?>
        </small>
    </div>
</footer>