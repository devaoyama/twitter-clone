#!/bin/sh

composer install --optimize-autoloader --no-dev
npm install
npm run prod
php artisan config:cache
php artisan view:cache
php artisan route:cache
