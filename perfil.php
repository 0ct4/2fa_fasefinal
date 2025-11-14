<?php
session_start();
require_once 'clases/Google2FA.php';
require_once 'config/database.php';

// Verificar que el usuario esté autenticado y que la sesión haya pasado 2FA.
if (!isset($_SESSION['usuario_id']) || !$_SESSION['autenticado_2fa']) {
    header("Location: login.php");
    exit;
}

// Inicializar recursos y comprobar si el usuario tiene 2FA configurado.
$database = new Database();
$db = $database->getConnection();
$google2fa = new Google2FA($db);

// Recuperar secreto desde la BD para determinar si 2FA está activado.
$secret = $google2fa->obtenerSecret2FA($_SESSION['usuario_id']);
$tiene_2fa = !empty($secret);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mi Perfil</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
    </head>
<body>
    <div class="container">
        <header class="header">
            <div class="brand">
                <div class="logo">2FA</div>
                <div>
                    <h1>Perfil de usuario</h1>
                </div>
            </div>
            <div>
                <a class="btn btn-ghost" href="dashboard.php">← Regresar</a>
                <a class="btn btn-ghost" href="logout.php">Cerrar sesión</a>
            </div>
        </header>

        <main class="grid">
            <div class="card">
                <h3>Datos personales</h3>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($_SESSION['usuario_email']); ?></p>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($_SESSION['usuario_id']); ?></p>
            </div>

            <div class="card">
                <h3>Protección 2FA</h3>
                <div class="text-muted">
                    <?php if ($tiene_2fa): ?>
                        <p>✅ <strong>Protección activada</strong></p>
                        <p>La autenticación en dos pasos está activa en tu cuenta.</p>
                    <?php else: ?>
                        <p>❌ <strong>Protección desactivada</strong></p>
                        <p>Aún no tienes 2FA configurado.</p>
                        <a class="btn btn-primary" href="activar_2fa.php">Habilitar 2FA</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <h3>Seguridad de la sesión</h3>
                <p><strong>ID de sesión:</strong> <?php echo session_id(); ?></p>
                <p><strong>2FA activo:</strong> <?php echo $_SESSION['autenticado_2fa'] ? 'Sí' : 'No'; ?></p>
                <p><strong>Última actividad:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
        </main>
    </div>
</body>
</html>