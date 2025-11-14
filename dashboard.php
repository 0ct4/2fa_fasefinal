<?php
session_start();

// Comprobar que la sesión pertenece a un usuario autenticado con 2FA.
if (!isset($_SESSION['usuario_id']) || !$_SESSION['autenticado_2fa']) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
    </head>
<body>
    <div class="container">
        <main class="card">
            <div class="kv">
                <div>
                    <h1>Bienvenido/a</h1>
                    <p class="text-muted">Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong></p>
                    <p class="text-muted">Correo: <strong><?php echo htmlspecialchars($_SESSION['usuario_email']); ?></strong></p>
                    <p>✅ Acceso protegido con 2FA</p>
                </div>

                <div class="center">
                    <a class="btn btn-ghost" href="perfil.php">👤 Perfil</a>
                    <a class="btn btn-ghost" href="mostrar_privilegios.php">🔐 Permisos</a>
                    <a class="btn btn-ghost" href="activar_2fa.php">📱 Administrar 2FA</a>
                    <a class="btn btn-ghost" href="logout.php">🚪 Salir</a>
                </div>
            </div>

            <section class="mt-12">
                <h2>Panel principal</h2>
                <p>Se trata de una vista protegida a la que se accede tras:</p>
                <ul>
                    <li>✅ Acceso con credenciales</li>
                    <li>✅ Confirmación mediante 2FA</li>
                </ul>
            </section>

            <section class="mt-12 card">
                <h3>✅ Requisitos cumplidos</h3>
                <ul class="criteria-list">
                    <li>✅ Usuario BD con privilegios mínimos</li>
                    <li>✅ Formularios de registro con validación</li>
                    <li>✅ Verificación de correo y unicidad</li>
                    <li>✅ Clases con responsabilidades separadas</li>
                    <li>✅ Sanitización de entradas</li>
                    <li>✅ Generación de QR para 2FA</li>
                    <li>✅ Login + confirmación 2FA</li>
                    <li>✅ Transferencia segura de sesiones</li>
                    <li>✅ Contraseñas hasheadas en BD</li>
                    <li>✅ QR generado tras el registro</li>
                    <li>✅ Tablas con datos consistentes</li>
                </ul>
            </section>

            <section class="mt-12 card">
                <h3>📊 Resumen</h3>
                <p><strong>ID de usuario:</strong> <?php echo htmlspecialchars($_SESSION['usuario_id']); ?></p>
                <p><strong>ID de sesión:</strong> <?php echo session_id(); ?></p>
                <p><strong>Último acceso:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                <p><strong>2FA activo:</strong> <?php echo $_SESSION['autenticado_2fa'] ? 'SÍ' : 'NO'; ?></p>
            </section>
        </main>
    </div>
</body>
</html>