FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set working directory and document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Copy project
COPY . /var/www/html
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install && \
    mv composer.phar /usr/local/bin/composer
