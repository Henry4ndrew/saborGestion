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
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

# Copiar el resto del código fuente
COPY . .

# Generar el autoloader optimizado
RUN composer dump-autoload --optimize

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
# ── Copiar aplicación desde etapa anterior ───────────────────────────────
COPY --from=composer_builder /app /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage

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
