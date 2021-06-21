#!/bin/bash

php artisan config:cache
php artisan key:generate
php artisan serve