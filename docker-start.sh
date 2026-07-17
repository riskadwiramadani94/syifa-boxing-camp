#!/bin/bash

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link || true

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
apache2-foreground
