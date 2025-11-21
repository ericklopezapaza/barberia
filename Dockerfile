# Usa PHP 8.3 FPM
FROM php:8.3-fpm

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia tu proyecto al contenedor
WORKDIR /var/www/html
COPY . .

# Instala dependencias de Laravel
RUN composer install --no-interaction

# Expone el puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando para ejecutar PHP-FPM
CMD ["php-fpm"]
