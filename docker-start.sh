#!/bin/bash
set -e

# Run migrations (pakai DB dari env variable - PostgreSQL di Render)
php artisan migrate --force

# Buat admin user kalau belum ada
php artisan db:seed --force

# Create storage link
php artisan storage:link || true

# Clear & cache config untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache in foreground
exec apache2ctl -D FOREGROUND
