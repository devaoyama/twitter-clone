#!/bin/sh

chown ec2-user /var/www/twitter-clone
composer install --optimize-autoloader --no-dev
npm install
npm run prod
php artisan config:cache
php artisan view:cache
php artisan route:cache
