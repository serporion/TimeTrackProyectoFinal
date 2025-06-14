# Timetrack

Timetrack es una aplicación web para el registro horario de empleados, diseñada para garantizar el cumplimiento de la
normativa laboral vigente en España. El sistema emplea tecnología de códigos QR y captura fotográfica en el momento del
fichaje, asegurando la autenticidad de cada registro y reduciendo posibles irregularidades.

La aplicación facilita la gestión interna de las empresas mediante el tratamiento automático de los datos, minimizando
errores y proporcionando información clave tanto para trabajadores como para empresarios y la administración competente.
Aunque está orientada principalmente al sector de la hostelería (bares, restaurantes y hoteles), puede adaptarse
fácilmente a otros sectores, especialmente a pequeñas empresas y autónomos que buscan una solución accesible y asequible.

**🔗 [Accede a la demo en producción](http://timetrack.atwebpages.com)**

## Características principales

- Registro de entrada y salida mediante QR y verificación fotográfica.
- Panel de administración para gestión de empleados y registros.
- Exportación y consulta de datos para la empresa y la administración.
- Interfaz intuitiva y fácil de usar.
- Adaptable a diferentes sectores y tamaños de empresa.

## Tecnologías utilizadas

- **Laravel 12** (backend, framework PHP)
- **Inertia.js** (puente entre backend y frontend)
- **Vue.js** (frontend)
- **MySQL Community Server** (base de datos)
- **Backpack for Laravel** (panel de administración, uso educativo/no comercial)
- **Laravel Breeze**, **Ziggy**, **Vue-qrcode-reader**, **SimpleSoftwareIO/QrCode**
- **PHP 8.2**

## Instalación

1. Clona el repositorio:

```bash
git clone https://github.com/usuario/timetrack.git
```

2. Instala dependencias PHP:

```bash
composer install
```

3. Instala dependencias JavaScript:

```bash
npm install
```

4. Copia el archivo `.env.example` a `.env` y configura las variables de entorno (base de datos, correo, etc.).

5. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

6. Ejecuta las migraciones y (opcionalmente) los seeders:

```bash
php artisan migrate --seed
```

7. Levanta el servidor de desarrollo:

```bash
php artisan serve
```

## 🔐 Configuración JWT

Para el correcto funcionamiento del sistema de autenticación basada en JSON Web Tokens (JWT), es necesario definir una 
clave secreta en el archivo `.env`:

```env
JWT_SECRET=tu_clave_secreta_segura
```

Si utilizas el paquete `tymon/jwt-auth` u otro sistema compatible, puedes generar esta clave automáticamente ejecutando:

```bash
php artisan jwt:secret
```

Esta clave se utiliza para firmar y verificar los tokens persistentes que se almacenan en la tabla `credenciales`. Estos 
tokens actúan como una “clave maestra” que se incluye en cada solicitud de fichaje para validar que el usuario es 
auténtico y está autorizado.

> ⚠️ Si esta clave no está configurada correctamente, las validaciones de autenticación fallarán y los fichajes no podrán 
> completarse.

## 🧪 Datos de prueba y seeders

Para facilitar la evaluación de la aplicación y comprobar su funcionamiento sin necesidad de crear datos manualmente,
se incluye una batería de datos de prueba. Esta incluye:

- 3 empleados con contratos activos (uno de ellos con dos contratos).
- 2 administradores (uno con acceso completo y otro limitado solo al terminal).
- Múltiples fichajes en días consecutivos.
- QRs válidos, fotos simuladas y auditorías de verificación.

### Opción 1: Usar el Seeder de Laravel

1. Copia el archivo `TestSeeder.php` a `database/seeders/` si no está ya.
2. Asegúrate de que en `DatabaseSeeder.php` esté registrado:

```php
$this->call(TestSeeder::class);
```

3. Ejecuta el seeder:

```bash
php artisan db:seed
```

> Esto poblará automáticamente la base de datos con datos funcionales de prueba.

### Opción 2: Importar SQL manualmente

1. Asegúrate de tener la base de datos creada y las migraciones ejecutadas:

```bash
php artisan migrate
```

2. Importa el archivo SQL:

```bash
mysql -u tu_usuario -p tu_base_de_datos < datos_prueba_extendido.sql
```

> El archivo `datos_prueba_extendido.sql` contiene los mismos datos de prueba generados por el seeder.

## Uso

Accede a la aplicación a través de tu navegador en la URL proporcionada por el servidor local (por defecto,
http://localhost:8000).  
Consulta la documentación interna para credenciales de prueba y ejemplos de uso.

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](LICENSE).  
Consulta el archivo LICENSE para más detalles.

## Créditos y dependencias

- Este proyecto utiliza software de terceros bajo licencias libres y comerciales. Consulta la documentación técnica para
- más información.
- **Backpack for Laravel** se utiliza únicamente en contexto educativo/no comercial, conforme a su licencia gratuita.

## Contacto

Oscar Delgado Huertas  
[serporionGit@hotmail.com]

