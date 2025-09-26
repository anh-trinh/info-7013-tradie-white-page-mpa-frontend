FROM php:8.1-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install system dependencies & build tools needed for extensions
RUN apk add --no-cache $PHPIZE_DEPS \
    libpng libpng-dev \
    libzip-dev zip unzip \
    git bash curl \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /tmp/pear

# Copy composer from official image (lighter than installing manually)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first for better layer caching
COPY composer.json composer.lock* ./

# Install PHP dependencies (no-dev can be toggled via build arg)
ARG COMPOSER_NO_DEV=0
RUN if [ "$COMPOSER_NO_DEV" = "1" ]; then \
      composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader; \
    else \
      composer install --no-interaction --prefer-dist --optimize-autoloader; \
    fi

# Copy application source
COPY . .

# Ensure proper permissions for storage & cache directories
RUN chown -R www-data:www-data /var/www \
    && find storage -type d -exec chmod 775 {} \; || true \
    && find bootstrap/cache -type d -exec chmod 775 {} \; || true

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
