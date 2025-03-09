#!/bin/bash

# Exit on error
set -e

# Load environment variables
source .env

# Print current step
echo_step() {
    echo "➡️ $1"
}

# Check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verify requirements
echo_step "Verifying requirements..."
for cmd in git php composer npm; do
    if ! command_exists $cmd; then
        echo "❌ $cmd is required but not installed."
        exit 1
    fi
done

# Pull latest changes
echo_step "Pulling latest changes..."
git pull origin main

# Install/update Composer dependencies
echo_step "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install/update NPM dependencies
echo_step "Installing NPM dependencies..."
npm install
npm run build

# Clear caches
echo_step "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run database migrations
echo_step "Running database migrations..."
php artisan migrate --force

# Optimize application
echo_step "Optimizing application..."
php artisan optimize
php artisan view:cache
php artisan config:cache
php artisan route:cache

# Update storage link
echo_step "Updating storage link..."
php artisan storage:link

# Set permissions
echo_step "Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Run tests
echo_step "Running tests..."
php artisan test

# Restart queue worker
echo_step "Restarting queue worker..."
php artisan queue:restart

# Clear old sessions
echo_step "Clearing old sessions..."
php artisan session:gc

# Check application health
echo_step "Checking application health..."
curl -s http://localhost/health || echo "❌ Health check failed"

echo "✅ Deployment completed successfully!" 