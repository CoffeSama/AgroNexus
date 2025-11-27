# Imagen base con PHP 8.2 CLI
FROM php:8.2-cli

# Instalar extensiones necesarias para PostgreSQL y demás
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer (copiado desde imagen oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiar todo el proyecto dentro del contenedor
COPY . /app

# Instalar dependencias de Laravel sin dev
RUN composer install --no-dev --optimize-autoloader

# Dar permisos de escritura a storage y cache
RUN chmod -R 775 storage bootstrap/cache || true

# Comando de arranque: levantar la API con el servidor embebido
CMD php artisan serve --host=0.0.0.0 --port=$PORT