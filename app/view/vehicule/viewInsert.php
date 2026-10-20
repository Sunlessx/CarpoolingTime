<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Ajouter un véhicule</h3>
        <form method="get" action="router.php">
            <input type="hidden" name="action" value="vehiculeCreated">
            <div class="mb-3">
                <label for="marque" class="form-label">Marque:</label>
                <input type="text" class="form-control" id="marque" name="marque" required>
            </div>
            <div class="mb-3">
                <label for="modele" class="form-label">Modèle:</label>
                <input type="text" class="form-control" id="modele" name="modele" required>
            </div>
            <div class="mb-3">
                <label for="annee" class="form-label">Année:</label>
                <input type="number" class="form-control" id="annee" name="annee" required>
            </div>
            <div class="mb-3">
                <label for="immatriculation" class="form-label">Immatriculation:</label>
                <input type="text" class="form-control" id="immatriculation" name="immatriculation" required>
            </div>
            <div class="mb-3">
                <label for="proprietaire_id" class="form-label">Propriétaire:</label>
                <select class="form-control" id="proprietaire_id" name="proprietaire_id" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($conducteurs as $c): ?>
                        <option value="<?php echo $c->id; ?>"><?php echo htmlspecialchars($c->nom . ' ' . $c->prenom); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
