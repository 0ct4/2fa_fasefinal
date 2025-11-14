<?php
session_start();
require_once 'clases/Google2FA.php';
require_once 'config/database.php';

// Comprobar que existe sesión de usuario; si no, redirigir al login.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Inicializar Google2FA y conexión a BD.
$database = new Database();
$db = $database->getConnection();
$google2fa = new Google2FA($db);

// Mensaje informativo para la UI.
$mensaje = "";

// Generar un secreto temporal por sesión si aún no existe.
if (!isset($_SESSION['secret_temp'])) {
    $_SESSION['secret_temp'] = $google2fa->generarSecreto();
}

$secret = $_SESSION['secret_temp'];
// Generar la URL del QR (data URI o similar) para mostrar en la vista.
$qr_url = $google2fa->generarQR($_SESSION['usuario_email'], $secret, "Sistema2FA");

// Procesar el formulario de activación: revisar el código de 6 dígitos.
if ($_POST && isset($_POST['codigo_2fa'])) {
    $codigo = $_POST['codigo_2fa'];
    
    // Verificar el código proporcionado usando la librería Google2FA.
    if ($google2fa->verificarCodigo($secret, $codigo)) {
        // Si es correcto, persistir el secreto del usuario en BD.
        if ($google2fa->guardarSecreto($_SESSION['usuario_id'], $secret)) {
            $_SESSION['autenticado_2fa'] = true;
            $mensaje = "✅ Autenticación en dos pasos activada";
            // Limpiar secreto temporal de sesión.
            unset($_SESSION['secret_temp']);
        } else {
            // Error al guardar en BD: informar al usuario.
            $mensaje = "❌ No se pudo guardar la configuración 2FA";
        }
    } else {
        // Código incorrecto: notificar.
        $mensaje = "❌ Código inválido. Intenta de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Habilitar Autenticación 2FA</title>
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
                    <h1>Habilitar 2FA</h1>
                    <p class="text-muted">Añade autenticación en dos pasos a tu cuenta</p>
                </div>
            </div>
        </header>

        <main class="card">
            <?php if ($mensaje): ?>
                <div class="alert <?php echo strpos($mensaje, '✅') !== false ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($_POST['codigo_2fa']) || strpos($mensaje, '❌') !== false): ?>
                <section class="center">
                    <p><strong>1)</strong> Escanea el código QR con Google Authenticator u otra app compatible</p>
                    <div class="qr">
                        <img src="<?php echo $qr_url; ?>" alt="Código QR para 2FA">
                    </div>
                    <p><strong>Clave secreta:</strong> <span class="secret-code"><?php echo $secret; ?></span></p>
                </section>

                <section class="mt-12">
                    <p><strong>2)</strong> Introduce el código de 6 dígitos desde la app</p>
                    <form method="post">
                        <div class="form-row">
                            <label>Código de verificación:</label>
                            <input type="text" name="codigo_2fa" maxlength="6" required placeholder="123456">
                        </div>
                        <div class="mt-12">
                            <button class="btn btn-primary" type="submit">Habilitar 2FA</button>
                            <a class="btn btn-ghost" href="logout.php">Salir</a>
                        </div>
                    </form>
                </section>
            <?php else: ?>
                <p class="center"><a class="btn btn-primary" href="dashboard.php">Ir al Dashboard</a></p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>