
if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q "APP_KEY=" .env || [ -z "$(grep APP_KEY .env | cut -d '=' -f2)" ]; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan storage:link || true

exec "$@"
