FROM php:8.2-cli

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip curl nodejs npm \
    libpng-dev libonig-dev libxml2-dev zip

# Extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar proyecto
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Permisos
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

# Migraciones + servidor
CMD php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=10000