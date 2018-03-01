#!/bin/bash

ABSOLUTE_PATH=/etc/server-tool/

#echo "Name?"
#read NAME

#echo "Server Name?"
#read SERVER_NAME

#echo "Document Root (After /var/www/)?"
#read DOCUMENT_ROOT

echo "Domain?"
read DOMAIN

cp ${ABSOLUTE_PATH}apache/vhost.conf /etc/apache2/sites-available/$DOMAIN.conf
sudo sed -i "s|SERVER_NAME|$DOMAIN|" /etc/apache2/sites-available/$DOMAIN.conf
sudo sed -i "s|DOCUMENT_ROOT|$DOMAIN|" /etc/apache2/sites-available/$DOMAIN.conf
sudo sed -i "s|NAME|$DOMAIN|" /etc/apache2/sites-available/$DOMAIN.conf
a2ensite $DOMAIN.conf
service apache2 reload

mkdir /var/www/$DOMAIN


