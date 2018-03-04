#!/bin/bash

source /etc/server-tool/scripts/helper.sh

export DEBIAN_FRONTEND="noninteractive"

# VARS
DATABASE_TEMP_PASS=root
NEW_DB_PASS=$(passwordgen);
PHPMYADMIN_HTACCESS_USER=$(passwordgen);
PHPMYADMIN_HTACCESS_PASS=$(passwordgen);
PUBLIC_IP=$(curl -sS ipinfo.io/ip)

# UPDATE
sudo apt-get update -y
sudo apt-get upgrade -y

# APACHE
sudo apt-get install apache2 -y
sudo ufw allow in "Apache Full"

# MYSQL
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $DATABASE_TEMP_PASS"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DATABASE_TEMP_PASS"
sudo apt-get -y install mysql-server

mysql -u root -p"$DATABASE_TEMP_PASS" -e "UPDATE mysql.user SET authentication_string=PASSWORD('$NEW_DB_PASS') WHERE User='root'"
mysql -u root -p"$DATABASE_TEMP_PASS" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
mysql -u root -p"$DATABASE_TEMP_PASS" -e "DELETE FROM mysql.user WHERE User=''"
mysql -u root -p"$DATABASE_TEMP_PASS" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
mysql -u root -p"$DATABASE_TEMP_PASS" -e "FLUSH PRIVILEGES"

sudo sed -i "s|ROOT_PASSWORD_HERE|$NEW_DB_PASS|" $CONFIG_PATH

# PHP
apt-get install python-software-properties -y
add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt install -y php7.1 php7.1-xml php7.1-mbstring php7.1-mysql php7.1-json php7.1-curl php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd libapache2-mod-php7.1 php7.1-zip

# COMPOSER
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/bin/composer

# PHPMYADMIN
debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password $NEW_DB_PASS'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password $NEW_DB_PASS'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password $NEW_DB_PASS'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'

apt-get install -y phpmyadmin

phpenmod mcrypt
phpenmod mbstring
service apache2 reload

# APACHE CONF
cp ${TEMPLATES_PATH}apache/phpmyadmin.conf /etc/apache2/conf-available/phpmyadmin.conf
cp ${TEMPLATES_PATH}apache/dir.conf /etc/apache2/mods-enabled/dir.conf

a2enmod rewrite

cp ${TEMPLATES_PATH}apache/ip.conf /etc/apache2/sites-available/ip.conf
sudo sed -i "s|IP_HERE|$PUBLIC_IP|" /etc/apache2/sites-available/ip.conf
a2ensite ip.conf
mkdir -p /var/www/ip/html
cp ${TEMPLATES_PATH}ip/. /var/www/ip/html -r
#git clone https://github.com/ben182/git-auto-deploy.git /var/www/ip/git-auto-deploy
cp ${ABSOLUTE_PATH}.env.example ${ABSOLUTE_PATH}.env
sudo sed -i "s|localhost|${PUBLIC_IP}/server-tools|" ${ABSOLUTE_PATH}.env
php ${ABSOLUTE_PATH}artisan key:generate
ln -s ${ABSOLUTE_PATH}public /var/www/ip/html/server-tools
composer install -d=$ABSOLUTE_PATH
ln -s ${ABSOLUTE_PATH}artisan /usr/bin/server-tools
chmod +x /usr/bin/server-tools

echo "ServerName localhost" >> /etc/apache2/apache2.conf
sudo sed -i "s|Options Indexes FollowSymLinks|Options -Indexes +FollowSymLinks|" /etc/apache2/apache2.conf

cp ${TEMPLATES_PATH}phpmyadmin/.htaccess /usr/share/phpmyadmin/.htaccess
htpasswd -c -b /etc/phpmyadmin/.htpasswd $PHPMYADMIN_HTACCESS_USER $PHPMYADMIN_HTACCESS_PASS

sudo sed -i "s|PHPMYADMIN_HTACCESS_USERNAME|$PHPMYADMIN_HTACCESS_USER|" $CONFIG_PATH
sudo sed -i "s|PHPMYADMIN_HTACCESS_PASSWORD|$PHPMYADMIN_HTACCESS_PASS|" $CONFIG_PATH

# CERTBOT
add-apt-repository -y ppa:certbot/certbot
apt-get -y update
apt-get install -y python-certbot-apache

# GITHUB SSH KEY
sudo mkdir -m 0700 /var/www/.ssh
sudo chown -R www-data:www-data /var/www/.ssh
sudo -Hu www-data ssh-keygen -f "/var/www/.ssh/id_rsa" -t rsa -b 4096 -N ''
ssh-keyscan github.com >> /var/www/.ssh/known_hosts
ssh-keyscan github.com >> ~/.ssh/known_hosts

SSH_KEY=$(cat /var/www/.ssh/id_rsa.pub)
sudo sed -i "s|GITHUB_SSH|$SSH_KEY|" $CONFIG_PATH

cp /var/www/.ssh/id_rsa /root/.ssh/id_rsa
cp /var/www/.ssh/id_rsa.pub /root/.ssh/id_rsa.pub

# NODE
curl -o- -sS https://raw.githubusercontent.com/creationix/nvm/v0.33.8/install.sh | bash

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm

nvm install node
nvm use node

# APACHE PERMISSIONS
apache_permissions
service apache2 reload