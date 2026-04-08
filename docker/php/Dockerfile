FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    sqlite-dev \
    linux-headers \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite pcntl

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create database directory
RUN mkdir -p database

EXPOSE 9000

CMD ["php-fpm"]
