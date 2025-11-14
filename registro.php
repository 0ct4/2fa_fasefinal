<?php
session_start();
require_once 'clases/Registro.php';
require_once 'config/database.php'; // Ruta corregida

// Inicializar recursos.
$database = new Database();
$db = $database->getConnection();
$registro = new Registro($db);

// Variables para mensajes en la interfaz.
$mensaje = "";
$tipo_mensaje = "";

// Procesar el envío del formulario de registro.
if ($_POST) {
    // Asignar y sanitizar (desde la capa de presentación; la clase Registro
    // debería hacer sanitización adicional y validaciones más estrictas).
    $registro->nombre = $_POST['nombre'] ?? '';
    $registro->apellido = $_POST['apellido'] ?? '';
    $registro->email = $_POST['email'] ?? '';
    $registro->password = $_POST['password'] ?? '';
    $registro->sexo = $_POST['sexo'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validación simple: comprobar que las contraseñas coincidan.
    if ($registro->password !== $confirm_password) {
        $mensaje = "Las contraseñas no coinciden, por favor verifica";
        $tipo_mensaje = "error";
    } else {
        // Intentar crear el usuario mediante la clase Registro.
        $resultado = $registro->registrar();
        
        if ($resultado['success']) {
            // Registro correcto: mostrar mensaje y limpiar campos.
            $mensaje = $resultado['message'];
            $tipo_mensaje = "success";
            $registro->nombre = $registro->apellido = $registro->email = $registro->sexo = '';
        } else {
            // Mostrar errores devueltos por la clase Registro.
            $mensaje = implode("<br>", $resultado['errors']);
            $tipo_mensaje = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuario</title>
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
                    <h1>Crear Cuenta</h1>
                    <p class="text-muted">Regístrate y añade protección con 2FA</p>
                </div>
            </div>
        </header>

        <main class="card">
            <?php if ($mensaje): ?>
                <div class="alert <?php echo $tipo_mensaje === 'success' ? 'alert-success' : 'alert-danger'; ?>"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="grid grid-cols-2">
                    <div class="form-row">
                        <label>Nombres:</label>
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($registro->nombre ?? ''); ?>" required>
                    </div>

                    <div class="form-row">
                        <label>Apellidos:</label>
                        <input type="text" name="apellido" value="<?php echo htmlspecialchars($registro->apellido ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Correo electrónico:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($registro->email ?? ''); ?>" required>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-row">
                        <label>Contraseña:</label>
                        <input type="password" name="password" required minlength="6">
                    </div>

                    <div class="form-row">
                        <label>Repetir contraseña:</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Género:</label>
                    <select name="sexo" required>
                        <option value="">Seleccionar</option>
                        <option value="M" <?php echo (($registro->sexo ?? '') == 'M') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="F" <?php echo (($registro->sexo ?? '') == 'F') ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>

                <div class="mt-12">
                    <button class="btn btn-primary" type="submit">Crear cuenta</button>
                    <a class="btn btn-ghost" href="login.php">¿Tienes cuenta? Accede</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>