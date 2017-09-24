FROM php:7.1.9-apache

RUN apt-get update

# Required php extensions
RUN docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Required for downloading zip packages with Composer
RUN apt-get install -y zlib1g-dev
RUN docker-php-ext-install zip

# Install Vim
RUN apt-get install -y vim

# Change Apache root path
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/conf-available/*.conf

# Activate Apache mod_rewrite
RUN a2enmod rewrite
# Replace apache2.conf with custom
COPY apache2.conf /etc/apache2/apache2.conf