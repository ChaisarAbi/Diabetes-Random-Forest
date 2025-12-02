#!/bin/bash

# Don't exit on error - we want to continue even if migrations fail
# set -e  # Exit on error

echo "=== Starting Diabetes Prediction System ==="
echo "Generating .env file from environment variables..."

# Generate a random encryption key if not provided
if [ -z "${ENCRYPTION_KEY}" ] || [ "${ENCRYPTION_KEY}" = "generate-secure-random-key-32-chars" ]; then
    # Try openssl first, then fallback to /dev/urandom
    if command -v openssl >/dev/null 2>&1; then
        ENCRYPTION_KEY=$(openssl rand -hex 32)
    else
        ENCRYPTION_KEY=$(head -c 32 /dev/urandom | base64 | tr -d '\n' | cut -c1-32)
    fi
    echo "WARNING: Generated random encryption key. For production, set ENCRYPTION_KEY in environment variables."
fi

# Debug: Show database environment variables
echo "=== Database Environment Variables ==="
echo "DATABASE_HOST: ${DATABASE_HOST:-not set}"
echo "MYSQL_HOST: ${MYSQL_HOST:-not set}"
echo "DATABASE_NAME: ${DATABASE_NAME:-not set}"
echo "DATABASE_USERNAME: ${DATABASE_USERNAME:-not set}"
echo "======================================"

# Determine database host - prefer MYSQL_HOST, then DATABASE_HOST, then default
if [ -n "${MYSQL_HOST}" ]; then
    DB_HOST="${MYSQL_HOST}"
elif [ -n "${DATABASE_HOST}" ] && [ "${DATABASE_HOST}" != "root" ]; then
    DB_HOST="${DATABASE_HOST}"
else
    DB_HOST="database-project-igd2an"
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

database.default.hostname = ${DB_HOST}
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

# Test database connection first
echo "Testing database connection to ${DB_HOST}:${DATABASE_PORT:-3306}..."
if php -r "
\$host = '${DB_HOST}';
\$port = ${DATABASE_PORT:-3306};
\$socket = fsockopen(\$host, \$port, \$errno, \$errstr, 5);
if (!\$socket) {
    echo 'ERROR: Cannot connect to database at ' . \$host . ':' . \$port . ' - ' . \$errstr . ' (' . \$errno . ')' . PHP_EOL;
    exit(1);
} else {
    echo 'SUCCESS: Database connection test passed' . PHP_EOL;
    fclose(\$socket);
}
"; then
    echo "Database connection test passed"
else
    echo "WARNING: Database connection test failed"
fi

# Run database migrations if needed (disabled by default to prevent crashes)
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running database migrations..."
    MIGRATION_OUTPUT=$(php spark migrate --force 2>&1)
    MIGRATION_EXIT_CODE=$?
    echo "Migration output: $MIGRATION_OUTPUT"
    if [ $MIGRATION_EXIT_CODE -eq 0 ]; then
        echo "Database migrations completed successfully"
    else
        echo "WARNING: Database migrations failed (exit code: $MIGRATION_EXIT_CODE), continuing anyway..."
    fi
fi

# Run database seeders if needed (disabled by default to prevent crashes)
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Running database seeders..."
    SEEDER_OUTPUT=$(php spark db:seed PetugasSeeder 2>&1)
    SEEDER_EXIT_CODE=$?
    echo "Seeder output: $SEEDER_OUTPUT"
    if [ $SEEDER_EXIT_CODE -eq 0 ]; then
        echo "Database seeders completed successfully"
    else
        echo "WARNING: Database seeders failed (exit code: $SEEDER_EXIT_CODE), continuing anyway..."
    fi
fi

echo "Starting Apache..."
exec apache2-foreground
