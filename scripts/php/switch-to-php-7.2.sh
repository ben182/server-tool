#!/bin/bash

echo "* Disabling Apache PHP 5.6 module..."
sudo a2disconf php5.6-fpm > /dev/null

echo "* Disabling Apache PHP 7.0 module..."
sudo a2disconf php7.0-fpm > /dev/null

echo "* Disabling Apache PHP 7.1 module..."
sudo a2disconf php7.1-fpm > /dev/null

echo "* Enabling Apache PHP 7.2 module..."
sudo a2enconf php7.2-fpm > /dev/null

echo "* Restarting Apache..."
sudo service apache2 restart > /dev/null

echo "* Switching CLI PHP to 7.2..."
sudo update-alternatives --set php /usr/bin/php7.2 > /dev/null

echo "* Switch to PHP 7.2 complete."
