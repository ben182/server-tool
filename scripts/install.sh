#!/bin/bash

# http://patorjk.com/software/taag/#p=display&v=0&f=Slant&t=stool%20v1.4.0
cat << "EOF"
         __              __        _____ __   ____
   _____/ /_____  ____  / /  _   _<  / // /  / __ \
  / ___/ __/ __ \/ __ \/ /  | | / / / // /_ / / / /
 (__  ) /_/ /_/ / /_/ / /   | |/ / /__  __// /_/ /
/____/\__/\____/\____/_/    |___/_(_)/_/ (_)____/

EOF

source /etc/stool/scripts/helper.sh

# VARS
echo "Initialization..."

start=`date +%s`

DATABASE_TEMP_PASS=root
NEW_DB_PASS=$(passwordgen);
WELCOMEPAGE_TOKEN=$(passwordgen);

bash ${SCRIPTS_PATH}partials/init.sh
bash ${SCRIPTS_PATH}partials/user.sh

# APACHE
apacheInstall() {
    sudo apt-get install apache2 -y
    sudo a2enmod proxy_http
    sudo a2enmod rewrite

    sudo ufw allow in "Apache Full"
    sudo ufw --force enable

    echo "ServerName localhost" >> /etc/apache2/apache2.conf
    echo "ServerTokens Prod" >> /etc/apache2/apache2.conf
    echo "ServerSignature Off" >> /etc/apache2/apache2.conf
    echo "FileETag None" >> /etc/apache2/apache2.conf
    echo "LoadModule headers_module /usr/lib/apache2/modules/mod_headers.so" >> /etc/apache2/apache2.conf
    echo "Header always append X-Frame-Options SAMEORIGIN" >> /etc/apache2/apache2.conf
    echo "Header set X-XSS-Protection \"1; mode=block\"" >> /etc/apache2/apache2.conf
    echo "Protocols h2 h2c http/1.1" >> /etc/apache2/apache2.conf
    sudo sed -i "s|Options Indexes FollowSymLinks|Options -Indexes -Includes +FollowSymLinks|" /etc/apache2/apache2.conf
    sudo sed -i "s|Timeout 300|Timeout 60|" /etc/apache2/apache2.conf

    sudo sed -i "s|www-data|stool|" /etc/apache2/envvars

    cp ${TEMPLATES_PATH}apache/ip.conf /etc/apache2/sites-available/ip.conf
    sudo sed -i "s|IP_HERE|$PUBLIC_IP|" /etc/apache2/sites-available/ip.conf
    a2ensite ip.conf

    sudo a2enmod expires
    sudo a2enmod http2

    # mod pagespeed
    wget https://dl-ssl.google.com/dl/linux/direct/mod-pagespeed-stable_current_amd64.deb -P /tmp
    dpkg -i /tmp/mod-pagespeed-stable_current_amd64.deb
    apt-get -f install

    sudo sed -i "s|ModPagespeed on|ModPagespeed unplugged|" /etc/apache2/mods-available/pagespeed.conf

    service apache2 restart
}
echo "Installing and configuring Apache Server..."
apacheInstall

phpInstall () {
    bash /etc/stool/scripts/php/setup.sh
    bash /etc/stool/scripts/php/switch-to-php-7.3.sh
    sudo phpenmod mbstring

    sudo a2dismod mpm_prefork
    sudo a2enmod mpm_event

    sudo a2enmod proxy_fcgi setenvif

    service apache2 reload
    sudo service php7.3-fpm restart
}
echo "Installing and configuring PHP..."
phpInstall

# COMPOSER
composerInstall () {
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/bin/composer
}
echo "Installing Composer..."
composerInstall

# MYSQL
mysqlInstall() {
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $DATABASE_TEMP_PASS"
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DATABASE_TEMP_PASS"
    sudo apt-get -y install mysql-server

    mysql -u root -p"$DATABASE_TEMP_PASS" -e "UPDATE mysql.user SET authentication_string=PASSWORD('$NEW_DB_PASS') WHERE User='root'"
    mysql -u root -p"$DATABASE_TEMP_PASS" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
    mysql -u root -p"$DATABASE_TEMP_PASS" -e "DELETE FROM mysql.user WHERE User=''"
    mysql -u root -p"$DATABASE_TEMP_PASS" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
    mysql -u root -p"$DATABASE_TEMP_PASS" -e "FLUSH PRIVILEGES"

    sudo sed -i "s|ROOT_PASSWORD_HERE|$NEW_DB_PASS|" $CONFIG_PATH
}
echo "Installing and configuring MySQL Server..."
mysqlInstall

servertoolInstall() {
    mkdir -p /var/www/ip/html
    cp ${TEMPLATES_PATH}ip/. /var/www/ip/html -r
    sudo sed -i "s|TOKEN_HERE|$WELCOMEPAGE_TOKEN|" /var/www/ip/html/checkToken.php

    cp ${ABSOLUTE_PATH}.env.example ${ABSOLUTE_PATH}.env
    sudo sed -i "s|localhost|${PUBLIC_IP}/stool|" ${ABSOLUTE_PATH}.env
    ln -s ${ABSOLUTE_PATH}public /var/www/ip/html/stool
    cd $ABSOLUTE_PATH && composer install
    php ${ABSOLUTE_PATH}artisan key:generate
    ln -s ${ABSOLUTE_PATH}artisan /usr/bin/stool
    chmod +x /usr/bin/stool
    cp ${TEMPLATES_PATH}git/post-merge-this ${ABSOLUTE_PATH}.git/hooks/post-merge
1    chmod +x ${ABSOLUTE_PATH}.git/hooks/post-merge
    chown -R stool:stool /etc/stool
    chmod -R 755 /etc/stool
    crontab -l | { cat; echo "* * * * * stool schedule:run >> /dev/null 2>&1"; } | crontab -
    crontab -l | { cat; echo "0 0 * * * composer self-update >> /dev/null 2>&1"; } | crontab -
    crontab -l | { cat; echo "0 0 * * 0 apt-get autoremove && apt-get autoclean -y >> /dev/null 2>&1"; } | crontab -
    stool init
    stool migrate --force

    stool view:cache
    stool config:cache
    stool route:cache

    git config core.filemode false
}

echo "Installing Server Tool..."
servertoolInstall

# RUBY
apt install ruby ruby-dev make gcc -y

# SUPERVISOR
apt-get install supervisor -y
service supervisor restart

stool installation:run
stool installation:finish
bash ${SCRIPTS_PATH}partials/finish.sh
echo "Visit your welcome page at http://$PUBLIC_IP?token=$WELCOMEPAGE_TOKEN"
