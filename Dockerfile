FROM php:8.4-cli-alpine

# System dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libzip \
    libpng \
    oniguruma \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    mysql-client

# Configure GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# PHP extensions (MySQL instead of Postgres)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    opcache \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy composer files (dependencies will be installed at runtime)
COPY composer.json composer.lock ./

# Copy the rest of the application
COPY . .

# Expose PHP development server port
EXPOSE 8000

# Default command (can be overridden by docker-compose)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
