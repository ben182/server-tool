#!/bin/bash

echo Name?
read NAME

echo Server Name?
read SERVER_NAME

echo Document Root (After /var/www/)?
read DOCUMENT_ROOT

cp apache/vhost.conf /etc/apache2/sites-available/$NAME.conf
sudo sed -i "s|SERVER_NAME|$SERVER_NAME|" /etc/apache2/sites-available/$NAME.conf
sudo sed -i "s|DOCUMENT_ROOT|$DOCUMENT_ROOT|" /etc/apache2/sites-available/$NAME.conf
sudo sed -i "s|NAME|$NAME|" /etc/apache2/sites-available/$NAME.conf
a2ensite $NAME.conf

mkdir /var/www/$DOCUMENT_ROOT


