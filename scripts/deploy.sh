#!/bin/sh

chown -R ec2-user /var/www/twitter-clone

composer install --optimize-autoloader --no-dev
npm install
npm run prod
php artisan config:cache
php artisan view:cache
php artisan route:cache

chown -R nginx /var/www/twitter-clone
