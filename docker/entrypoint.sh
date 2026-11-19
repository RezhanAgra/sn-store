#!/bin/bash
set -e

cd /var/www/html

if [ "$1" = "php-fpm" ]; then
    php artisan storage:link --force || true

    if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
        php artisan migrate --force
    fi

    if [ "${APP_ENV}" = "production" ]; then
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
    fi
fi

exec "$@"
