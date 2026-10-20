<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Créer un trajet</h3>
        <form method="get" action="router.php">
            <input type="hidden" name="action" value="trajetCreated">
            <div class="mb-3">
                <label for="ville_depart" class="form-label">Ville de départ:</label>
                <select class="form-control" id="ville_depart" name="ville_depart" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($villes as $v): ?>
                        <option value="<?php echo $v->id; ?>"><?php echo htmlspecialchars($v->nom); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="ville_arrivee" class="form-label">Ville d'arrivée:</label>
                <select class="form-control" id="ville_arrivee" name="ville_arrivee" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($villes as $v): ?>
                        <option value="<?php echo $v->id; ?>"><?php echo htmlspecialchars($v->nom); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="vehicule_id" class="form-label">Véhicule:</label>
                <select class="form-control" id="vehicule_id" name="vehicule_id" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($vehicules as $v): ?>
                        <option value="<?php echo $v->id; ?>"><?php echo htmlspecialchars($v->marque . ' ' . $v->modele); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix:</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
            </div>
            <div class="mb-3">
                <label for="date_depart" class="form-label">Date de départ:</label>
                <input type="date" class="form-control" id="date_depart" name="date_depart" required>
            </div>
            <div class="mb-3">
                <label for="heure_depart" class="form-label">Heure de départ:</label>
                <input type="time" class="form-control" id="heure_depart" name="heure_depart" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
