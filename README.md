🔐 Sistema de Login con 2FA (Autenticación de Dos Factores)

📋 Descripción
Proyecto web que implementa un sistema de registro e inicio de sesión protegido con autenticación de dos factores (2FA) mediante códigos TOTP (Google Authenticator u otras apps compatibles). El sistema usa PHP orientado a objetos y genera códigos QR para enlazar la app de 2FA.

🚀 Características
- ✅ **Registro y Login** con validación y hash de contraseñas
- 📲 **2FA (TOTP)**: generación de secreto, QR y verificación de códigos de 6 dígitos
- 🧾 **Sesiones seguras**: transferencia de estado entre login y verificación 2FA
- 🎨 **Interfaz responsiva**: hoja de estilos global `css/style.css` incluida
- 🛡️ **Buenas prácticas**: uso de clases para responsabilidades separadas y sanitización básica
- ✅ **Flujos completos**: activar 2FA tras registro, verificar 2FA en cada inicio

🛠️ Tecnologías Utilizadas
- Frontend: HTML5, CSS (archivo `css/style.css`) y JavaScript mínimo
- Backend: PHP (orientado a objetos)
- Base de datos: MySQL (conexión en `config/database.php`)

📁 Estructura del Proyecto

2fa_fasefinal/
│
├── activar_2fa.php        // Vista para generar secreto/QR y activar 2FA
├── verificar_2fa.php      // Vista para confirmar el código TOTP al iniciar sesión
├── login.php              // Pantalla de inicio de sesión
├── registro.php           // Formulario y lógica de registro
├── dashboard.php          // Área protegida tras autenticación completa
├── perfil.php             // Información del usuario y estado 2FA
├── mostrar_privilegios.php// Información sobre privilegios de BD (documental)
├── logout.php             // Cierra sesión
├── css/
│   └── style.css          // Hoja de estilos global (creada)
├── clases/
│   ├── Google2FA.php      // Lógica TOTP (generar/validar/guardar secreto)
│   ├── Login.php          // Métodos de verificación de credenciales
│   ├── Registro.php       // Lógica de creación de usuarios
│   └── Sanitizador.php    // Funciones de sanitización (si aplica)
└── config/
    ├── database.php      // Clase/conexión a la BD
    ├── crear_tabla.php   // Script para crear tablas de ejemplo
    └── crear_usuario_bd.php // (Opcional) script para usuario DB

⚙️ Configuración

Prerrequisitos
- Servidor web con PHP (WAMP/XAMPP)
- MySQL
- Navegador moderno

Instalación rápida
1. Copia el proyecto dentro de la ruta pública de tu servidor (p.ej. `www/2fa_fasefinal`).
2. Crea la base de datos y tablas; puedes usar `config/crear_tabla.php` o ejecutar el SQL apropiado en tu servidor MySQL.
3. Ajusta las credenciales en `config/database.php` (host, usuario, contraseña, nombre de BD).
4. Abre en el navegador `http://localhost/2fa_fasefinal/registro.php` para crear un usuario.

Incluir estilos
- El proyecto ya incluye `css/style.css`. Asegúrate de que la carpeta `css/` y el archivo existan y estén accesibles.
- Las páginas principales ya incluyen el enlace:

  `<link rel="stylesheet" href="css/style.css">`

🎯 Flujo de uso

Registro y activación 2FA
1. Accede a `registro.php` y crea una cuenta.
2. Tras registrarte, inicia sesión en `login.php`.
3. Si no tienes 2FA activado, serás redirigido a `activar_2fa.php`.
4. En `activar_2fa.php` se muestra un QR y una clave secreta; escanéalo en Google Authenticator u otra app.
5. Introduce el código de 6 dígitos para confirmar y guardar el secreto en la BD.

Inicio de sesión con 2FA
1. En `login.php` ingresa correo y contraseña.
2. Si el usuario tiene 2FA activo, se redirige a `verificar_2fa.php`.
3. Ingresa el código actual de 6 dígitos de la app para completar el acceso.

📊 Estructura mínima de la tabla `usuarios` (ejemplo)
| Campo           | Tipo               | Descripción                      |
|-----------------|--------------------|----------------------------------|
| id              | INT AUTO_INCREMENT | Clave primaria                   |
| nombre          | VARCHAR(100)       | Nombre del usuario               |
| apellido        | VARCHAR(100)       | Apellidos                        |
| email           | VARCHAR(150)       | Correo único                     |
| password_hash   | VARCHAR(255)       | Hash de la contraseña            |
| secret_2fa      | VARCHAR(100)       | Secreto TOTP (nullable)          |
| creado_en       | TIMESTAMP          | Fecha de creación                |

🔧 Archivos principales y responsabilidades

- `clases/Google2FA.php`:
  - Genera secretos, crea la URL/imagen QR y valida códigos TOTP.
  - Guarda y recupera el secreto asociado a un usuario.

- `clases/Login.php`:
  - Contiene métodos para verificar credenciales y determinar si el usuario tiene 2FA.

- `clases/Registro.php`:
  - Registra usuarios, valida datos y asegura unicidad de email.

- `config/database.php`:
  - Provee la conexión PDO a MySQL usada por las clases.

- `css/style.css`:
  - Estilos globales responsivos y utilidades (botones, tarjetas, formularios).

👥 Autores
- Frauca, Octavio 8-1010-1989
- Carrion, Arelys 8-994-1678
