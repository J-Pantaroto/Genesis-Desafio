FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    curl

RUN docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["sh", "-c", "php artisan migrate --force && php-fpm"]
