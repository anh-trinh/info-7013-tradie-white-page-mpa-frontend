FROM php:8.1-fpm-alpine

# Set working directory
WORKDIR /var/www

# Build args (can be overridden at build time)
ARG INSTALL_XDEBUG=0

# Install system dependencies & PHP extensions (skip Xdebug by default - PECL instability)
RUN set -eux; \
  apk update; \
  apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers libpng-dev libzip-dev; \
  apk add --no-cache git bash curl libpng libzip zip unzip; \
  docker-php-ext-install pdo pdo_mysql; \
  if [ "${INSTALL_XDEBUG:-0}" = "1" ]; then \
    echo 'Attempting Xdebug install (enabled via build arg INSTALL_XDEBUG=1)'; \
    if pecl install xdebug; then docker-php-ext-enable xdebug; else echo 'Xdebug install failed - continuing without it'; fi; \
  else \
    echo 'Skipping Xdebug (INSTALL_XDEBUG=0 or network issues)'; \
  fi; \
  apk del .build-deps || true; \
  rm -rf /tmp/pear /var/cache/apk/*

# Provide default (disabled) Xdebug config placeholder
COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy composer from official image (lighter than installing manually)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first for better layer caching
COPY composer.json composer.lock* ./

# Install PHP dependencies (no-dev can be toggled via build arg)
ARG COMPOSER_NO_DEV=0
RUN set -eux; \
    if [ "$COMPOSER_NO_DEV" = "1" ]; then \
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
