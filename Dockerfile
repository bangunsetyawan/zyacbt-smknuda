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

# PHP custom configuration for CBT & file uploads
RUN { \
    echo "date.timezone = Asia/Jakarta"; \
    echo "upload_max_filesize = 64M"; \
    echo "post_max_size = 64M"; \
    echo "memory_limit = 256M"; \
    echo "max_execution_time = 300"; \
} > /usr/local/etc/php/conf.d/zyacbt.ini

WORKDIR /var/www/html
