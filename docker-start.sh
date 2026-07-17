#!/bin/bash
set -e

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link || true

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache in foreground
exec apache2ctl -D FOREGROUND
