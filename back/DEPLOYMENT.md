# 🚀 Waqitly API Deployment Guide

This guide covers deploying the Waqitly API to various platforms.

## 📋 Pre-deployment Checklist

### 1. Environment Configuration
- [ ] Copy `.env.example` to `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate `APP_KEY`
- [ ] Configure database credentials
- [ ] Set proper `APP_URL`

### 2. Security
- [ ] Change default database passwords
- [ ] Set strong `APP_KEY`
- [ ] Configure CORS settings
- [ ] Set up SSL/HTTPS
- [ ] Review file permissions

### 3. Performance
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up database indexes

## 🌐 GitHub Deployment

### Step 1: Initialize Repository
```bash
# If not already initialized
git init

# Add all files
git add .

# Commit changes
git commit -m "Initial commit: Waqitly API with complete CRUD operations"
```

### Step 2: Create GitHub Repository
1. Go to https://github.com
2. Click "New repository"
3. Name: `waqitly-api`
4. Description: "Complete RESTful API for space booking system"
5. Keep it public or private as needed
6. Don't initialize with README (we already have one)

### Step 3: Connect and Push
```bash
# Add GitHub remote
git remote add origin https://github.com/YOUR_USERNAME/waqitly-api.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## 🐳 Docker Deployment

### Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
```

### Docker Compose
```yaml
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8000:8000"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    volumes:
      - ./storage:/var/www/storage
    depends_on:
      - db
  
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: waqitly
      MYSQL_USER: waqitly
      MYSQL_PASSWORD: password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"

volumes:
  db_data:
```

## ☁️ Cloud Platform Deployments

### Heroku Deployment

1. **Install Heroku CLI**
2. **Create Procfile:**
```
web: vendor/bin/heroku-php-apache2 public/
```

3. **Deploy:**
```bash
heroku create waqitly-api
heroku addons:create heroku-postgresql:hobby-dev
heroku config:set APP_KEY=$(php artisan --no-ansi key:generate --show)
git push heroku main
heroku run php artisan migrate --force
```

### DigitalOcean App Platform

1. **Create `app.yaml`:**
```yaml
name: waqitly-api
services:
- name: api
  source_dir: /
  github:
    repo: YOUR_USERNAME/waqitly-api
    branch: main
  run_command: php artisan serve --host=0.0.0.0 --port=8080
  environment_slug: php
  instance_count: 1
  instance_size_slug: basic-xxs
  routes:
  - path: /
  envs:
  - key: APP_ENV
    value: production
  - key: APP_DEBUG
    value: false
databases:
- name: db
  engine: PG
  version: "13"
```

### AWS Elastic Beanstalk

1. **Install EB CLI**
2. **Initialize:**
```bash
eb init waqitly-api
eb create production
eb deploy
```

## 🗄️ Database Setup

### MySQL Production
```sql
CREATE DATABASE waqitly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'waqitly'@'%' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON waqitly.* TO 'waqitly'@'%';
FLUSH PRIVILEGES;
```

### Environment Variables
```env
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=waqitly
DB_USERNAME=waqitly
DB_PASSWORD=your_secure_password
```

## 🔧 Production Commands

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 🛡️ Security Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Use strong passwords
- [ ] Enable HTTPS
- [ ] Configure CORS properly
- [ ] Set up rate limiting
- [ ] Hide server information
- [ ] Regular security updates
- [ ] Database access restrictions
- [ ] File upload validation
- [ ] Input sanitization

## 📊 Monitoring & Logging

### Laravel Telescope (Development)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Production Logging
```php
// config/logging.php
'channels' => [
    'production' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'error',
        'days' => 14,
    ],
],
```

## 🚀 CI/CD Pipeline (GitHub Actions)

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
    
    - name: Install dependencies
      run: composer install --optimize-autoloader --no-dev
    
    - name: Run tests
      run: php artisan test
    
    - name: Deploy to server
      run: |
        # Add your deployment commands here
        echo "Deploying to production server..."
```

## 📞 Support & Documentation

- **API Documentation:** Available at `/` (home page)
- **Postman Collection:** `docs/Waqitly_API.postman_collection.json`
- **Technical Docs:** `docs/API_ENDPOINTS.md`

## 🔍 Health Check Endpoint

The API includes a health check at the root URL that returns API status and available endpoints.

---

**Ready for production!** 🎉

Choose your preferred deployment method and follow the corresponding steps above.
