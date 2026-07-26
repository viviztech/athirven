#!/bin/sh
set -e

cd /var/www/html

if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is not set — refusing to start. Set it in Coolify's environment variables (generate locally with: php artisan key:generate --show)."
    exit 1
fi

echo "Waiting for the database..."
attempt=0
max_attempts=30
until php artisan db:show > /dev/null 2>&1; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge "$max_attempts" ]; then
        echo "Could not reach the database after ${max_attempts} attempts. Check DB_CONNECTION/DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD in Coolify's environment variables, and that the database service is running."
        exit 1
    fi
    sleep 2
done

# storage/app/public is a persistent Docker volume (uploaded media survives
# redeploys) — it can be (re-)initialized by Docker with root ownership on
# first mount regardless of what the image sets, so re-assert www-data
# ownership on every start rather than relying on the image alone.
mkdir -p storage/app/public
chown -R www-data:www-data storage/app/public

php artisan migrate --force

# Every seeder here is idempotent (firstOrCreate, or an explicit "skip if
# data already exists" guard in Demo/SubscriptionPlan seeders) — safe to run
# on every container start, not just the first one. This is what gives a
# freshly provisioned database its Admin login and role/permission set.
php artisan db:seed --force

# storage:link errors if the symlink already exists (e.g. on redeploy of the
# same volume) — that's expected, not a failure.
php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
