FROM php:8.1-fpm-alpine

# Set working directory
WORKDIR /var/www

# Build args (can be overridden at build time)
ARG INSTALL_XDEBUG=1
ARG XDEBUG_VERSION=3.3.2

# Install system dependencies & PHP extensions
# Make xdebug installation optional & resilient (pin version + fallback)
RUN set -eux; \
  apk update; \
  apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libpng-dev libzip-dev; \
  apk add --no-cache git bash curl libpng libzip zip unzip; \
  docker-php-ext-install pdo pdo_mysql; \
  if [ "${INSTALL_XDEBUG}" = "1" ]; then \
    (pecl channel-update pecl.php.net || true); \
    if ! pecl install xdebug-${XDEBUG_VERSION}; then \
      echo 'Falling back to latest xdebug'; \
      pecl install xdebug || pecl install xdebug-3.2.2; \
    fi; \
    docker-php-ext-enable xdebug; \
  else \
    echo 'Skipping Xdebug installation'; \
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
