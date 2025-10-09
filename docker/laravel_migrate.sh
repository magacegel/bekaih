#!/bin/bash

echo "==================================================================="
echo "                            MIGRATE                                "
echo "==================================================================="

# Check if the Docker container ut-php is running
if [ -z "$(docker ps --format '{{.Names}}' | grep "^ut-php$")" ]; then
    echo "||   Docker container ut-php is not running   ||"
    echo "==================================================================="
    exit 0
fi

echo "||                        SYSTEM CHECK                           ||"
echo "==================================================================="

docker exec ut-php pwd
docker exec ut-php ls -la

echo "==================================================================="

# check versions
docker exec ut-php which php
docker exec ut-php php -v
docker exec ut-php which node
docker exec ut-php node -v
docker exec ut-php which npm
docker exec ut-php npm -v
docker exec ut-php git config --global --add safe.directory /var/www/html

echo "==================================================================="
set -x

if [ -z "$(docker exec ut-php ls /var/www/html/artisan)" ]; then
    echo "|| Artisan file missing, maybe there is no code in /var/www/html ||"
    echo "==================================================================="
    exit 0
fi

echo "update vendor"
docker exec ut-php composer update
docker exec ut-php npm update

echo "set permission"
docker exec ut-php id www-data
docker exec ut-php chmod -R 777 /var/www/html/bootstrap/cache
docker exec ut-php chmod -R 777 /var/www/html/storage
docker exec ut-php chmod -R 777 /var/www/html/storage/framework
docker exec ut-php chmod -R 777 /var/www/html/storage/logs

echo "prepare laravel"
docker exec ut-php php artisan key:generate
docker exec ut-php php artisan storage:link

echo "optimize laravel"
docker exec ut-php php artisan optimize:clear
docker exec ut-php composer dump-autoload -o

# docker exec ut-php npm run build # skip because of error

echo "migrate database"
docker exec ut-php php artisan migrate:status
docker exec ut-php php artisan migrate --force

# docker exec ut-php php artisan db:seed --class=SeederClass --force
