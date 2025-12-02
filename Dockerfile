# Diabetes Prediction System - Dockerfile
# PHP 8.2 + Python 3.10 + Apache + MySQL

# Stage 1: Base PHP with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies including Python build tools
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    default-mysql-client \
    python3 \
    python3-pip \
    python3-venv \
    python3-dev \
    build-essential \
    gcc \
    g++ \
    make \
    pkg-config \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install numerical libraries for Python ML (use minimal set)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libblas-dev \
    liblapack-dev \
    gfortran \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    opcache

# Install and configure Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Check Python version and install compatible ML packages
RUN python3 --version && \
    pip3 install --no-cache-dir --upgrade pip setuptools wheel && \
    pip3 install --no-cache-dir \
    numpy \
    pandas \
    scikit-learn \
    joblib

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy Apache configuration
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/writable

# Create Python virtual environment for ML
RUN python3 -m venv /opt/ml-venv \
    && /opt/ml-venv/bin/pip install --no-cache-dir --upgrade pip setuptools wheel \
    && /opt/ml-venv/bin/pip install --no-cache-dir \
        numpy \
        pandas \
        scikit-learn \
        joblib

# Copy Python ML files
COPY app/Python /var/www/html/app/Python

# Set environment variables
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV PYTHONPATH /opt/ml-venv/bin/python

# Update Apache configuration
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Start Apache
CMD ["apache2-foreground"]
