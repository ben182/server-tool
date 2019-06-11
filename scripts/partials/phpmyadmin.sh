#!/bin/bash

source /etc/stool/scripts/helper.sh

HTACCESS_USER=$(passwordgen);
HTACCESS_PASS=$(passwordgen);

# PHPMYADMIN
phpmyadminInstall () {
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password $NEW_DB_PASS'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password $NEW_DB_PASS'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password $NEW_DB_PASS'
    debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'

    apt-get install -y phpmyadmin

    cp ${TEMPLATES_PATH}apache/phpmyadmin.conf /etc/apache2/conf-available/phpmyadmin.conf

    sudo sed -i "s|</VirtualHost>|Include /etc/apache2/conf-available/phpmyadmin.conf\n</VirtualHost>|" /etc/apache2/sites-available/ip.conf
    a2disconf phpmyadmin

    cp ${TEMPLATES_PATH}phpmyadmin/.htaccess /usr/share/phpmyadmin/.htaccess
    htpasswd -c -b /etc/phpmyadmin/.htpasswd $HTACCESS_USER $HTACCESS_PASS

    sudo sed -i "s|PHPMYADMIN_HTACCESS_USERNAME|$HTACCESS_USER|" $CONFIG_PATH
    sudo sed -i "s|PHPMYADMIN_HTACCESS_PASSWORD|$HTACCESS_PASS|" $CONFIG_PATH

    service apache2 restart
}
echo "Installing phpMyAdmin..."
phpmyadminInstall
