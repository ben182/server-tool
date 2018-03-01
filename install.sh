passwordgen() {
    l=$1
    [ "$l" == "" ] && l=16
    tr -dc A-Za-z0-9 < /dev/urandom | head -c ${l} | xargs
}

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

sudo sed -i "s|ROOT_PASSWORD_HERE|$NEW_DB_PASS|" config.json

# PHP
apt-get install python-software-properties -y
add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt install -y php7.1 php7.1-xml php7.1-mbstring php7.1-mysql php7.1-json php7.1-curl php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd libapache2-mod-php7.1 php7.1-zip

# COMPOSER
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/bin/composer

# APACHE PERMISSIONS
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

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
cp apache/phpmyadmin.conf /etc/apache2/conf-available/phpmyadmin.conf
cp apache/dir.conf /etc/apache2/mods-enabled/dir.conf

cp apache/ip.conf /etc/apache2/sites-available/ip.conf
sudo sed -i "s|IP_HERE|$PUBLIC_IP|" /etc/apache2/sites-available/ip.conf
a2ensite ip.conf
cp -a ip /var/www/ip

echo "ServerName localhost" >> /etc/apache2/apache2.conf
service apache2 reload

cp phpmyadmin/.htaccess /usr/share/phpmyadmin/.htaccess
htpasswd -c -b /etc/phpmyadmin/.htpasswd $PHPMYADMIN_HTACCESS_USER $PHPMYADMIN_HTACCESS_PASS

sudo sed -i "s|PHPMYADMIN_HTACCESS_USERNAME|$PHPMYADMIN_HTACCESS_USER|" config.json
sudo sed -i "s|PHPMYADMIN_HTACCESS_PASSWORD|$PHPMYADMIN_HTACCESS_PASS|" config.json

# CERTBOT
add-apt-repository -y ppa:certbot/certbot
apt-get -y update
apt-get install -y python-certbot-apache

# GITHUB SSH KEY
sudo mkdir -m 0700  /var/www/.ssh
sudo chown -R www-data:www-data /var/www/.ssh
sudo -u www-data ssh-keygen -f "/var/www/.ssh/id_rsa" -t rsa -b 4096 -N ''
SSH_KEY=$(cat /var/www/.ssh/id_rsa.pub)
sudo sed -i "s|GITHUB_SSH|$SSH_KEY|" config.json

# NODE
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.8/install.sh | bash

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm

nvm install node
nvm use node