FROM php:8.2-apache

# Set timezone to Asia/Jakarta
ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        gd \
        zip \
        mbstring \
        xml \
        intl \
        opcache \
    && a2enmod rewrite headers \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP custom configuration for CBT, mass file uploads & high-concurrency exam sessions
RUN { \
    echo "date.timezone = Asia/Jakarta"; \
    echo "upload_max_filesize = 64M"; \
    echo "post_max_size = 64M"; \
    echo "max_file_uploads = 60"; \
    echo "max_input_vars = 5000"; \
    echo "memory_limit = 256M"; \
    echo "max_execution_time = 300"; \
    echo "opcache.enable = 1"; \
    echo "opcache.memory_consumption = 128"; \
    echo "opcache.interned_strings_buffer = 16"; \
    echo "opcache.max_accelerated_files = 10000"; \
    echo "opcache.revalidate_freq = 2"; \
} > /usr/local/etc/php/conf.d/zyacbt.ini

# Tune Apache MPM Prefork to handle 250+ simultaneous student exam requests
RUN { \
    echo "<IfModule mpm_prefork_module>"; \
    echo "    StartServers             8"; \
    echo "    MinSpareServers          8"; \
    echo "    MaxSpareServers         20"; \
    echo "    ServerLimit            256"; \
    echo "    MaxRequestWorkers      250"; \
    echo "    MaxConnectionsPerChild 2000"; \
    echo "</IfModule>"; \
} > /etc/apache2/mods-available/mpm_prefork.conf \
    && mkdir -p /var/www/html/uploads /var/www/html/public/uploads/batch_tmp /var/www/html/application/cache /var/www/html/application/logs \
    && chown -R www-data:www-data /var/www/html/uploads /var/www/html/public/uploads /var/www/html/application/cache /var/www/html/application/logs

WORKDIR /var/www/html
