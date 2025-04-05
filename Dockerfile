FROM php:7.4-apache

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


