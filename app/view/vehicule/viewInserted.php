<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Véhicule créé avec succès</h3>
        <p>ID: <?php echo htmlspecialchars($id); ?></p>
        <a href="router.php?action=vehiculeReadAll" class="btn btn-primary">Retour à la liste</a>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
