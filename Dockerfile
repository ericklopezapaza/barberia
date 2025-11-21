# PHP 8.3 FPM
FROM php:8.3-fpm

# Instala dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libonig-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring xml zip gd bcmath ctype json \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia todo el proyecto
COPY . .

# Asegura que exista .env
RUN cp .env.example .env || echo ".env already exists"

# Ajusta permisos de storage y bootstrap/cache antes de composer install
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Instala dependencias de Laravel
RUN composer install --no-interaction --optimize-autoloader --ignore-platform-reqs

# Expone el puerto para PHP-FPM
EXPOSE 9000

# Comando para ejecutar PHP-FPM
CMD ["php-fpm"]
