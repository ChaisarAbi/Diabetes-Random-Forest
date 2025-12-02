# Diabetes Prediction System - Deployment Guide

## 📋 Application Overview
**Name**: Tugu Sawangan cinangka - Sistem Prediksi Diabetes  
**Technology Stack**: CodeIgniter 4 + AdminLTE + Python Random Forest  
**Features**: Patient Management, Diabetes Prediction, Reports, User Management

## 🐳 Docker Deployment with Dokploy

### Prerequisites
1. **Dokploy Account** - Registered and logged in
2. **GitHub Repository** - Code pushed to GitHub
3. **Docker** - Installed on Dokploy server
4. **Domain/Subdomain** - Configured for application

### Step 1: Prepare GitHub Repository
1. Push all code to GitHub:
   ```bash
   git add .
   git commit -m "Deploy Diabetes Prediction System"
   git push origin main
   ```

2. Ensure these files are in repository:
   - `Dockerfile`
   - `docker-compose.yml`
   - `.dockerignore`
   - `docker/apache.conf`
   - `docker/mysql-init/01-init.sql`

### Step 2: Dokploy Configuration

#### 2.1 Connect GitHub Repository
1. Login to Dokploy dashboard
2. Click "Add New Application"
3. Select "GitHub" as source
4. Authorize GitHub access
5. Select your repository
6. Choose branch (usually `main` or `master`)

#### 2.2 Configure Application
1. **Application Name**: `diabetes-predict-system`
2. **Build Method**: `Dockerfile`
3. **Dockerfile Path**: `./Dockerfile`
4. **Build Context**: `.`
5. **Port**: `8080` (mapped to container port 80)

#### 2.3 Environment Variables
Add these environment variables in Dokploy:

```env
# Application
CI_ENVIRONMENT=production
APP_BASE_URL=https://your-domain.com
ENCRYPTION_KEY=generate-secure-random-key-32-chars

# Database
DATABASE_HOST=db
DATABASE_NAME=diabetes_db
DATABASE_USERNAME=diabetes_user
DATABASE_PASSWORD=secure_password_here
DATABASE_PORT=3306

# Session
SESSION_DRIVER=files
SESSION_SAVE_PATH=/var/www/html/writable/session

# Database Root (for MySQL container)
DB_ROOT_PASSWORD=secure_root_password
```

#### 2.4 Volumes Configuration
Map these volumes in Dokploy:
1. `./writable` → `/var/www/html/writable`
2. `./public/logo.png` → `/var/www/html/public/logo.png`
3. `./app/Python` → `/var/www/html/app/Python`

### Step 3: Database Configuration

#### 3.1 Add MySQL Service
In Dokploy, add a MySQL service:
- **Service Name**: `db`
- **Image**: `mysql:8.0`
- **Port**: `3306:3306`
- **Environment Variables**:
  - `MYSQL_ROOT_PASSWORD=${DB_ROOT_PASSWORD}`
  - `MYSQL_DATABASE=${DATABASE_NAME}`
  - `MYSQL_USER=${DATABASE_USERNAME}`
  - `MYSQL_PASSWORD=${DATABASE_PASSWORD}`
- **Volumes**: `mysql-data:/var/lib/mysql`

#### 3.2 Database Initialization
The MySQL container will automatically:
1. Create database `diabetes_db`
2. Create user `diabetes_user`
3. Run migrations (via application startup)

### Step 4: Application Startup Commands

After deployment, run these commands in Dokploy terminal:

```bash
# Run database migrations
php spark migrate

# Run seeders to create initial data
php spark db:seed PetugasSeeder
php spark db:seed PasienSeeder
php spark db:seed PrediksiSeeder

# Set proper permissions
chmod -R 755 writable
chown -R www-data:www-data writable
```

### Step 5: Health Checks

Dokploy will monitor:
- **Web Service**: `http://localhost:8080/health` (returns 200 OK)
- **Database**: MySQL ping every 30 seconds

## 🔧 Manual Docker Deployment

If deploying manually without Dokploy:

```bash
# 1. Clone repository
git clone https://github.com/your-username/diabetes-predict.git
cd diabetes-predict

# 2. Create .env file
cp .env.example .env
# Edit .env with your configuration

# 3. Start services
docker-compose up -d

# 4. Run migrations
docker-compose exec web php spark migrate

# 5. Run seeders
docker-compose exec web php spark db:seed PetugasSeeder
docker-compose exec web php spark db:seed PasienSeeder
docker-compose exec web php spark db:seed PrediksiSeeder

# 6. Check logs
docker-compose logs -f web
```

## 📊 Application URLs

After deployment:
- **Main Application**: `https://your-domain.com`
- **phpMyAdmin**: `https://your-domain.com:8081` (if enabled)
- **Health Check**: `https://your-domain.com/health`

## 🔐 Default Login Credentials

After running seeders:
- **Admin**: `admin` / `admin123`
- **Petugas**: `petugas1` / `petugas123`

## 🚨 Troubleshooting

### Common Issues:

1. **Database Connection Error**
   - Check MySQL container is running: `docker-compose ps`
   - Verify environment variables
   - Check MySQL logs: `docker-compose logs db`

2. **Permission Denied on Writable Directory**
   ```bash
   docker-compose exec web chmod -R 755 writable
   docker-compose exec web chown -R www-data:www-data writable
   ```

3. **Python ML Not Working**
   - Check Python is installed: `docker-compose exec web python3 --version`
   - Verify ML libraries: `docker-compose exec web pip3 list`
   - Check model file exists: `ls app/Python/model.pkl`

4. **Application Not Accessible**
   - Check port mapping: `docker-compose ps`
   - Check Apache logs: `docker-compose logs web`
   - Verify firewall rules

## 📞 Support

For issues with:
- **Application Code**: Check GitHub Issues
- **Dokploy Deployment**: Dokploy Documentation
- **Docker Configuration**: Docker Documentation

## 🔄 Updates and Maintenance

To update the application:
1. Push changes to GitHub
2. Dokploy will automatically rebuild
3. Run migrations if database changes: `php spark migrate`
4. Clear cache: `php spark cache:clear`
