#!/bin/bash

echo "=== Starting Diabetes Prediction System ==="

# Generate .env file if not exists
if [ ! -f /var/www/html/.env ]; then
    echo "Generating .env file..."
    cat > /var/www/html/.env <<EOF
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------

CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------

app.baseURL = 'https://pikoy.aventra.my.id/'

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = \${DATABASE_HOST:-db}
database.default.database = \${DATABASE_NAME:-diabetes_db}
database.default.username = \${DATABASE_USERNAME:-diabetes_user}
database.default.password = '\${DATABASE_PASSWORD:-SecurePassword123!@#}'
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = \${DATABASE_PORT:-3306}

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

encryption.key = '\${ENCRYPTION_KEY:-21431c8f632ae04b95fc6a9a881358ee03744c2bab3ca1d4f66bb392886be505}'
encryption.driver = OpenSSL

cookie.prefix = 
cookie.domain = 
cookie.path = /
cookie.secure = true
cookie.httpOnly = true
cookie.sameSite = Lax

#--------------------------------------------------------------------
# TOOLBAR
#--------------------------------------------------------------------

toolbar.enable = false
EOF
    echo ".env file generated"
fi

# Set permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/writable

# Create database tables if needed (optional)
if [ "${CREATE_TABLES:-true}" = "true" ]; then
    echo "Creating database tables if needed..."
    if command -v mysql >/dev/null 2>&1; then
        mysql -h "${DATABASE_HOST:-db}" \
              -u "${DATABASE_USERNAME:-diabetes_user}" \
              -p"${DATABASE_PASSWORD:-SecurePassword123!@#}" \
              "${DATABASE_NAME:-diabetes_db}" \
              < /var/www/html/docker/create-tables.sql 2>/dev/null || true
    fi
fi

echo "Starting Apache..."
exec apache2-foreground
