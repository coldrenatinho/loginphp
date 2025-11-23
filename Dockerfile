FROM php:8.2-fpm

# Instala as extensões necessárias para o MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql