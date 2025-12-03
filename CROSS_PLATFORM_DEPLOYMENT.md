# Cross-Platform Docker Deployment Guide

## ✅ Docker Portability Concept

**Yes, you're absolutely correct!** Docker containers are designed to be portable across different operating systems. When you clone this project from Windows Docker Desktop to an Ubuntu 24 VPS, it **should run fine** with minimal adjustments.

## 🔄 How Docker Portability Works

### 1. **Container Isolation**
- Docker containers run in isolated environments
- The container runtime abstracts away host OS differences
- Same Docker image runs on Windows, Linux, or macOS

### 2. **Configuration Files**
- `Dockerfile`: Defines the application environment (OS: Debian-based)
- `docker-compose.yml`: Defines multi-container setup
- Both files use platform-agnostic syntax

### 3. **Volume Mounts**
Current configuration uses relative paths:
```yaml
volumes:
  - ./writable:/var/www/html/writable           # ✅ Cross-platform
  - ./public/logo.png:/var/www/html/public/logo.png  # ✅ Cross-platform
  - ./app/Python:/var/www/html/app/Python       # ✅ Cross-platform
```

## 🖥️ Windows → Ubuntu VPS Migration Steps

### Step 1: Clone Repository on Ubuntu VPS
```bash
# On Ubuntu VPS
git clone <your-repository-url>
cd diabetes-predict
```

### Step 2: Verify Line Endings (Important!)
Windows uses CRLF (`\r\n`), Linux uses LF (`\n`):
```bash
# Convert Windows line endings to Unix
sudo apt-get install dos2unix
dos2unix docker/startup.sh
dos2unix docker/*.sql
```

### Step 3: Set Proper Permissions (Linux-specific)
```bash
# Make startup script executable
chmod +x docker/startup.sh

# Set directory permissions
chmod -R 755 writable
sudo chown -R www-data:www-data writable  # Only if using traditional deployment
```

### Step 4: Deploy with Docker Compose
```bash
# Start all services
docker-compose up -d

# Verify services are running
docker-compose ps

# Check logs
docker-compose logs -f web
```

## ⚠️ Potential Cross-Platform Issues & Solutions

### 1. **Line Ending Issues**
- **Problem**: Windows CRLF in shell scripts causes errors on Linux
- **Solution**: Use `dos2unix` or configure Git to handle line endings:
  ```bash
  git config --global core.autocrlf input
  ```

### 2. **File Permission Differences**
- **Problem**: Linux has stricter file permissions
- **Solution**: Ensure writable directories have proper permissions:
  ```bash
  docker-compose exec web chmod -R 755 /var/www/html/writable
  docker-compose exec web chown -R www-data:www-data /var/www/html/writable
  ```

### 3. **Path Separator Differences**
- **Problem**: Windows uses `\`, Linux uses `/`
- **Status**: ✅ Already using forward slashes in all configurations

### 4. **Python ML Model Compatibility**
- **Problem**: `model.pkl` trained on Windows might have compatibility issues
- **Solution**: The prediction script includes a fallback dummy model
- **Recommendation**: Retrain model on Linux if accuracy issues occur:
  ```bash
  docker-compose exec web python3 app/Python/train_model.py
  ```

## 🐳 Docker-Specific Considerations

### Docker Desktop (Windows/Mac) vs Docker Engine (Linux)

| Aspect | Docker Desktop (Windows) | Docker Engine (Linux) |
|--------|--------------------------|----------------------|
| **Underlying VM** | Uses Hyper-V/WSL2 | Native Linux kernel |
| **Performance** | Slight overhead | Native performance |
| **Volume Mounts** | Through WSL2 | Direct filesystem |
| **Networking** | NAT networking | Bridge networking |

### Network Configuration
- **Windows**: Ports mapped to `localhost`
- **Linux**: Ports mapped to server IP
- **Access URLs**:
  - Windows: `http://localhost:8080`
  - Ubuntu VPS: `http://<vps-ip>:8080`

## 🔧 Platform-Specific Optimizations

### For Ubuntu 24 VPS:
```bash
# Install Docker Compose v2 (if not present)
sudo apt-get update
sudo apt-get install docker-compose-v2

# Increase Docker resources (if needed)
sudo nano /etc/docker/daemon.json
# Add: { "default-ulimits": { "nofile": { "Name": "nofile", "Hard": 65536, "Soft": 65536 } } }

# Restart Docker
sudo systemctl restart docker
```

### For Windows Docker Desktop:
- Ensure WSL2 backend is enabled
- Allocate sufficient memory (4GB+ recommended)
- Enable Docker Compose V2 in settings

## 📊 Testing Cross-Platform Compatibility

### Quick Test Script (run on Ubuntu):
```bash
#!/bin/bash
# test_deployment.sh

echo "1. Checking Docker installation..."
docker --version
docker-compose --version

echo "2. Validating docker-compose configuration..."
docker-compose config

echo "3. Testing volume paths..."
ls -la writable/
ls -la app/Python/model.pkl

echo "4. Checking script line endings..."
file docker/startup.sh | grep -q "ASCII text" && echo "✓ Line endings OK" || echo "✗ Line ending issues"

echo "5. Testing Docker build..."
docker build -t diabetes-test .
```

## 🚀 Deployment Commands Comparison

### Windows (PowerShell):
```powershell
# Start services
docker-compose up -d

# View logs
docker-compose logs -f web

# Access application
start http://localhost:8080
```

### Ubuntu (Bash):
```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs -f web

# Access application (from another machine)
echo "Application available at: http://$(hostname -I | awk '{print $1}'):8080"
```

## 🔍 Troubleshooting Cross-Platform Issues

### Common Issues & Fixes:

1. **"Permission denied" on startup.sh**
   ```bash
   chmod +x docker/startup.sh
   ```

2. **Database connection errors**
   ```bash
   # Check if MySQL container is running
   docker-compose ps db
   
   # Check MySQL logs
   docker-compose logs db
   ```

3. **Python ML not working**
   ```bash
   # Test Python inside container
   docker-compose exec web python3 --version
   docker-compose exec web python3 app/Python/predict.py '{"pregnancies":6,"glucose":148,"blood_pressure":72,"skin_thickness":35,"insulin":0,"bmi":33.6,"dpf":0.627,"age":50}'
   ```

4. **Application not accessible**
   ```bash
   # Check firewall on Ubuntu
   sudo ufw allow 8080/tcp
   sudo ufw allow 3306/tcp
   sudo ufw allow 8081/tcp
   ```

## ✅ Verification Checklist (After Migration)

- [ ] Docker and Docker Compose installed on Ubuntu
- [ ] Line endings converted (CRLF → LF)
- [ ] Script permissions set (`chmod +x`)
- [ ] Docker Compose starts without errors
- [ ] All containers running (`docker-compose ps`)
- [ ] Application accessible via browser
- [ ] Database connections working
- [ ] Python ML predictions functioning

## 📞 Support

- **Docker Issues**: Check Docker documentation for your platform
- **Application Issues**: Same codebase, should behave identically
- **Platform-Specific Issues**: Refer to this document

---

**Conclusion**: Yes, this Dockerized application will run correctly when cloned from Windows to Ubuntu 24 VPS. The configuration has been designed with cross-platform compatibility in mind, using relative paths and platform-agnostic Docker configurations.
