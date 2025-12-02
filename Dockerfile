# Diabetes Prediction System - Dockerfile
# PHP 8.2 + Python 3.10 + Apache + MySQL

# Stage 1: Build Python ML environment
FROM python:3.10-slim as python-ml

# Install Python ML dependencies
RUN pip install --no-cache-dir \
    numpy==1.23.5 \
    pandas==1.5.3 \
    scikit-learn==1.2.2 \
    joblib==1.2.0

# Create a virtual environment and copy it
RUN python -m venv /opt/ml-venv && \
    /opt/ml-venv/bin/pip install --no-cache-dir \
    numpy==1.23.5 \
    pandas==1.5.3 \
    scikit-learn==1.2.2 \
    joblib==1.2.0

# Stage 2: PHP Application
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
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
    python3-venv \
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

# Copy Python ML environment from first stage
COPY --from=python-ml /opt/ml-venv /opt/ml-venv
COPY --from=python-ml /usr/local/lib/python3.10/site-packages /usr/local/lib/python3.10/site-packages

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

# Create symlink for Python
RUN ln -sf /opt/ml-venv/bin/python3 /usr/local/bin/ml-python

# Copy Python ML files
COPY app/Python /var/www/html/app/Python

# Set environment variables
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV PYTHONPATH /opt/ml-venv/lib/python3.10/site-packages
ENV PATH="/opt/ml-venv/bin:${PATH}"

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
