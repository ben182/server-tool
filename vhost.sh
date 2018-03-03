#!/bin/bash

source /etc/server-tool/helper.sh

#echo "Name?"
#read NAME

#echo "Server Name?"
#read SERVER_NAME

#echo "Document Root (After /var/www/)?"
#read DOCUMENT_ROOT

echo "Domain?"
read DOMAIN

cp ${ABSOLUTE_PATH}apache/vhost.conf /etc/apache2/sites-available/$DOMAIN.conf
sudo sed -i "s|DOCUMENT_ROOT|$DOMAIN|" /etc/apache2/sites-available/$DOMAIN.conf

echo "www Alias?"
select yn in "Yes" "No"; do
    case $yn in
        Yes ) break;;
        No )

        sed -i '/ServerAlias www.SERVER_NAME/d' /etc/apache2/sites-available/$DOMAIN.conf

        break;;
    esac
done

sudo sed -i "s|SERVER_NAME|$DOMAIN|" /etc/apache2/sites-available/$DOMAIN.conf
sudo sed -i "s|NAME|$DOMAIN|" /etc/apache2/sites-available/$DOMAIN.conf

a2ensite $DOMAIN.conf

mkdir -p /var/www/$DOMAIN/html

echo "SSL?"
select yn in "Yes" "No"; do
    case $yn in
        Yes )

        echo "Email?"
        read EMAIL
        certbot --non-interactive --agree-tos --email $EMAIL --apache --domains $DOMAIN

        break;;
        No ) break;;
    esac
done

echo "htaccess?"
select yn in "Non SSL to SSL and www to non www" "www to non www" "Nothing"; do
    case $yn in
        "Non SSL to SSL and www to non www" )

        cp ${ABSOLUTE_PATH}apache/nonSSL_to_SSL_and_www_to_nonwww.htaccess /var/www/$DOMAIN/html/.htaccess
        break;;

        "www to non www" )
        cp ${ABSOLUTE_PATH}apache/www_to_nonwww.htaccess /var/www/$DOMAIN/html/.htaccess
        break;;

        "Nothing" )
        break;;
    esac
done

apache_permissions
service apache2 reload
