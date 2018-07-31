#!/bin/bash

# http://patorjk.com/software/taag/#p=display&v=0&f=Slant&t=Server%20Tool%20v1.0
cat << "EOF"
         __              __        ___ ___
   _____/ /_____  ____  / /  _   _<  /<  /
  / ___/ __/ __ \/ __ \/ /  | | / / / / /
 (__  ) /_/ /_/ / /_/ / /   | |/ / / / /
/____/\__/\____/\____/_/    |___/_(_)_/

EOF

source /etc/stool/scripts/helper.sh

# VARS
echo "Initialization..."

start=`date +%s`

DATABASE_TEMP_PASS=root
NEW_DB_PASS=$(passwordgen);

bash ${SCRIPTS_PATH}partials/init.sh

# APACHE
apacheInstall() {
    sudo apt-get install apache2 -y
    sudo a2enmod proxy_http
    sudo a2enmod rewrite

    sudo ufw allow ssh
    sudo ufw allow in "Apache Full"
    sudo ufw --force enable

    echo "ServerName localhost" >> /etc/apache2/apache2.conf
    echo "ServerTokens Prod" >> /etc/apache2/apache2.conf
    echo "ServerSignature Off" >> /etc/apache2/apache2.conf
    echo "FileETag None" >> /etc/apache2/apache2.conf
    echo "LoadModule headers_module /usr/lib/apache2/modules/mod_headers.so" >> /etc/apache2/apache2.conf
    echo "Header always append X-Frame-Options SAMEORIGIN" >> /etc/apache2/apache2.conf
    echo "Header set X-XSS-Protection \"1; mode=block\"" >> /etc/apache2/apache2.conf
    sudo sed -i "s|Options Indexes FollowSymLinks|Options -Indexes -Includes +FollowSymLinks|" /etc/apache2/apache2.conf
    sudo sed -i "s|Timeout 300|Timeout 60|" /etc/apache2/apache2.conf
}
echo "Installing and configuring Apache Server..."
apacheInstall

phpInstall () {
    bash /etc/stool/scripts/php/setup.sh &> /dev/null
    bash /etc/stool/scripts/php/switch-to-php-7.1.sh &> /dev/null
    phpenmod mcrypt
    phpenmod mbstring
    service apache2 reload
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
    cp ${ABSOLUTE_PATH}.env.example ${ABSOLUTE_PATH}.env
    sudo sed -i "s|localhost|${PUBLIC_IP}/stool|" ${ABSOLUTE_PATH}.env
    ln -s ${ABSOLUTE_PATH}public /var/www/ip/html/stool
    cd $ABSOLUTE_PATH && composer install
    php ${ABSOLUTE_PATH}artisan key:generate
    ln -s ${ABSOLUTE_PATH}artisan /usr/bin/stool
    chmod +x /usr/bin/stool
    cp ${TEMPLATES_PATH}git/post-merge-this ${ABSOLUTE_PATH}.git/hooks/post-merge
    chmod +x ${ABSOLUTE_PATH}.git/hooks/post-merge
    chown -R www-data:www-data /etc/stool
    chown -R root:root $CONFIG_PATH
    chmod -R 755 /etc/stool
    crontab -l | { cat; echo "* * * * * stool schedule:run >> /dev/null 2>&1"; } | crontab -
    crontab -l | { cat; echo "0 0 * * * composer self-update >> /dev/null 2>&1"; } | crontab -
    crontab -l | { cat; echo "0 0 * * 0 apt-get autoremove && apt-get autoclean -y >> /dev/null 2>&1"; } | crontab -
    stool init
    stool migrate --force

    stool view:cache
    stool config:cache
    stool route:cache
}

echo "Installing Server Tool..."
servertoolInstall

stool installation:run
bash ${SCRIPTS_PATH}partials/finish.sh