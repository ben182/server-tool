#!/bin/bash

source /etc/server-tool/scripts/helper.sh

PHPMYADMIN_HTACCESS_USER=$(passwordgen);
PHPMYADMIN_HTACCESS_PASS=$(passwordgen);

# PHPMYADMIN
phpmyadminInstall () {
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password $NEW_DB_PASS'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password $NEW_DB_PASS'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password $NEW_DB_PASS'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'

    apt-get install -y phpmyadmin

    cp ${TEMPLATES_PATH}apache/phpmyadmin.conf /etc/apache2/conf-available/phpmyadmin.conf
    cp ${TEMPLATES_PATH}apache/dir.conf /etc/apache2/mods-enabled/dir.conf

    cp ${TEMPLATES_PATH}apache/ip.conf /etc/apache2/sites-available/ip.conf
    sudo sed -i "s|IP_HERE|$PUBLIC_IP|" /etc/apache2/sites-available/ip.conf
    sudo sed -i "s|</VirtualHost>|Include /etc/apache2/conf-available/phpmyadmin.conf\n</VirtualHost>|" /etc/apache2/sites-available/ip.conf
    a2ensite ip.conf
    a2disconf phpmyadmin

    cp ${TEMPLATES_PATH}phpmyadmin/.htaccess /usr/share/phpmyadmin/.htaccess
    htpasswd -c -b /etc/phpmyadmin/.htpasswd $PHPMYADMIN_HTACCESS_USER $PHPMYADMIN_HTACCESS_PASS

    sudo sed -i "s|PHPMYADMIN_HTACCESS_USERNAME|$PHPMYADMIN_HTACCESS_USER|" $CONFIG_PATH
    sudo sed -i "s|PHPMYADMIN_HTACCESS_PASSWORD|$PHPMYADMIN_HTACCESS_PASS|" $CONFIG_PATH
}
echo "Installing phpMyAdmin..."
phpmyadminInstall &> /dev/null