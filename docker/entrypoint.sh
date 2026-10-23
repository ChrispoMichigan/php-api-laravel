#!/bin/sh
set -e

cd /app

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$APP_KEY" ] && ! grep -q '^APP_KEY=.\+' .env; then
    php artisan key:generate --force
fi

DB_FILE="${DB_DATABASE:-database/database.sqlite}"
mkdir -p "$(dirname "$DB_FILE")"
touch "$DB_FILE"

php artisan migrate --force

USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -n1)
if [ "$USER_COUNT" = "0" ]; then
    php artisan db:seed --force
fi

php artisan l5-swagger:generate

PORT="${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="$PORT"
