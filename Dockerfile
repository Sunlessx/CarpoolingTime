FROM php:8.2-apache

# PDO MySQL pour Model.php
RUN docker-php-ext-install pdo_mysql

# le code est monté par docker-compose (volume), rien à copier ici
