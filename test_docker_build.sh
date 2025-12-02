#!/bin/bash

# Test Docker Build for Diabetes Prediction System
echo "=== Testing Docker Build ==="
echo "Application: Tugu Sawangan cinangka - Diabetes Prediction System"
echo "Date: $(date)"
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ ERROR: Docker is not running. Please start Docker Desktop."
    exit 1
fi

echo "✅ Docker is running"

# Check required files
echo ""
echo "=== Checking Required Files ==="
required_files=("Dockerfile" "docker-compose.yml" ".dockerignore" "docker/apache.conf" "docker/mysql-init/01-init.sql" "DEPLOYMENT.md")

missing_files=0
for file in "${required_files[@]}"; do
    if [ -f "$file" ] || [ -d "$file" ]; then
        echo "✅ Found: $file"
    else
        echo "❌ Missing: $file"
        missing_files=$((missing_files + 1))
    fi
done

if [ $missing_files -gt 0 ]; then
    echo ""
    echo "❌ ERROR: $missing_files required files are missing."
    exit 1
fi

echo ""
echo "✅ All required files found"

# Test Docker build (dry run)
echo ""
echo "=== Testing Docker Build (Dry Run) ==="
echo "This will test if Dockerfile can be parsed correctly..."
echo ""

if docker build --no-cache --progress=plain --target=base -t diabetes-predict-test . > docker-build-test.log 2>&1; then
    echo "✅ Docker build test successful"
    echo "Log saved to: docker-build-test.log"
    
    # Clean up test image
    docker rmi diabetes-predict-test > /dev/null 2>&1
else
    echo "❌ Docker build test failed"
    echo "Check docker-build-test.log for details"
    exit 1
fi

# Test docker-compose configuration
echo ""
echo "=== Testing docker-compose Configuration ==="
if docker-compose config > /dev/null 2>&1; then
    echo "✅ docker-compose configuration is valid"
else
    echo "❌ docker-compose configuration is invalid"
    exit 1
fi

# Check Python ML files
echo ""
echo "=== Checking Python ML Files ==="
python_files=("app/Python/predict.py" "app/Python/train_model.py" "app/Python/model.pkl")

missing_python=0
for file in "${python_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ Found: $file"
        
        # Check if model.pkl exists and has content
        if [[ "$file" == *"model.pkl"* ]]; then
            if [ -s "$file" ]; then
                echo "   ✅ Model file has content"
            else
                echo "   ⚠️  Model file is empty"
            fi
        fi
    else
        echo "❌ Missing: $file"
        missing_python=$((missing_python + 1))
    fi
done

if [ $missing_python -gt 0 ]; then
    echo ""
    echo "⚠️  WARNING: $missing_python Python ML files are missing"
    echo "   The application will still run but ML predictions won't work"
fi

# Summary
echo ""
echo "=== Test Summary ==="
echo "✅ Docker environment: Ready"
echo "✅ Required files: All present"
echo "✅ Docker build: Successful"
echo "✅ docker-compose: Valid configuration"

if [ $missing_python -eq 0 ]; then
    echo "✅ Python ML files: All present"
else
    echo "⚠️  Python ML files: $missing_python missing (ML predictions won't work)"
fi

echo ""
echo "=== Next Steps ==="
echo "1. Push to GitHub:"
echo "   git add ."
echo "   git commit -m 'Deploy Diabetes Prediction System'"
echo "   git push origin main"
echo ""
echo "2. Deploy on Dokploy:"
echo "   - Connect GitHub repository"
echo "   - Configure environment variables"
echo "   - Deploy using docker-compose"
echo ""
echo "3. After deployment:"
echo "   - Run migrations: php spark migrate"
echo "   - Run seeders: php spark db:seed PetugasSeeder"
echo "   - Access application at: http://your-domain.com"
echo ""
echo "=== Deployment Documentation ==="
echo "For detailed instructions, see: DEPLOYMENT.md"
echo ""
echo "✅ All tests completed successfully!"
