#!/bin/sh
set -e

# Start the Python recommendation service (FastAPI/Uvicorn) in the background,
# listening only on localhost since it's internal-only
cd /var/www/html/recommendation-service
uvicorn main:app --host 127.0.0.1 --port 8001 &
PYTHON_PID=$!

cd /var/www/html

# Run Laravel setup steps, then start PHP's server in the foreground
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan storage:link --force

# If the Python process dies, kill the whole container so Render restarts it
trap "kill $PYTHON_PID" EXIT

php artisan serve --host=0.0.0.0 --port=${PORT:-10000}