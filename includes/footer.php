<footer class="footer mt-auto py-3 bg-dark text-white">
    <div class="container">
        <small>
            <i class="fas fa-tint me-1"></i>
            Gestion Sang &copy; <?php echo date('Y'); ?>
            - 
            <?php echo htmlspecialchars($_SESSION['nom']); ?>
        </small>
    </div>
</footer>
<style>
html, body {
    height: 100%;
    margin: 0;
}

body {
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}

.footer {
    background-color: #212529;
    color: white;
    text-align: center;
    padding: 15px 0;
    margin-top: auto; 
}
</style>