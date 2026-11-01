#!/bin/sh
set -e

if [ -z "$APP_KEY" ]; then
    echo 'APP_KEY belum diisi di .env. Buat dengan: echo "base64:$(openssl rand -base64 32)"' >&2
    exit 1
fi

# Volume storage bisa saja masih kosong saat pertama kali dipakai
mkdir -p storage/app/public storage/fonts storage/logs \
    storage/framework/cache/data storage/framework/sessions storage/framework/views

php artisan migrate --force
php artisan optimize

chown -R www-data:www-data storage bootstrap/cache

exec "$@"
