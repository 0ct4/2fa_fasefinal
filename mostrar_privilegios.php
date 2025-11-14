<?php
session_start();

// Requerir que el usuario esté autenticado y haya superado 2FA.
if (!isset($_SESSION['usuario_id']) || !$_SESSION['autenticado_2fa']) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Permisos de la Base de Datos</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
    </head>
<body>
    <div class="container">
        <header class="header">
            <div class="brand">
                <div class="logo">DB</div>
                <div>
                    <h1>Permisos de la BD</h1>
                </div>
            </div>
            <div>
                <a class="btn btn-ghost" href="dashboard.php">← Volver</a>
                <a class="btn btn-ghost" href="perfil.php">Perfil</a>
                <a class="btn btn-ghost" href="logout.php">Salir</a>
            </div>
        </header>

        <main class="grid">
            <div class="card">
                <h3>✅ Usuario de BD configurado</h3>
                <p><strong>Cuenta:</strong> usuario_2fa</p>
                <p><strong>Base de datos:</strong> sistema_2fa</p>
                
                <h4>Permisos otorgados (mínimos):</h4>
                <ul>
                    <li>✅ SELECT — Lectura de datos</li>
                    <li>✅ INSERT — Insertar registros</li>
                    <li>✅ UPDATE — Actualizar datos</li>
                    <li>❌ DELETE — No autorizado</li>
                    <li>❌ CREATE — No autorizado</li>
                    <li>❌ DROP — No autorizado</li>
                </ul>

                <h4>Comando para revisar permisos:</h4>
                <code>SHOW GRANTS FOR 'usuario_2fa'@'localhost';</code>

                <h4>Estructura de la tabla `usuarios`:</h4>
                <code>DESCRIBE usuarios;</code>
            </div>

            <div class="card">
                <h3>🔒 Consideraciones de Seguridad</h3>
                <p>El usuario de base de datos tiene privilegios mínimos necesarios para:</p>
                <ul>
                    <li>Registrar nuevos usuarios</li>
                    <li>Verificar credenciales de login</li>
                    <li>Gestionar secretos 2FA</li>
                    <li>No puede eliminar datos o modificar estructura</li>
                </ul>
            </div>
        </main>
    </div>
</body>
</html>