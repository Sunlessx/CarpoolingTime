<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Trajet créé avec succès</h3>
        <p>ID: <?php echo htmlspecialchars($id); ?></p>
        <a href="router.php?action=trajetMesTrajets" class="btn btn-primary">Retour à mes trajets</a>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
