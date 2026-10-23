FROM php:8.3-cli-alpine

RUN apk add --no-cache sqlite-dev oniguruma-dev \
    && docker-php-ext-install pdo_sqlite sqlite3 mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --no-interaction --optimize-autoloader --no-progress \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh \
    && sed -i 's/\r$//' docker/entrypoint.sh

ENV APP_ENV=production \
    APP_DEBUG=false \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/app/database/database.sqlite \
    L5_SWAGGER_GENERATE_ALWAYS=true

EXPOSE 8000

ENTRYPOINT ["docker/entrypoint.sh"]
