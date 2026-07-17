#!/bin/bash
set -e

# Pastikan file SQLite ada dan permission benar
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link || true

# Cache config dan route saja (tidak cache view karena ada query DB)
php artisan config:cache
php artisan route:cache

# Start Apache in foreground
exec apache2ctl -D FOREGROUND
