#!/bin/bash
set -e

# Run migrations (pakai DB dari env variable - PostgreSQL di Render)
php artisan migrate --force

# Buat admin user kalau belum ada (|| true agar tidak matikan server kalau gagal)
php artisan db:seed --force || true

# Create storage link
php artisan storage:link || true

# Clear & cache config untuk production (|| true agar tidak matikan server)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start Apache in foreground
exec apache2ctl -D FOREGROUND
