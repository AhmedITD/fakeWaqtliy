#!/bin/bash

echo "🗺️  Setting up Laravel + MapBox Vue Application"
echo "=============================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if required tools are installed
print_status "Checking system requirements..."

if ! command -v php &> /dev/null; then
    print_error "PHP is not installed. Please install PHP 8.1 or higher."
    exit 1
fi

if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer."
    exit 1
fi

if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed. Please install Node.js 20.19.0 or higher."
    exit 1
fi

if ! command -v npm &> /dev/null; then
    print_error "npm is not installed. Please install npm."
    exit 1
fi

print_success "All required tools are installed!"

# Setup Laravel backend
print_status "Setting up Laravel backend..."
cd map

# Install PHP dependencies
print_status "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if .env doesn't exist
if [ ! -f .env ]; then
    print_status "Creating Laravel .env file..."
    cp .env.example .env 2>/dev/null || echo "APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local

VITE_APP_NAME=\"\${APP_NAME}\"" > .env
    
    print_status "Generating Laravel application key..."
    php artisan key:generate
fi

# Create SQLite database if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    print_status "Creating SQLite database..."
    touch database/database.sqlite
fi

# Run migrations
print_status "Running database migrations..."
php artisan migrate --force

# Install Node.js dependencies for Laravel
if [ -f package.json ]; then
    print_status "Installing Laravel Node.js dependencies..."
    npm install
fi

print_success "Laravel backend setup complete!"

# Setup Vue frontend
print_status "Setting up Vue.js frontend..."
cd ../mapBox

# Install Node.js dependencies
print_status "Installing Vue.js dependencies..."
npm install

# Create .env file for Vue if it doesn't exist
if [ ! -f .env ]; then
    print_status "Creating Vue.js .env file..."
    echo "VITE_MAPBOX_ACCESS_TOKEN=your_mapbox_access_token_here
VITE_API_BASE_URL=http://localhost:8000/api" > .env
    print_warning "Please update .env file with your MapBox access token!"
fi

print_success "Vue.js frontend setup complete!"

cd ..

print_success "Setup completed successfully! 🎉"
echo ""
echo "Next steps:"
echo "1. Get a MapBox access token from https://account.mapbox.com/"
echo "2. Update mapBox/.env with your MapBox access token"
echo "3. Start the Laravel backend: cd map && php artisan serve"
echo "4. Start the Vue frontend: cd mapBox && npm run dev"
echo ""
echo "Your applications will be available at:"
echo "- Laravel API: http://localhost:8000"
echo "- Vue Frontend: http://localhost:5173"









