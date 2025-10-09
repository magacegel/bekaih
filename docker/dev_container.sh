#!/bin/bash

echo "==================================================================="
echo "                             DEV_CONTAINER                         "
echo "==================================================================="

cd /home/administrator

if [ -d $CI_PROJECT_NAME ]; then
    echo "\n Removing previous clone..."
    rm -r $CI_PROJECT_NAME
fi

echo "\n executing Git clone..."
git clone https://${token_pull}@gitlab.bki.co.id/${CI_PROJECT_PATH}.git
git config --global --add safe.directory /home/administrator/${CI_PROJECT_NAME}
cd ${CI_PROJECT_NAME}
ls -la

echo "\n executing chmod..."
chmod 777 -R storage
chmod 777 -R bootstrap
chown -R 1000:33 ./
cp docker/dev.env .env

echo "\n Rebuilding containers..."
sudo docker compose -f dev.yml down  -v
sudo docker compose -f dev.yml build --no-cache
sudo docker compose -f dev.yml up -d

sh docker/laravel_migrate.sh
