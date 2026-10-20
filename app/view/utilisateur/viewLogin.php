<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Connexion</h3>
        <form method="get" action="router.php">
            <input type="hidden" name="action" value="logined">
            <div class="mb-3">
                <label for="login" class="form-label">Login:</label>
                <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Connexion</button>
        </form>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
