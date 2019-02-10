#!/bin/bash

source /etc/stool/scripts/helper.sh

echo "* Refreshing software repositories..."
sudo apt-get update

echo "* Installing prerequisite software packages..."
sudo apt-get install -y software-properties-common

echo "* Setting up third-party repository to allow installation of multiple PHP versions..."
sudo add-apt-repository -y ppa:ondrej/php

echo "* Refreshing software repositories..."
sudo apt-get update

echo "* Installing PHP 5.6..."
sudo apt-get install -y php5.6 php5.6-fpm php5.6-common

echo "* Installing PHP 5.6 extensions..."
sudo apt-get install -y php5.6-curl php5.6-mcrypt php5.6-soap php5.6-bz2 php5.6-gd php5.6-mysql php5.6-sqlite3 php5.6-json php5.6-opcache php5.6-xml php5.6-mbstring php5.6-readline php5.6-xmlrpc php5.6-zip php5.6-intl php5.6-bcmath php5.6-gmp php-redis

cat ${SCRIPTS_PATH}php/opcache.conf >> /etc/php/5.6/fpm/php.ini

echo "* Installing PHP 7.0..."
sudo apt-get install -y php7.0 php7.0-fpm php7.0-common

echo "* Installing PHP 7.0 extensions..."
sudo apt-get install -y php7.0-gd php7.0-mysql php7.0-sqlite3 php7.0-soap php7.0-xsl php7.0-json php7.0-opcache php7.0-mbstring php7.0-readline php7.0-curl php7.0-mcrypt php7.0-xml php7.0-zip php7.0-intl php7.0-bcmath php7.0-gmp php-redis

cat ${SCRIPTS_PATH}php/opcache.conf >> /etc/php/7.0/fpm/php.ini

echo "* Installing PHP 7.1..."
sudo apt-get install -y php7.1 php7.1-fpm php7.1-common

echo "* Installing PHP 7.1 extensions..."
sudo apt-get install -y php7.1-gd php7.1-mysql php7.1-sqlite3 php7.1-soap php7.1-xsl php7.1-json php7.1-opcache php7.1-mbstring php7.1-readline php7.1-curl php7.1-mcrypt php7.1-xml php7.1-zip php7.1-intl php7.1-bcmath php7.1-gmp php-redis

cat ${SCRIPTS_PATH}php/opcache.conf >> /etc/php/7.1/fpm/php.ini

echo "* Installing PHP 7.2..."
sudo apt-get install -y php7.2 php7.2-fpm php7.2-common

echo "* Installing PHP 7.2 extensions..."
sudo apt-get install -y php7.2-bz2 php7.2-curl php7.2-gd php7.2-json php7.2-mbstring php7.2-mysql php7.2-opcache php7.2-readline php7.2-soap php7.2-sqlite3 php7.2-tidy php7.2-xml php7.2-xsl php7.2-zip php7.2-intl php7.2-bcmath php7.2-gmp php-redis

cat ${SCRIPTS_PATH}php/opcache.conf >> /etc/php/7.2/fpm/php.ini

echo "* Installing PHP 7.3..."
sudo apt-get install -y php7.3 php7.3-fpm php7.3-common

echo "* Installing PHP 7.3 extensions..."
sudo apt-get install -y php7.3-bz2 php7.3-curl php7.3-gd php7.3-json php7.3-mbstring php7.3-mysql php7.3-opcache php7.3-readline php7.3-soap php7.3-sqlite3 php7.3-tidy php7.3-xml php7.3-xsl php7.3-zip php7.3-intl php7.3-bcmath php7.3-gmp php-redis

cat ${SCRIPTS_PATH}php/opcache.conf >> /etc/php/7.3/fpm/php.ini

echo "* Installing additional PHP extensions..."
sudo apt-get install -y php-memcache php-memcached

echo "* Setup complete"
