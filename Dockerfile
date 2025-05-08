FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./composer.json .
COPY ./composer.lock .
RUN composer install

COPY . .

RUN chmod +x ./entrypoint.sh

