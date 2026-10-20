<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Trajet clôturé avec succès</h3>
        <p>Le trajet a été marqué comme passif et les paiements ont été traités.</p>
        <p>Nombre de passagers: <?php echo count($reservations); ?></p>
        <p>Total encaissé: <?php echo number_format(count($reservations) * $trajet->prix, 2); ?>€</p>
        <a href="router.php?action=trajetMesTrajets" class="btn btn-primary">Retour à mes trajets</a>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
