<?php
session_start();
require_once 'clases/Login.php';
require_once 'config/database.php';

// Inicializar conexión a la base de datos y la clase de login.
$database = new Database();
$db = $database->getConnection();
$login = new Login($db);

// Mensaje informativo para mostrar en la interfaz (error/alerta).
$mensaje = "";

// Procesar envío de formulario (si existe $_POST).
if ($_POST) {
    // Obtener credenciales desde el formulario (defensivo).
    $login->email = $_POST['email'] ?? '';
    $login->password = $_POST['password'] ?? '';
    
    // Verificar credenciales con la clase Login.
    if ($login->verificarCredenciales()) {
        // Credenciales válidas: guardar información relevante en sesión.
        $_SESSION['usuario_id'] = $login->usuario_id;
        $_SESSION['usuario_nombre'] = $login->nombre;
        $_SESSION['usuario_email'] = $login->email;
        // Marcar como no autenticado por 2FA todavía.
        $_SESSION['autenticado_2fa'] = false;
        
        // Si el usuario tiene 2FA configurado, continuar al paso de verificación.
        if ($login->tiene2FAActivado()) {
            header("Location: verificar_2fa.php");
            exit;
        } else {
            // Si no tiene 2FA, redirigir para activarlo.
            header("Location: activar_2fa.php");
            exit;
        }
    } else {
        // Mensaje genérico en caso de credenciales inválidas.
        $mensaje = "Correo o contraseña no válidos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
                    <h1>Acceder</h1>
                    <p class="text-muted">Ingresa a tu perfil seguro</p>
                </div>
            </div>
        </header>

        <main class="card">
            <?php if ($mensaje): ?>
                <div class="alert alert-danger"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-row">
                    <label>Correo electrónico:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>

                <div class="form-row">
                    <label>Contraseña:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="mt-12">
                    <button class="btn btn-primary" type="submit">Entrar</button>
                    <a class="btn btn-ghost" href="registro.php">Crear cuenta</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>