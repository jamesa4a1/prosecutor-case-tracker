FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    build-essential \
    libssl-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    zip \
    && docker-php-ext-enable pdo_mysql mysqli zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and NPM
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

# Set working directory
WORKDIR /app

# Copy the application
COPY . /app

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist

# Install Node dependencies
RUN npm ci

# Build frontend assets
RUN npm run build

# Create necessary directories
RUN mkdir -p storage/logs && chmod -R 777 storage bootstrap/cache

# Expose port
EXPOSE 8000

# Run the development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
