<?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
<body>
    <div class="container">
        <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
        <h3>Superglobales et Session</h3>
        
        <div class="card mb-3">
            <div class="card-header">
                <h5>$_SESSION</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <?php foreach ($_SESSION as $key => $value): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($key); ?></strong></td>
                                <td><?php echo htmlspecialchars(is_array($value) ? json_encode($value) : $value); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5>$_COOKIE</h5>
            </div>
            <div class="card-body">
                <?php if (count($_COOKIE) > 0): ?>
                    <table class="table table-sm">
                        <tbody>
                            <?php foreach ($_COOKIE as $key => $value): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($key); ?></strong></td>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Aucun cookie</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5>$_GET</h5>
            </div>
            <div class="card-body">
                <?php if (count($_GET) > 0): ?>
                    <table class="table table-sm">
                        <tbody>
                            <?php foreach ($_GET as $key => $value): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($key); ?></strong></td>
                                    <td><?php echo htmlspecialchars(is_array($value) ? json_encode($value) : $value); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Aucun paramètre GET</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5>$_SERVER (sélection)</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td><strong>HTTP_HOST</strong></td>
                            <td><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>REQUEST_METHOD</strong></td>
                            <td><?php echo htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>SCRIPT_NAME</strong></td>
                            <td><?php echo htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>REQUEST_URI</strong></td>
                            <td><?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>REMOTE_ADDR</strong></td>
                            <td><?php echo htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
</body>
</html>
