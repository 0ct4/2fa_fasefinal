<?php
session_start();
require_once 'clases/Google2FA.php';
require_once 'config/database.php';

// Si el usuario no está logueado o ya pasó 2FA, redirigir al login.
if (!isset($_SESSION['usuario_id']) || $_SESSION['autenticado_2fa']) {
    header("Location: login.php");
    exit;
}

// Inicializar servicios.
$database = new Database();
$db = $database->getConnection();
$google2fa = new Google2FA($db);

// Mensaje para la interfaz en caso de error.
$mensaje = "";

// Recuperar el secreto del usuario desde la BD para validar el token.
$secret = $google2fa->obtenerSecret2FA($_SESSION['usuario_id']);

// Procesar envío del código 2FA.
if ($_POST && isset($_POST['codigo_2fa'])) {
    $codigo = $_POST['codigo_2fa'];
    
    if ($google2fa->verificarCodigo($secret, $codigo)) {
        // Código válido: marcar sesión como autenticada con 2FA.
        $_SESSION['autenticado_2fa'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        // Código inválido: mostrar mensaje sin detalles adicionales.
        $mensaje = "❌ Código inválido. Intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmar Código 2FA</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
    </head>
<body>
    <div class="container">
        <main class="card center">
            <h2>Confirmación 2FA</h2>
            <p>Hola <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>,</p>
            <p>Escribe el código de 6 dígitos generado por tu app de autenticación:</p>

            <?php if ($mensaje): ?>
                <div class="alert alert-danger"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="post" class="mt-12">
                <div class="form-row">
                    <label>Código de 6 dígitos:</label>
                    <input type="text" name="codigo_2fa" maxlength="6" required placeholder="123456">
                </div>
                <div class="mt-12">
                    <button class="btn btn-primary" type="submit">Confirmar</button>
                    <a class="btn btn-ghost" href="logout.php">Cancelar sesión</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>