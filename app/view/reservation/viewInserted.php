<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Réservation effectuée avec succès</h3>
        <p>ID de réservation: <?php echo htmlspecialchars($id); ?></p>
        <a href="router.php?action=reservationMesReservations" class="btn btn-primary">Voir mes réservations</a>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
