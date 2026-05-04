# Guía Completa: TiDB Cloud + Laravel (Eloquent ORM) + Docker + Render

> **Nivel:** Desde cero — no se asume ninguna configuración previa.
> **Stack:** TiDB Cloud · Laravel 11 · Eloquent ORM · PHP 8.3 · Docker · Render

---

## Tabla de Contenidos

1. [Creación y configuración de TiDB Cloud](#1-creación-y-configuración-de-tidb-cloud)
2. [Obtención de credenciales de conexión](#2-obtención-de-credenciales-de-conexión)
3. [Conexión segura SSL/TLS — por qué es obligatoria](#3-conexión-segura-ssltls--por-qué-es-obligatoria)
4. [Descarga y ubicación del certificado CA](#4-descarga-y-ubicación-del-certificado-ca)
5. [Configuración del archivo `.env`](#5-configuración-del-archivo-env)
6. [Configuración de `config/database.php`](#6-configuración-de-configdatabasephp)
7. [Verificación con modelos Eloquent](#7-verificación-con-modelos-eloquent)
8. [Creación del `Dockerfile`](#8-creación-del-dockerfile)
9. [Configuración de `docker-compose.yml`](#9-configuración-de-docker-composeyml)
10. [Variables de entorno dentro de Docker](#10-variables-de-entorno-dentro-de-docker)
11. [Probar la conexión desde el contenedor](#11-probar-la-conexión-desde-el-contenedor)
12. [Solución del error "insecure transport"](#12-solución-del-error-insecure-transport)
13. [Preparación para producción](#13-preparación-para-producción)
14. [Configuración completa en Render](#14-configuración-completa-en-render)
15. [Pruebas finales en producción](#15-pruebas-finales-en-producción)
16. [Errores comunes y soluciones](#16-errores-comunes-y-soluciones)

---

## 1. Creación y configuración de TiDB Cloud

### 1.1 Crear una cuenta en TiDB Cloud

1. Ve a [https://tidbcloud.com](https://tidbcloud.com)
2. Haz clic en **"Sign Up"** (puedes usar Google, GitHub o email)
3. Completa el registro y verifica tu correo electrónico
4. Inicia sesión en el dashboard

### 1.2 Crear un Cluster

1. En el dashboard, haz clic en **"Create Cluster"**
2. Elige el tipo de cluster:
   - **TiDB Serverless** → gratuito, ideal para desarrollo y proyectos pequeños ✅ (recomendado para empezar)
   - **TiDB Dedicated** → pago, para producción de alto tráfico
3. Para este tutorial elige **TiDB Serverless**
4. Selecciona un **Cloud Provider** (AWS o Google Cloud) y una **región** cercana a tu ubicación (ej. `us-east-1` o `us-west-2`)
5. Dale un nombre al cluster, por ejemplo: `laravel-app`
6. Haz clic en **"Create"** y espera 1-2 minutos a que el cluster esté listo (estado: `Active`)

### 1.3 Crear la base de datos

Una vez que el cluster esté activo:

1. Haz clic en el nombre de tu cluster para entrar al detalle
2. En el menú lateral, ve a **"SQL Editor"** o **"Chat2Query"**
3. Ejecuta el siguiente SQL para crear tu base de datos:

```sql
CREATE DATABASE laravel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Verifica que se creó correctamente:

```sql
SHOW DATABASES;
```

Deberías ver `laravel_db` en la lista.

---

## 2. Obtención de credenciales de conexión

### 2.1 Acceder a las credenciales

1. En el dashboard de TiDB Cloud, selecciona tu cluster
2. Haz clic en el botón **"Connect"** (esquina superior derecha del detalle del cluster)
3. Aparecerá un panel con opciones de conexión

### 2.2 Configurar el método de conexión

1. En **"Connect With"** selecciona: `General`
2. En **"Operating System"** selecciona: `Linux` (para que sea compatible con Docker)
3. Verás algo como esto (los valores reales serán diferentes):

```
Host:     gateway01.us-east-1.prod.aws.tidbcloud.com
Port:     4000
User:     2abc123def.root
Password: (se genera al crear el cluster o puedes crearla)
```

### 2.3 Crear o resetear la contraseña root

Si no tienes contraseña guardada:

1. En el panel de conexión, haz clic en **"Generate Password"** o **"Reset Password"**
2. **¡Guarda esta contraseña inmediatamente!** Solo se muestra una vez
3. Ejemplo de contraseña generada: `ABC123xyz!@#456`

### 2.4 Crear un usuario de aplicación (recomendado para producción)

En el SQL Editor, crea un usuario dedicado para Laravel:

```sql
-- Crear usuario
CREATE USER 'laravel_user'@'%' IDENTIFIED BY 'TuPasswordSegura123!';

-- Otorgar permisos sobre la base de datos de la aplicación
GRANT ALL PRIVILEGES ON laravel_db.* TO 'laravel_user'@'%';

-- Aplicar cambios
FLUSH PRIVILEGES;

-- Verificar
SHOW GRANTS FOR 'laravel_user'@'%';
```

### 2.5 Resumen de tus credenciales

Anota estos datos en un lugar seguro:

| Variable | Ejemplo de valor |
|----------|-----------------|
| `DB_HOST` | `gateway01.us-east-1.prod.aws.tidbcloud.com` |
| `DB_PORT` | `4000` |
| `DB_DATABASE` | `laravel_db` |
| `DB_USERNAME` | `laravel_user` o `2abc123def.root` |
| `DB_PASSWORD` | `TuPasswordSegura123!` |

---

## 3. Conexión segura SSL/TLS — por qué es obligatoria

TiDB Cloud **requiere conexión cifrada con SSL/TLS obligatoriamente**. Intentar conectar sin SSL produce el error:

```
SQLSTATE[HY000] [2026] SSL connection error:
Connections using insecure transport are prohibited
```

Esto significa que Laravel debe enviar:
- El certificado de la Autoridad Certificadora (CA) de TiDB Cloud
- La opción SSL activada en el driver MySQL/PDO

No es opcional: **toda conexión a TiDB Cloud necesita SSL**, incluso en desarrollo local.

---

## 4. Descarga y ubicación del certificado CA

### 4.1 Descargar el certificado

TiDB Cloud usa el CA raíz de ISRG (Let's Encrypt) para TiDB Serverless. Tienes dos opciones:

**Opción A — Descargar desde TiDB Cloud (recomendado):**

1. En el panel de conexión de TiDB Cloud, busca la sección **"CA Cert"** o **"Download CA"**
2. Descarga el archivo (se llama `isrgrootx1.pem` o similar)

**Opción B — Descargar directamente desde ISRG (siempre disponible):**

```bash
# Desde tu terminal local (fuera de Docker)
curl -o isrgrootx1.pem https://letsencrypt.org/certs/isrgrootx1.pem

# Verificar que el archivo se descargó correctamente
openssl x509 -in isrgrootx1.pem -text -noout | grep "Subject:"
# Debe mostrar: Subject: O=Internet Security Research Group, CN=ISRG Root X1
```

### 4.2 Ubicación del certificado en el proyecto

```bash
# Desde la raíz de tu proyecto Laravel
mkdir -p storage/ssl

# Copia el certificado descargado a esta carpeta
cp /ruta/donde/descargaste/isrgrootx1.pem storage/ssl/isrgrootx1.pem

# Verificar que está en su lugar
ls -la storage/ssl/
# -rw-r--r-- 1 user group 1762 Jan 01 00:00 isrgrootx1.pem
```

### 4.3 Agregar al `.gitignore` — NO (el certificado SÍ debe estar en el repositorio)

El certificado CA es público (no es secreto), así que debe incluirse en el repositorio para que esté disponible dentro de Docker y en Render:

```bash
# Verificar que NO esté en .gitignore (debe estar disponible en el repo)
cat .gitignore | grep -i ssl
# No debe aparecer nada relacionado con el certificado SSL
```

Si tu `.gitignore` ignora `storage/ssl/`, elimina esa regla o añade una excepción:

```gitignore
# En .gitignore: asegurar que el certificado esté incluido
!storage/ssl/isrgrootx1.pem
```

### 4.4 Hacer que Git rastree el archivo

```bash
git add storage/ssl/isrgrootx1.pem
git status
# Debe mostrar: new file: storage/ssl/isrgrootx1.pem
```

---

## 5. Configuración del archivo `.env`

El archivo `.env` contiene las variables de entorno para tu entorno local (no se sube a Git).

### 5.1 Archivo `.env` completo y funcional

Abre o crea el archivo `.env` en la raíz de tu proyecto Laravel:

```dotenv
# ─── Aplicación ───────────────────────────────────────────────
APP_NAME="Mi App Laravel"
APP_ENV=local
APP_KEY=base64:ESTA_CLAVE_SE_GENERA_CON_php_artisan_key_generate
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=America/La_Paz

# ─── Logging ──────────────────────────────────────────────────
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# ─── Base de Datos (TiDB Cloud) ───────────────────────────────
DB_CONNECTION=mysql
DB_HOST=gateway01.us-east-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=TuPasswordSegura123!

# CRÍTICO: Habilitar SSL/TLS para TiDB Cloud
# La ruta debe apuntar al certificado CA DENTRO del contenedor Docker
DB_SSL_CA=/var/www/html/storage/ssl/isrgrootx1.pem
DB_SSL_MODE=REQUIRED

# ─── Cache & Session ──────────────────────────────────────────
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# ─── Cola de trabajos ─────────────────────────────────────────
QUEUE_CONNECTION=sync

# ─── Mail (puedes ignorar por ahora) ─────────────────────────
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> **IMPORTANTE sobre `DB_SSL_CA`:**
> La ruta `/var/www/html/storage/ssl/isrgrootx1.pem` es la ruta **dentro del contenedor Docker**.
> En desarrollo local sin Docker, usa la ruta absoluta en tu máquina, ej: `/home/tuusuario/proyecto/storage/ssl/isrgrootx1.pem`

### 5.2 Generar la APP_KEY

```bash
php artisan key:generate
```

Esto actualiza automáticamente `APP_KEY` en tu `.env`.

### 5.3 Verificar el archivo `.gitignore`

Asegúrate de que `.env` esté en `.gitignore` (ya viene así en Laravel por defecto):

```bash
grep "^\.env$" .gitignore
# Debe mostrar: .env
```

---

## 6. Configuración de `config/database.php`

Este es el archivo más importante para que Eloquent ORM funcione correctamente con TiDB Cloud.

### 6.1 Configuración completa de `config/database.php`

Abre `config/database.php` y reemplaza su contenido por el siguiente (o aplica los cambios en la sección `mysql`):

```php
<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    */
    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */
    'connections' => [

        'sqlite' => [
            'driver'                  => 'sqlite',
            'url'                     => env('DB_URL'),
            'database'                => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix'                  => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout'            => null,
            'journal_mode'            => null,
            'synchronous'             => null,
        ],

        /*
        |----------------------------------------------------------------------
        | MySQL / TiDB Cloud Connection
        |----------------------------------------------------------------------
        | TiDB es 100% compatible con el protocolo MySQL, por lo tanto
        | usamos el driver 'mysql' de Laravel/Eloquent.
        |
        | CRÍTICO: Las opciones SSL son OBLIGATORIAS para TiDB Cloud.
        | Sin ellas obtendrás: "Connections using insecure transport are prohibited"
        |----------------------------------------------------------------------
        */
        'mysql' => [
            'driver'         => 'mysql',
            'url'            => env('DB_URL'),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '4000'),       // TiDB usa puerto 4000
            'database'       => env('DB_DATABASE', 'laravel'),
            'username'       => env('DB_USERNAME', 'root'),
            'password'       => env('DB_PASSWORD', ''),
            'unix_socket'    => env('DB_SOCKET', ''),
            'charset'        => env('DB_CHARSET', 'utf8mb4'),
            'collation'      => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix'         => '',
            'prefix_indexes' => true,

            /*
            | TiDB Serverless: strict_mode debe estar en true
            | TiDB es compatible con el modo estricto de MySQL 8
            */
            'strict'         => true,

            'engine'         => null,

            /*
            | Opciones PDO para SSL/TLS
            |
            | MYSQL_ATTR_SSL_CA    → ruta al certificado CA (OBLIGATORIO en TiDB Cloud)
            | MYSQL_ATTR_SSL_VERIFY_SERVER_CERT → verificar que el cert del servidor
            |                                     coincide con el CA (recomendado: true)
            |
            | Estas opciones se leen desde las variables de entorno DB_SSL_CA
            | para que funcionen tanto en local, Docker, como en Render.
            */
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA                  => env('DB_SSL_CA'),
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT  => true,
                PDO::ATTR_EMULATE_PREPARES              => true,
                PDO::ATTR_TIMEOUT                       => 30,
            ]) : [],
        ],

        'mariadb' => [
            'driver'         => 'mariadb',
            'url'            => env('DB_URL'),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '3306'),
            'database'       => env('DB_DATABASE', 'laravel'),
            'username'       => env('DB_USERNAME', 'root'),
            'password'       => env('DB_PASSWORD', ''),
            'unix_socket'    => env('DB_SOCKET', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::ATTR_SSL_CA => env('DB_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver'         => 'pgsql',
            'url'            => env('DB_URL'),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '5432'),
            'database'       => env('DB_DATABASE', 'laravel'),
            'username'       => env('DB_USERNAME', 'root'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => 'utf8',
            'prefix'         => '',
            'prefix_indexes' => true,
            'search_path'    => 'public',
            'sslmode'        => 'prefer',
        ],

        'sqlsrv' => [
            'driver'         => 'sqlsrv',
            'url'            => env('DB_URL'),
            'host'           => env('DB_HOST', 'localhost'),
            'port'           => env('DB_PORT', '1433'),
            'database'       => env('DB_DATABASE', 'laravel'),
            'username'       => env('DB_USERNAME', 'root'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => 'utf8',
            'prefix'         => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    */
    'migrations' => [
        'table'                  => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    */
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix'  => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],
        'default' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],

];
```

### 6.2 ¿Por qué `array_filter()`?

La función `array_filter()` elimina valores `null` del array de opciones PDO. Esto es esencial porque si `DB_SSL_CA` no está definido en el entorno, `env('DB_SSL_CA')` retorna `null`, y pasar `null` a `PDO::MYSQL_ATTR_SSL_CA` causaría un error. `array_filter()` elimina esas entradas nulas automáticamente.

---

## 7. Verificación con modelos Eloquent

### 7.1 Crear una migración de prueba

```bash
# Crear migración
php artisan make:migration create_test_connection_table

# Esto crea un archivo en database/migrations/
```

Edita el archivo de migración recién creado:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_connection', function (Blueprint $table) {
            $table->id();
            $table->string('mensaje');
            $table->boolean('ssl_activo')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_connection');
    }
};
```

### 7.2 Crear el modelo Eloquent

```bash
php artisan make:model TestConnection
```

Edita `app/Models/TestConnection.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestConnection extends Model
{
    protected $table = 'test_connection';

    protected $fillable = [
        'mensaje',
        'ssl_activo',
    ];

    protected $casts = [
        'ssl_activo' => 'boolean',
    ];
}
```

### 7.3 Crear un comando Artisan de prueba

```bash
php artisan make:command TestTiDBConnection
```

Edita `app/Console/Commands/TestTiDBConnection.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\TestConnection;

class TestTiDBConnection extends Command
{
    protected $signature   = 'tidb:test';
    protected $description = 'Prueba la conexión a TiDB Cloud usando Eloquent ORM';

    public function handle(): int
    {
        $this->info('═══════════════════════════════════════');
        $this->info('   Prueba de Conexión a TiDB Cloud');
        $this->info('═══════════════════════════════════════');

        // ── 1. Conexión básica PDO ──────────────────────────────
        $this->newLine();
        $this->line('📡 [1/4] Verificando conexión PDO...');
        try {
            DB::connection()->getPdo();
            $this->info('    ✅ Conexión PDO establecida correctamente');
        } catch (\Exception $e) {
            $this->error('    ❌ Error de conexión PDO: ' . $e->getMessage());
            return self::FAILURE;
        }

        // ── 2. Verificar SSL ────────────────────────────────────
        $this->line('🔒 [2/4] Verificando cifrado SSL...');
        try {
            $sslStatus = DB::select("SHOW STATUS LIKE 'Ssl_cipher'");
            $cipher    = $sslStatus[0]->Value ?? '';

            if (!empty($cipher)) {
                $this->info("    ✅ SSL activo — Cipher: {$cipher}");
                $sslActivo = true;
            } else {
                $this->warn('    ⚠️  SSL NO está activo (conexión sin cifrado)');
                $sslActivo = false;
            }
        } catch (\Exception $e) {
            $this->error('    ❌ No se pudo verificar SSL: ' . $e->getMessage());
            $sslActivo = false;
        }

        // ── 3. Operaciones Eloquent (INSERT y SELECT) ───────────
        $this->line('💾 [3/4] Probando Eloquent ORM...');
        try {
            // INSERT usando Eloquent
            $registro = TestConnection::create([
                'mensaje'    => 'Conexión exitosa desde ' . gethostname(),
                'ssl_activo' => $sslActivo,
            ]);
            $this->info("    ✅ INSERT exitoso — ID: {$registro->id}");

            // SELECT usando Eloquent
            $encontrado = TestConnection::find($registro->id);
            $this->info("    ✅ SELECT exitoso — Mensaje: {$encontrado->mensaje}");

            // UPDATE usando Eloquent
            $encontrado->update(['mensaje' => 'Actualizado: ' . now()->toDateTimeString()]);
            $this->info("    ✅ UPDATE exitoso");

            // DELETE usando Eloquent
            $encontrado->delete();
            $this->info("    ✅ DELETE exitoso");

        } catch (\Exception $e) {
            $this->error('    ❌ Error en Eloquent: ' . $e->getMessage());
            return self::FAILURE;
        }

        // ── 4. Información del servidor ─────────────────────────
        $this->line('ℹ️  [4/4] Información del servidor TiDB...');
        try {
            $version = DB::select('SELECT VERSION() as version')[0]->version;
            $dbName  = DB::select('SELECT DATABASE() as db')[0]->db;
            $this->info("    ✅ Versión TiDB: {$version}");
            $this->info("    ✅ Base de datos: {$dbName}");
        } catch (\Exception $e) {
            $this->warn('    ⚠️  No se pudo obtener info del servidor: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('   ✅ TODAS LAS PRUEBAS PASARON');
        $this->info('═══════════════════════════════════════');

        return self::SUCCESS;
    }
}
```

### 7.4 Ejecutar migraciones y la prueba

```bash
# Ejecutar migraciones (crea la tabla en TiDB Cloud)
php artisan migrate

# Ejecutar el comando de prueba
php artisan tidb:test
```

**Salida esperada:**

```
═══════════════════════════════════════
   Prueba de Conexión a TiDB Cloud
═══════════════════════════════════════

📡 [1/4] Verificando conexión PDO...
    ✅ Conexión PDO establecida correctamente
🔒 [2/4] Verificando cifrado SSL...
    ✅ SSL activo — Cipher: TLS_AES_256_GCM_SHA384
💾 [3/4] Probando Eloquent ORM...
    ✅ INSERT exitoso — ID: 1
    ✅ SELECT exitoso — Mensaje: Conexión exitosa desde mi-pc
    ✅ UPDATE exitoso
    ✅ DELETE exitoso
ℹ️  [4/4] Información del servidor TiDB...
    ✅ Versión TiDB: 8.1.0-TiDB-v7.5.3
    ✅ Base de datos: laravel_db

═══════════════════════════════════════
   ✅ TODAS LAS PRUEBAS PASARON
═══════════════════════════════════════
```

---

## 8. Creación del `Dockerfile`

### 8.1 Estructura del proyecto antes de crear el Dockerfile

```
mi-proyecto-laravel/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
│   └── ssl/
│       └── isrgrootx1.pem    ← certificado CA (debe estar aquí)
├── vendor/
├── .env                      ← NO se sube a Git
├── .env.example              ← SÍ se sube a Git
├── composer.json
├── composer.lock
├── artisan
├── Dockerfile                ← vamos a crearlo
└── docker-compose.yml        ← vamos a crearlo
```

### 8.2 Dockerfile completo

Crea el archivo `Dockerfile` en la raíz del proyecto:

```dockerfile
# ─────────────────────────────────────────────────────────────────────────────
# Dockerfile para Laravel 11 + TiDB Cloud (SSL)
# Base: PHP 8.3 FPM en Alpine Linux (imagen pequeña y segura)
# ─────────────────────────────────────────────────────────────────────────────

# ── Etapa 1: Instalar dependencias de Composer ────────────────────────────────
FROM composer:2.7 AS composer_builder

WORKDIR /app

# Copiar archivos de dependencias primero (para aprovechar el caché de Docker)
COPY composer.json composer.lock ./

# Instalar dependencias de producción sin autoloader optimizado (se hará después)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

# Copiar el resto del código fuente
COPY . .

# Generar el autoloader optimizado
RUN composer dump-autoload --optimize --no-dev

# ── Etapa 2: Imagen final de producción ──────────────────────────────────────
FROM php:8.3-fpm-alpine

LABEL maintainer="Tu Nombre <tu@email.com>"
LABEL description="Laravel + TiDB Cloud (SSL/TLS)"

# ── Instalar dependencias del sistema operativo ───────────────────────────────
RUN apk update && apk add --no-cache \
    # Nginx para servir la aplicación
    nginx \
    # Supervisor para manejar múltiples procesos (nginx + php-fpm)
    supervisor \
    # Herramientas necesarias para compilar extensiones PHP
    $PHPIZE_DEPS \
    # Dependencias para extensiones PHP
    openssl \
    openssl-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    # Para extensiones de imágenes (opcional pero común en Laravel)
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    # Git (necesario para algunas dependencias de Composer)
    git \
    # Bash para scripts
    bash \
    && rm -rf /var/cache/apk/*

# ── Instalar extensiones PHP necesarias ──────────────────────────────────────
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        # PDO y driver MySQL (OBLIGATORIO para TiDB Cloud con Eloquent)
        pdo \
        pdo_mysql \
        # Extensión MySQL mejorada
        mysqli \
        # Compresión ZIP
        zip \
        # Procesamiento de imágenes
        gd \
        # Entrada/Salida de buffers
        opcache \
        # BCMath para operaciones numéricas de Laravel
        bcmath \
        # PCNTL para manejo de señales (queues de Laravel)
        pcntl \
    && docker-php-ext-enable pdo pdo_mysql opcache

# ── Configurar PHP ────────────────────────────────────────────────────────────
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "post_max_size = 50M"    >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "memory_limit = 256M"   >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "max_execution_time = 60" >> /usr/local/etc/php/conf.d/laravel.ini

# ── Configurar OPcache para producción ───────────────────────────────────────
RUN echo "opcache.enable=1"                   >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128"  >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0"   >> /usr/local/etc/php/conf.d/opcache.ini

# ── Configurar Nginx ──────────────────────────────────────────────────────────
RUN mkdir -p /run/nginx

# Crear configuración de Nginx para Laravel
RUN cat > /etc/nginx/nginx.conf << 'EOF'
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    log_format main '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for"';

    access_log /var/log/nginx/access.log main;
    sendfile on;
    keepalive_timeout 65;

    server {
        listen 8080;
        server_name _;
        root /var/www/html/public;
        index index.php index.html;

        # Logs dentro del contenedor
        access_log /var/log/nginx/laravel_access.log;
        error_log  /var/log/nginx/laravel_error.log;

        # Tamaño máximo de carga
        client_max_body_size 50M;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include        fastcgi_params;
            fastcgi_read_timeout 60;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}
EOF

# ── Configurar Supervisor ────────────────────────────────────────────────────
RUN mkdir -p /etc/supervisor.d/

RUN cat > /etc/supervisor.d/laravel.ini << 'EOF'
[supervisord]
nodaemon=true
logfile=/var/log/supervisor/supervisord.log
logfile_maxbytes=50MB
loglevel=info

[program:php-fpm]
command=/usr/local/sbin/php-fpm
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
EOF

# ── Configurar directorio de la aplicación ────────────────────────────────────
WORKDIR /var/www/html

# Copiar el código fuente desde la etapa builder
COPY --from=composer_builder /app .

# ── CRÍTICO: El certificado CA debe estar en la imagen ───────────────────────
# Ya se copió en la línea anterior porque está en storage/ssl/
# Verificamos que existe:
RUN test -f /var/www/html/storage/ssl/isrgrootx1.pem \
    && echo "✅ Certificado SSL CA encontrado" \
    || (echo "❌ ERROR: Certificado SSL CA no encontrado en storage/ssl/" && exit 1)

# Configurar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    # El certificado debe ser legible por el proceso web
    && chmod 644 /var/www/html/storage/ssl/isrgrootx1.pem

# Crear directorios necesarios de Laravel
RUN mkdir -p storage/framework/sessions \
              storage/framework/views \
              storage/framework/cache \
              storage/logs \
    && chmod -R 775 storage \
    && chown -R www-data:www-data storage

# ── Script de inicio ──────────────────────────────────────────────────────────
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto que usa Nginx
EXPOSE 8080

# Ejecutar el script de inicio al arrancar el contenedor
ENTRYPOINT ["/entrypoint.sh"]
```

### 8.3 Crear el script de inicio `docker/entrypoint.sh`

```bash
mkdir -p docker
```

Crea el archivo `docker/entrypoint.sh`:

```bash
#!/bin/bash
set -e

echo "═══════════════════════════════════════════"
echo "   Iniciando Laravel + TiDB Cloud"
echo "═══════════════════════════════════════════"

# ── 1. Limpiar caché de configuración vieja ───────────────────────────────────
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# ── 2. Verificar que el certificado CA existe ─────────────────────────────────
if [ -z "$DB_SSL_CA" ]; then
    echo "⚠️  ADVERTENCIA: DB_SSL_CA no está definida"
elif [ ! -f "$DB_SSL_CA" ]; then
    echo "❌ ERROR CRÍTICO: El certificado CA no existe en: $DB_SSL_CA"
    echo "   Asegúrate de que storage/ssl/isrgrootx1.pem esté en el repositorio"
    exit 1
else
    echo "✅ Certificado CA encontrado: $DB_SSL_CA"
fi

# ── 3. Verificar conexión a TiDB Cloud ───────────────────────────────────────
echo "🔌 Verificando conexión a TiDB Cloud..."
MAX_RETRIES=10
RETRY=0
until php artisan db:monitor 2>/dev/null || [ $RETRY -ge $MAX_RETRIES ]; do
    RETRY=$((RETRY+1))
    echo "   ⏳ Intento $RETRY/$MAX_RETRIES — esperando conexión..."
    sleep 3
done

if [ $RETRY -ge $MAX_RETRIES ]; then
    echo "❌ No se pudo conectar a TiDB Cloud después de $MAX_RETRIES intentos"
    echo "   Verifica: DB_HOST, DB_PORT, DB_SSL_CA, credenciales"
    # No salimos con error para que Render muestre los logs
fi

# ── 4. Ejecutar migraciones ───────────────────────────────────────────────────
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force --no-interaction

# ── 5. Optimizar para producción ─────────────────────────────────────────────
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Optimizando para producción..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
fi

# ── 6. Generar APP_KEY si no existe ──────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --force
fi

echo "✅ Aplicación lista. Iniciando Nginx + PHP-FPM..."
echo "═══════════════════════════════════════════"

# Iniciar Supervisor (que gestiona Nginx + PHP-FPM)
exec /usr/bin/supervisord -c /etc/supervisor.d/laravel.ini
```

---

## 9. Configuración de `docker-compose.yml`

El archivo `docker-compose.yml` es para **desarrollo local**. En Render no se usa (Render usa el `Dockerfile` directamente).

### 9.1 Archivo `docker-compose.yml` completo

```yaml
# docker-compose.yml
# Para desarrollo local únicamente
# En producción (Render) este archivo NO se utiliza

version: '3.9'

services:
  # ── Aplicación Laravel ──────────────────────────────────────────────────────
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel_tidb_app

    # Puerto: tu_máquina:dentro_del_contenedor
    ports:
      - "8000:8080"

    # Variables de entorno para desarrollo local
    # Puedes usar env_file o definirlas directamente aquí
    env_file:
      - .env

    # Sobrescribir variables específicas para el entorno Docker
    environment:
      APP_ENV: local
      APP_DEBUG: "true"
      # CRÍTICO: La ruta al certificado DENTRO del contenedor
      DB_SSL_CA: /var/www/html/storage/ssl/isrgrootx1.pem

    # Volúmenes para desarrollo (cambios en código se reflejan sin rebuilding)
    volumes:
      # Montar código fuente (excepto vendor y storage para no sobreescribir)
      - ./app:/var/www/html/app
      - ./config:/var/www/html/config
      - ./database:/var/www/html/database
      - ./resources:/var/www/html/resources
      - ./routes:/var/www/html/routes
      - ./public:/var/www/html/public
      - ./storage/ssl:/var/www/html/storage/ssl    # CRÍTICO: certificado CA
      - ./storage/logs:/var/www/html/storage/logs  # Logs persistentes

    # Política de reinicio
    restart: unless-stopped

    # Healthcheck para verificar que la app responde
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8080"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

    # Red interna
    networks:
      - laravel_network

# ── Redes ────────────────────────────────────────────────────────────────────
networks:
  laravel_network:
    driver: bridge
```

> **Nota:** No incluimos un servicio de MySQL local porque usamos TiDB Cloud (base de datos en la nube). El contenedor de Laravel se conecta directamente a TiDB Cloud a través de internet.

---

## 10. Variables de entorno dentro de Docker

### 10.1 Cómo fluyen las variables de entorno

```
┌─────────────────────────────────────────────────────────────┐
│                    FLUJO DE VARIABLES                        │
│                                                             │
│  Desarrollo Local:                                          │
│  .env ──────────────────────────→ Contenedor Docker         │
│                                   (via env_file en compose) │
│                                                             │
│  Producción (Render):                                       │
│  Variables en Render Dashboard ──→ Contenedor Docker        │
│                                   (inyectadas por Render)   │
└─────────────────────────────────────────────────────────────┘
```

### 10.2 La variable más crítica: `DB_SSL_CA`

Esta variable debe apuntar a la ruta del certificado **dentro del contenedor**, no en tu máquina:

| Contexto | Valor de `DB_SSL_CA` |
|----------|---------------------|
| Máquina local (sin Docker) | `/home/tuusuario/proyecto/storage/ssl/isrgrootx1.pem` |
| Dentro del contenedor Docker | `/var/www/html/storage/ssl/isrgrootx1.pem` |
| En Render | `/var/www/html/storage/ssl/isrgrootx1.pem` |

> En Docker y Render siempre es `/var/www/html/storage/ssl/isrgrootx1.pem` porque el `WORKDIR` del Dockerfile es `/var/www/html`.

### 10.3 Verificar que las variables llegan al contenedor

```bash
# Con docker-compose
docker-compose exec app env | grep DB_

# Con docker run directamente
docker exec laravel_tidb_app env | grep DB_

# Salida esperada:
# DB_CONNECTION=mysql
# DB_HOST=gateway01.us-east-1.prod.aws.tidbcloud.com
# DB_PORT=4000
# DB_DATABASE=laravel_db
# DB_USERNAME=laravel_user
# DB_PASSWORD=TuPasswordSegura123!
# DB_SSL_CA=/var/www/html/storage/ssl/isrgrootx1.pem
```

### 10.4 Verificar que el certificado está dentro del contenedor

```bash
docker-compose exec app ls -la /var/www/html/storage/ssl/
# Debe mostrar: isrgrootx1.pem

docker-compose exec app cat /var/www/html/storage/ssl/isrgrootx1.pem | head -3
# Debe mostrar: -----BEGIN CERTIFICATE-----
```

---

## 11. Probar la conexión desde el contenedor

### 11.1 Construir y levantar el contenedor

```bash
# Construir la imagen (la primera vez tarda varios minutos)
docker-compose build --no-cache

# Levantar el contenedor en segundo plano
docker-compose up -d

# Ver los logs en tiempo real
docker-compose logs -f app
```

### 11.2 Ejecutar el comando de prueba dentro del contenedor

```bash
# Acceder al shell del contenedor
docker-compose exec app bash

# Dentro del contenedor, ejecutar:
php artisan tidb:test
```

O directamente sin entrar al shell:

```bash
docker-compose exec app php artisan tidb:test
```

### 11.3 Probar la conexión con MySQL CLI (diagnóstico)

```bash
# Instalar mysql-client temporalmente en el contenedor
docker-compose exec app sh -c "apk add --no-cache mysql-client"

# Conectar a TiDB Cloud con SSL
docker-compose exec app mysql \
    --host="$DB_HOST" \
    --port=4000 \
    --user="$DB_USERNAME" \
    --password="$DB_PASSWORD" \
    --ssl-ca=/var/www/html/storage/ssl/isrgrootx1.pem \
    --ssl-mode=REQUIRED \
    --execute="SELECT VERSION(), @@hostname;"
```

### 11.4 Verificar que PHP tiene PDO MySQL disponible

```bash
docker-compose exec app php -m | grep -i pdo
# Debe mostrar:
# PDO
# pdo_mysql
```

### 11.5 Probar migraciones desde el contenedor

```bash
# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Ver estado de migraciones
docker-compose exec app php artisan migrate:status

# Ver las tablas creadas en TiDB Cloud
docker-compose exec app php artisan db:show
```

---

## 12. Solución del error "insecure transport"

### 12.1 El error completo

```
SQLSTATE[HY000] [2026] SSL connection error:
Connections using insecure transport are prohibited while --require_secure_transport=ON.
```

Este error ocurre cuando TiDB Cloud rechaza la conexión porque no se está usando SSL.

### 12.2 Causas y soluciones

**Causa 1: `DB_SSL_CA` no está configurada**

```bash
# Diagnóstico
php artisan tinker --execute="echo env('DB_SSL_CA');"
# Si no muestra nada → no está configurada
```

Solución: Agregar al `.env`:
```dotenv
DB_SSL_CA=/var/www/html/storage/ssl/isrgrootx1.pem
```

---

**Causa 2: El certificado no existe en la ruta especificada**

```bash
# Verificar desde dentro del contenedor
docker-compose exec app ls -la /var/www/html/storage/ssl/
```

Si no existe:
```bash
# Verificar que el archivo está en el repositorio (no en .gitignore)
git ls-files storage/ssl/
# Debe mostrar: storage/ssl/isrgrootx1.pem
```

Si no aparece, agregarlo:
```bash
git add -f storage/ssl/isrgrootx1.pem
git commit -m "feat: add TiDB Cloud SSL CA certificate"
```

---

**Causa 3: `config/database.php` no pasa las opciones SSL a PDO**

Verifica que la sección `options` en `config/database.php` tenga:

```php
'options' => extension_loaded('pdo_mysql') ? array_filter([
    PDO::MYSQL_ATTR_SSL_CA                 => env('DB_SSL_CA'),
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
]) : [],
```

Luego limpia la caché de configuración:
```bash
php artisan config:clear
php artisan config:cache
```

---

**Causa 4: La caché de configuración de Laravel tiene valores viejos**

```bash
# Limpiar TODA la caché
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Verificar la configuración actual cargada
php artisan tinker --execute="print_r(config('database.connections.mysql.options'));"
```

---

**Causa 5: El certificado CA incorrecto o expirado**

```bash
# Verificar validez del certificado
openssl x509 -in storage/ssl/isrgrootx1.pem -text -noout | grep -E "Subject|Not After"
```

Si está expirado, descarga uno nuevo:
```bash
curl -o storage/ssl/isrgrootx1.pem https://letsencrypt.org/certs/isrgrootx1.pem
```

---

**Causa 6: `PDO::MYSQL_ATTR_SSL_CA` no es reconocida**

Esto pasa cuando `pdo_mysql` no está instalado:

```bash
php -m | grep pdo_mysql
# Si no aparece, instalar en el sistema:
# Ubuntu/Debian: sudo apt-get install php8.3-mysql
# Alpine (Docker): apk add php83-pdo_mysql
```

---

## 13. Preparación para producción

### 13.1 Verificar el archivo `.env.example`

El archivo `.env.example` **sí se sube a Git** y sirve como plantilla. Actualízalo con los nombres de variables (sin valores reales):

```dotenv
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=4000
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
DB_SSL_CA=/var/www/html/storage/ssl/isrgrootx1.pem
DB_SSL_MODE=REQUIRED

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 13.2 Optimizaciones de producción en Laravel

```bash
# En producción (Render ejecutará esto en el entrypoint)
php artisan config:cache    # Cachea config/
php artisan route:cache     # Cachea rutas
php artisan view:cache      # Cachea vistas Blade
php artisan event:cache     # Cachea eventos
php artisan optimize        # Ejecuta todo lo anterior

# NOTA: No ejecutar config:cache en desarrollo, solo en producción
# En desarrollo: php artisan config:clear (para que .env se lea siempre)
```

### 13.3 Asegurarse de que el `composer.lock` está actualizado

```bash
# Instalar solo dependencias de producción (sin --dev)
composer install --no-dev --optimize-autoloader

# Comprometer el lockfile
git add composer.lock
git commit -m "chore: update composer.lock for production"
```

### 13.4 Verificar el `Dockerfile` para producción

El `Dockerfile` que creamos ya está configurado para producción:
- Usa `composer install --no-dev` (sin dependencias de desarrollo)
- Instala solo las extensiones PHP necesarias
- Configura OPcache
- El `entrypoint.sh` ejecuta `config:cache`, `route:cache`, etc. cuando `APP_ENV=production`

### 13.5 Subir todo a GitHub/GitLab

```bash
# Verificar qué archivos van al repositorio
git status

# Los archivos críticos que DEBEN estar en Git:
# ✅ Dockerfile
# ✅ docker-compose.yml
# ✅ docker/entrypoint.sh
# ✅ storage/ssl/isrgrootx1.pem
# ✅ config/database.php
# ✅ .env.example
# ✅ composer.lock

# Los archivos que NO deben estar en Git:
# ❌ .env  (contiene credenciales reales)
# ❌ vendor/ (se instala con composer)
# ❌ storage/logs/ (logs locales)

git add .
git commit -m "feat: configure TiDB Cloud SSL connection for Docker + Render"
git push origin main
```

---

## 14. Configuración completa en Render

### 14.1 Crear una cuenta en Render

1. Ve a [https://render.com](https://render.com)
2. Haz clic en **"Get Started for Free"**
3. Regístrate con GitHub (recomendado para integración directa) o con email
4. Verifica tu correo

### 14.2 Conectar tu repositorio

1. En el dashboard de Render, haz clic en **"New +"** → **"Web Service"**
2. Selecciona **"Build and deploy from a Git repository"**
3. Conecta tu cuenta de GitHub/GitLab si no lo has hecho
4. Busca y selecciona tu repositorio Laravel
5. Haz clic en **"Connect"**

### 14.3 Configurar el servicio web

En la pantalla de configuración del servicio:

**Sección "Settings":**

| Campo | Valor |
|-------|-------|
| **Name** | `laravel-tidb-app` (o el nombre que prefieras) |
| **Region** | Elige la más cercana a tu región de TiDB Cloud |
| **Branch** | `main` (o `master`) |
| **Root Directory** | *(dejar vacío si el `Dockerfile` está en la raíz)* |
| **Runtime** | **Docker** ← MUY IMPORTANTE |
| **Dockerfile Path** | `./Dockerfile` |
| **Docker Context** | `.` |
| **Instance Type** | `Free` para empezar (o `Starter` para producción) |

**Sección "Build & Deploy":**

| Campo | Valor |
|-------|-------|
| **Build Command** | *(dejar vacío — Docker maneja el build)* |
| **Start Command** | *(dejar vacío — el `ENTRYPOINT` del Dockerfile lo maneja)* |

> Con Docker en Render, el `Dockerfile` define todo. Render simplemente hace `docker build` y `docker run`.

### 14.4 Configurar las variables de entorno en Render

En la sección **"Environment"** de tu servicio en Render, agrega **cada una** de estas variables:

```
APP_NAME            = Mi App Laravel
APP_ENV             = production
APP_KEY             = (generar con: php artisan key:generate --show)
APP_DEBUG           = false
APP_URL             = https://laravel-tidb-app.onrender.com

LOG_CHANNEL         = stack
LOG_LEVEL           = error

DB_CONNECTION       = mysql
DB_HOST             = gateway01.us-east-1.prod.aws.tidbcloud.com
DB_PORT             = 4000
DB_DATABASE         = laravel_db
DB_USERNAME         = laravel_user
DB_PASSWORD         = TuPasswordSegura123!
DB_SSL_CA           = /var/www/html/storage/ssl/isrgrootx1.pem
DB_SSL_MODE         = REQUIRED

CACHE_STORE         = file
SESSION_DRIVER      = file
SESSION_LIFETIME    = 120
QUEUE_CONNECTION    = sync
```

**Cómo agregar variables en Render:**
1. En la pestaña **"Environment"** de tu servicio
2. Haz clic en **"Add Environment Variable"**
3. Escribe el nombre en **"Key"** y el valor en **"Value"**
4. Repite para cada variable
5. Haz clic en **"Save Changes"** al final

> **Importante:** Render inyecta estas variables como variables de entorno del sistema operativo dentro del contenedor Docker. Laravel las lee a través de `env()` normalmente.

### 14.5 Generar la APP_KEY para producción

En tu máquina local:

```bash
php artisan key:generate --show
# Output: base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=
```

Copia ese valor completo (incluyendo `base64:`) y ponlo en la variable `APP_KEY` en Render.

### 14.6 Configurar el puerto en Render

Render detecta automáticamente el puerto si usas la variable de entorno `PORT`. Nuestro Dockerfile ya usa el puerto 8080 en Nginx, que es el estándar de Render para contenedores Docker.

Si Render no detecta el puerto automáticamente:
1. En la configuración del servicio, busca **"Port"**
2. Establece el valor en `8080`

### 14.7 Desplegar la aplicación

1. Haz clic en **"Create Web Service"**
2. Render comenzará el proceso de build automáticamente
3. Puedes ver los logs en tiempo real en la pestaña **"Logs"**
4. El primer deploy puede tardar 5-10 minutos

### 14.8 Logs durante el deploy

Durante el deploy verás algo como:

```
==> Building Docker image...
Step 1/20 : FROM composer:2.7 AS composer_builder
...
==> Starting container...
═══════════════════════════════════════════
   Iniciando Laravel + TiDB Cloud
═══════════════════════════════════════════
🧹 Limpiando caché...
✅ Certificado CA encontrado: /var/www/html/storage/ssl/isrgrootx1.pem
🔌 Verificando conexión a TiDB Cloud...
🗄️  Ejecutando migraciones...
⚡ Optimizando para producción...
✅ Aplicación lista. Iniciando Nginx + PHP-FPM...
==> Your service is live 🎉
```

---

## 15. Pruebas finales en producción

### 15.1 Verificar que el sitio responde

```bash
# Reemplaza con tu URL de Render
curl -I https://laravel-tidb-app.onrender.com
# Debe responder: HTTP/2 200
```

### 15.2 Ejecutar el comando de prueba en Render

En el dashboard de Render:
1. Ve a tu servicio
2. Haz clic en la pestaña **"Shell"** (disponible en planes de pago) o usa la API de Render

**Alternativa: Crear una ruta de prueba temporalmente**

Crea una ruta en `routes/web.php` para verificar la conexión (recuerda eliminarla después):

```php
// routes/web.php - SOLO PARA PRUEBA, ELIMINAR DESPUÉS
Route::get('/test-db', function () {
    try {
        // Prueba conexión básica
        $pdo = DB::connection()->getPdo();

        // Verificar SSL
        $sslStatus = DB::select("SHOW STATUS LIKE 'Ssl_cipher'");
        $cipher = $sslStatus[0]->Value ?? 'Sin cifrado';

        // Versión de TiDB
        $version = DB::select('SELECT VERSION() as v')[0]->v;

        // Prueba Eloquent
        $count = \App\Models\TestConnection::count();

        return response()->json([
            'status'       => '✅ Conexión exitosa',
            'driver'       => 'MySQL/TiDB',
            'version'      => $version,
            'ssl_cipher'   => $cipher,
            'ssl_activo'   => !empty($cipher),
            'app_env'      => app()->environment(),
            'host'         => config('database.connections.mysql.host'),
            'registros_db' => $count,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => '❌ Error de conexión',
            'error'  => $e->getMessage(),
        ], 500);
    }
})->name('test.db');
```

Accede a: `https://laravel-tidb-app.onrender.com/test-db`

**Respuesta esperada:**
```json
{
  "status": "✅ Conexión exitosa",
  "driver": "MySQL/TiDB",
  "version": "8.1.0-TiDB-v7.5.3",
  "ssl_cipher": "TLS_AES_256_GCM_SHA384",
  "ssl_activo": true,
  "app_env": "production",
  "host": "gateway01.us-east-1.prod.aws.tidbcloud.com",
  "registros_db": 0
}
```

> ⚠️ **Elimina esta ruta después de verificar.** Exponer información del servidor en producción es un riesgo de seguridad.

### 15.3 Verificar los logs en Render

En el dashboard de Render → tu servicio → pestaña **"Logs"**:

```bash
# Deberías ver en los logs:
[2024-01-01 00:00:00] production.INFO: Conexión a TiDB Cloud establecida
```

### 15.4 Probar con datos reales usando Eloquent

Si tienes una migración real con un modelo (ej. `User`):

```bash
# En Render Shell (planes de pago) o creando un endpoint temporal:
php artisan tinker
>>> User::count()
=> 0
>>> User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => bcrypt('test')])
=> App\Models\User {...}
>>> User::first()
=> App\Models\User {...}
```

---

## 16. Errores comunes y soluciones

### Error 1: "SSL connection error: Connections using insecure transport are prohibited"

```
SQLSTATE[HY000] [2026] SSL connection error
```

**Causa:** No se está enviando el certificado CA o SSL no está configurado.

**Solución:**
```bash
# 1. Verificar que DB_SSL_CA está definida
php artisan tinker --execute="echo env('DB_SSL_CA');"

# 2. Verificar que el archivo existe
ls -la storage/ssl/isrgrootx1.pem

# 3. Limpiar caché de configuración
php artisan config:clear

# 4. Verificar config/database.php
php artisan tinker --execute="print_r(config('database.connections.mysql.options'));"
# Debe mostrar PDO::MYSQL_ATTR_SSL_CA con la ruta correcta
```

---

### Error 2: "could not find driver"

```
SQLSTATE[HY000] [2002] No such file or directory
o
could not find driver (SQL: select ...)
```

**Causa:** La extensión `pdo_mysql` no está instalada en PHP.

**Solución en el Dockerfile:**
```dockerfile
RUN docker-php-ext-install pdo pdo_mysql
```

Verificar:
```bash
docker-compose exec app php -m | grep pdo
# Debe mostrar: PDO y pdo_mysql
```

---

### Error 3: "Connection refused" al conectar al host de TiDB

```
SQLSTATE[HY000] [2002] Connection refused
o
Connection timed out
```

**Causas posibles:**

a) El host o puerto es incorrecto:
```bash
# Verificar conectividad desde el contenedor
docker-compose exec app curl -v telnet://gateway01.us-east-1.prod.aws.tidbcloud.com:4000
```

b) El cluster de TiDB está pausado (TiDB Serverless pausa automáticamente tras inactividad):
→ Ve al dashboard de TiDB Cloud y verifica que el cluster esté `Active`. Si dice `Paused`, haz clic en **"Resume"**.

c) El firewall de TiDB Cloud bloquea la IP:
→ En TiDB Cloud → tu cluster → `Security` → verifica que **"Allow All"** está activado o agrega la IP de Render a la lista blanca.

---

### Error 4: "Access denied for user"

```
SQLSTATE[HY000] [1045] Access denied for user 'laravel_user'@'...' (using password: YES)
```

**Causa:** Usuario o contraseña incorrectos.

**Solución:**
```bash
# Verificar las credenciales
php artisan tinker --execute="
echo 'Host: ' . config('database.connections.mysql.host') . PHP_EOL;
echo 'User: ' . config('database.connections.mysql.username') . PHP_EOL;
echo 'DB: '   . config('database.connections.mysql.database') . PHP_EOL;
"

# Resetear la contraseña en TiDB Cloud:
# 1. Ve al SQL Editor de TiDB Cloud
# 2. ALTER USER 'laravel_user'@'%' IDENTIFIED BY 'NuevaPassword123!';
# 3. Actualiza DB_PASSWORD en .env y en Render
```

---

### Error 5: "Table doesn't exist"

```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'laravel_db.users' doesn't exist
```

**Causa:** Las migraciones no se han ejecutado.

**Solución:**
```bash
php artisan migrate

# Verificar estado
php artisan migrate:status
```

---

### Error 6: "Allowed memory size exhausted"

```
PHP Fatal error: Allowed memory size of 134217728 bytes exhausted
```

**Solución en `Dockerfile` o `php.ini`:**
```dockerfile
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/laravel.ini
```

---

### Error 7: "No application encryption key has been specified"

```
RuntimeException: No application encryption key has been specified.
```

**Causa:** `APP_KEY` está vacía.

**Solución:**
```bash
# Generar la key
php artisan key:generate --show
# Copia el output y ponlo en Render como variable APP_KEY
```

---

### Error 8: "The stream or file could not be opened" (logs)

```
Unable to create configured logger. [...] The stream or file could not be opened.
```

**Causa:** Los directorios `storage/logs/` no existen o no tienen permisos.

**Solución en `Dockerfile`:**
```dockerfile
RUN mkdir -p storage/logs \
    && chmod -R 775 storage \
    && chown -R www-data:www-data storage
```

---

### Error 9: El certificado CA no se encuentra en el contenedor de Render

```
❌ ERROR CRÍTICO: El certificado CA no existe en: /var/www/html/storage/ssl/isrgrootx1.pem
```

**Causa:** El archivo `storage/ssl/isrgrootx1.pem` está en `.gitignore` o no fue añadido a Git.

**Solución:**
```bash
# Verificar que está en Git
git ls-files storage/ssl/isrgrootx1.pem
# Si no aparece nada:
git add -f storage/ssl/isrgrootx1.pem
git commit -m "fix: add TiDB SSL CA certificate"
git push origin main
```

---

### Error 10: TiDB Serverless — "Invalid username/password" después de resetear

TiDB Serverless requiere que el nombre de usuario tenga el prefijo del cluster. Por ejemplo:

```
✅ Correcto: 2abc123def.laravel_user   (con prefijo del cluster)
❌ Incorrecto: laravel_user            (sin prefijo)
```

En el panel de conexión de TiDB Cloud, el usuario ya viene con el prefijo incluido. Asegúrate de copiar el usuario completo.

---

## Resumen de archivos creados

```
proyecto/
├── Dockerfile                          ← Build de la imagen
├── docker-compose.yml                  ← Para desarrollo local
├── docker/
│   └── entrypoint.sh                   ← Script de inicio del contenedor
├── storage/
│   └── ssl/
│       └── isrgrootx1.pem             ← Certificado CA de TiDB (en Git)
├── config/
│   └── database.php                   ← Configuración con SSL para Eloquent
├── .env                               ← Variables locales (NO en Git)
├── .env.example                       ← Plantilla (SÍ en Git)
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── TestTiDBConnection.php ← Comando de prueba
│   └── Models/
│       └── TestConnection.php         ← Modelo de prueba
└── database/
    └── migrations/
        └── xxxx_create_test_connection_table.php
```

---

## Checklist final

### Desarrollo local
- [ ] Cluster TiDB Cloud creado y activo
- [ ] Base de datos `laravel_db` creada
- [ ] Usuario `laravel_user` creado con permisos
- [ ] Certificado CA descargado en `storage/ssl/isrgrootx1.pem`
- [ ] `.env` configurado con todas las variables (incluyendo `DB_SSL_CA`)
- [ ] `config/database.php` con opciones SSL en PDO
- [ ] `php artisan migrate` ejecutado exitosamente
- [ ] `php artisan tidb:test` pasa todas las pruebas
- [ ] Contenedor Docker construido sin errores
- [ ] `docker-compose up -d` → app responde en `http://localhost:8000`
- [ ] Pruebas Eloquent desde el contenedor exitosas

### Producción (Render)
- [ ] Repositorio en GitHub/GitLab actualizado (con certificado CA)
- [ ] Servicio Web creado en Render con runtime **Docker**
- [ ] Todas las variables de entorno configuradas en Render
- [ ] `APP_KEY` generada y configurada
- [ ] `DB_SSL_CA` apunta a `/var/www/html/storage/ssl/isrgrootx1.pem`
- [ ] Deploy completado sin errores en los logs
- [ ] Endpoint de prueba responde correctamente con SSL activo
- [ ] Endpoint de prueba eliminado después de verificar

---

*Guía creada para: Laravel 11 · TiDB Cloud Serverless · PHP 8.3 · Docker · Render · Mayo 2025*
