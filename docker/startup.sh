#!/bin/bash

set -e  # Exit on error

echo "=== Starting Diabetes Prediction System ==="
echo "Generating .env file from environment variables..."

# Generate a random encryption key if not provided
if [ -z "${ENCRYPTION_KEY}" ] || [ "${ENCRYPTION_KEY}" = "generate-secure-random-key-32-chars" ]; then
    ENCRYPTION_KEY=$(openssl rand -hex 32)
    echo "WARNING: Generated random encryption key. For production, set ENCRYPTION_KEY in environment variables."
fi

# Generate .env file from environment variables
cat > /var/www/html/.env <<EOF
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------

CI_ENVIRONMENT = ${CI_ENVIRONMENT:-production}

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------

app.baseURL = '${APP_BASE_URL:-https://pikoy.aventra.my.id}/'

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = ${DATABASE_HOST:-db}
database.default.database = ${DATABASE_NAME:-diabetes}
database.default.username = ${DATABASE_USERNAME:-diabetes}
database.default.password = '${DATABASE_PASSWORD:-leaveempty1}'
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = ${DATABASE_PORT:-3306}

#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------

session.driver = CodeIgniter\Session\Handlers\FileHandler
session.savePath = writable/session
session.matchIP = false
session.timeToUpdate = 300
session.regenerateDestroy = false

#--------------------------------------------------------------------
# SECURITY
#--------------------------------------------------------------------

encryption.key = '${ENCRYPTION_KEY}'
encryption.driver = OpenSSL

cookie.prefix = 
cookie.domain = 
cookie.path = /
cookie.secure = false
cookie.httpOnly = true
cookie.sameSite = Lax

#--------------------------------------------------------------------
# TOOLBAR
#--------------------------------------------------------------------

toolbar.enable = false
EOF

echo ".env file generated successfully"

# Display generated .env for debugging
echo "=== Generated .env content (first 10 lines) ==="
head -20 /var/www/html/.env
echo "=============================================="

echo "Setting permissions..."

# Set proper permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/writable

# Test PHP configuration
echo "Testing PHP configuration..."
php -v

# Test if we can access the spark file
echo "Testing spark file..."
if [ -f /var/www/html/spark ]; then
    echo "Spark file exists"
else
    echo "ERROR: Spark file not found!"
fi

# Change to application directory
cd /var/www/html

# Run database migrations if needed (disabled by default for now)
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running database migrations..."
    if php spark migrate --force; then
        echo "Database migrations completed successfully"
    else
        echo "WARNING: Database migrations failed, continuing anyway..."
    fi
fi

# Run database seeders if needed (disabled by default for now)
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Running database seeders..."
    if php spark db:seed PetugasSeeder; then
        echo "Database seeders completed successfully"
    else
        echo "WARNING: Database seeders failed, continuing anyway..."
    fi
fi

echo "Starting Apache..."
exec apache2-foreground
