#!/bin/bash

cd "$(dirname "${BASH_SOURCE[0]}")" || exit

php artisan schedule:run >> /dev/null 2>&1
