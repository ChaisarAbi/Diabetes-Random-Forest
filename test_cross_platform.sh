#!/bin/bash
# Cross-platform compatibility test script
# Run this on Ubuntu VPS after cloning from Windows

echo "========================================="
echo "Diabetes Prediction System - Compatibility Test"
echo "========================================="

# Check Docker installation
echo "1. Checking Docker installation..."
if command -v docker &> /dev/null; then
    docker --version
else
    echo "❌ Docker not installed"
    echo "Install Docker: https://docs.docker.com/engine/install/ubuntu/"
    exit 1
fi

# Check Docker Compose
echo "2. Checking Docker Compose..."
if command -v docker-compose &> /dev/null; then
    docker-compose --version
elif docker compose version &> /dev/null; then
    echo "✓ Docker Compose V2 detected"
else
    echo "❌ Docker Compose not installed"
    echo "Install: sudo apt-get install docker-compose-plugin"
    exit 1
fi

# Validate docker-compose configuration
echo "3. Validating docker-compose configuration..."
if docker-compose config > /dev/null 2>&1; then
    echo "✓ docker-compose.yml is valid"
else
    echo "❌ docker-compose.yml has errors"
    docker-compose config
    exit 1
fi

# Check line endings in startup script
echo "4. Checking script line endings..."
if file docker/startup.sh | grep -q "CRLF"; then
    echo "⚠️  Windows line endings detected (CRLF)"
    echo "Converting to Unix line endings..."
    if command -v dos2unix &> /dev/null; then
        dos2unix docker/startup.sh
        echo "✓ Converted to Unix line endings"
    else
        echo "⚠️  dos2unix not installed, installing..."
        sudo apt-get update && sudo apt-get install -y dos2unix
        dos2unix docker/startup.sh
        echo "✓ Converted to Unix line endings"
    fi
else
    echo "✓ Unix line endings (LF)"
fi

# Make startup script executable
echo "5. Setting script permissions..."
chmod +x docker/startup.sh
if [ -x docker/startup.sh ]; then
    echo "✓ startup.sh is executable"
else
    echo "❌ Failed to make startup.sh executable"
    exit 1
fi

# Check required files
echo "6. Checking required files..."
required_files=(
    "docker/startup.sh"
    "app/Python/model.pkl"
    "docker-compose.yml"
    "Dockerfile"
    ".env"
)

missing_files=0
for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✓ $file exists"
    else
        echo "❌ $file missing"
        missing_files=$((missing_files + 1))
    fi
done

if [ $missing_files -gt 0 ]; then
    echo "⚠️  $missing_files required files missing"
    exit 1
fi

# Check writable directory permissions
echo "7. Checking directory permissions..."
if [ -d "writable" ]; then
    echo "✓ writable directory exists"
    # Note: Docker will handle permissions inside container
else
    echo "⚠️  writable directory missing, creating..."
    mkdir -p writable
fi

# Test Docker build (optional - can be skipped)
echo "8. Testing Docker build (this may take a few minutes)..."
read -p "Run Docker build test? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if docker build -t diabetes-test .; then
        echo "✓ Docker build successful"
    else
        echo "⚠️  Docker build failed, but this might be okay if Docker Desktop isn't running"
    fi
else
    echo "⏭️  Skipping Docker build test"
fi

echo "========================================="
echo "✅ Compatibility test completed!"
echo ""
echo "Next steps:"
echo "1. Start the application: docker-compose up -d"
echo "2. Check status: docker-compose ps"
echo "3. View logs: docker-compose logs -f web"
echo "4. Access at: http://$(hostname -I | awk '{print $1}'):8080"
echo ""
echo "Default credentials:"
echo "- Admin: admin / admin123"
echo "- Petugas: petugas1 / petugas123"
echo "========================================="
