# syntax=docker/dockerfile:1

# =============================================================================
# Image untuk aplikasi Laravel (loket-majuhardware)
# PHP 8.2 + Apache + PostgreSQL extensions + Composer + Node (Vite build)
# =============================================================================

FROM php:8.2-apache

# -----------------------------------------------------------------------------
# 1. System dependencies + PHP extensions
# -----------------------------------------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        unzip \
        zip \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libpq-dev \
        postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache (butuh untuk routing Laravel + .htaccess)
RUN a2enmod rewrite headers

# -----------------------------------------------------------------------------
# 2. Composer (dari image resmi)
# -----------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------------------------------------------------------
# 3. Node.js (untuk build asset Vite: npm install && npm run build)
# -----------------------------------------------------------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------------------------------
# 4. Konfigurasi Apache: DocumentRoot -> /var/www/html/public
# -----------------------------------------------------------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Izinkan .htaccess (AllowOverride All) di DocumentRoot
RUN printf '<Directory %s>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' "${APACHE_DOCUMENT_ROOT}" > /etc/apache2/conf-available/laravel-documentroot.conf \
    && a2enconf laravel-documentroot

# -----------------------------------------------------------------------------
# 5. Copy source code aplikasi
# -----------------------------------------------------------------------------
WORKDIR /var/www/html
COPY . .

# -----------------------------------------------------------------------------
# 6. Install dependency PHP & build asset frontend
# -----------------------------------------------------------------------------
# Dependency PHP (tanpa dev dependency untuk image lebih kecil)
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-progress

# Dependency JS + build (hasilnya ada di public/build)
RUN npm install --no-audit --no-fund \
    && npm run build \
    && rm -rf node_modules

# -----------------------------------------------------------------------------
# 7. Permission untuk storage & bootstrap/cache
# -----------------------------------------------------------------------------
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# -----------------------------------------------------------------------------
# 8. Entrypoint
# -----------------------------------------------------------------------------
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
