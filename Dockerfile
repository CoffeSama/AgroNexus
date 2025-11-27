# Imagen base con PHP 8.2 CLI
FROM php:8.2-cli

# Instalar dependencias del sistema y extensiones requeridas
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install pgsql pdo_pgsql mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer (desde la imagen oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiar el código de la app
COPY . /app

# Instalar dependencias de Laravel sin dev
RUN composer install --no-dev --optimize-autoloader

# Dar permisos de escritura a storage y cache (si falla, que no rompa el build)
RUN chmod -R 775 storage bootstrap/cache || true

# Comando de arranque del contenedor
CMD php artisan serve --host=0.0.0.0 --port=$PORT