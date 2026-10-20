<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Clôturer un trajet</h3>
        <form method="get" action="router.php">
            <input type="hidden" name="action" value="trajetCloture">
            <div class="mb-3">
                <label for="trajet_id" class="form-label">Trajet actif:</label>
                <select class="form-control" id="trajet_id" name="trajet_id" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($trajets as $t): 
                        if ($t->statut === 'actif') {
                            $ville_dep = \ModelVille::getOne($t->ville_depart);
                            $ville_arr = \ModelVille::getOne($t->ville_arrivee);
                            $dep = $ville_dep ? htmlspecialchars($ville_dep->nom) : 'N/A';
                            $arr = $ville_arr ? htmlspecialchars($ville_arr->nom) : 'N/A';
                    ?>
                        <option value="<?php echo $t->id; ?>"><?php echo $dep . ' -> ' . $arr . ' (' . $t->date_depart . ')'; ?></option>
                    <?php 
                        }
                    endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Clôturer</button>
        </form>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
