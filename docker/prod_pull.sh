#!/bin/bash -x

echo "==================================================================="
echo "                             PROD_PULL                             "
echo "==================================================================="

cd /home/administrator
ls -la

if [ ! -d $CI_PROJECT_NAME ]; then
    echo "\n executing Git clone..."
    git clone https://${token_pull}@gitlab.bki.co.id/${CI_PROJECT_PATH}.git
    git config --global --add safe.directory /home/administrator/${CI_PROJECT_NAME}
    cd ${CI_PROJECT_NAME}
    ls -la

else
    echo "\n executing Git pull..."
    git config --global --add safe.directory /home/administrator/${CI_PROJECT_NAME}
    cd ${CI_PROJECT_NAME}
    ls -la
    git reset --hard HEAD
    git pull

fi

echo "\n executing chmod..."
chmod 777 -R storage
chmod 777 -R bootstrap
chown -R 1000:33 ./
cp docker/prod.env .env

sh docker/laravel_migrate.sh
