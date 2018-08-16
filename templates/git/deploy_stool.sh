#!/bin/bash

git pull
composer install --no-interaction --prefer-dist

# supervisorctl restart name