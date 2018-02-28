passwordgen() {
    l=$1
    [ "$l" == "" ] && l=16
    tr -dc A-Za-z0-9 < /dev/urandom | head -c ${l} | xargs
}

export DEBIAN_FRONTEND="noninteractive"

DATABASE_PASS=root

sudo apt-get update -y
sudo apt-get upgrade -y

apt install aptitude -y

sudo apt-get install apache2 -y
sudo ufw allow in "Apache Full"

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $DATABASE_PASS"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DATABASE_PASS"
sudo apt-get -y install mysql-server

apt-get install python-software-properties -y
add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt install -y php7.1 php7.1-xml php7.1-mbstring php7.1-mysql php7.1-json php7.1-curl php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd libapache2-mod-php7.1 php7.1-zip

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/bin/composer

chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html


RAND_DB_PASS=$(passwordgen);
RAND_PHPMYADMIN_HTACCESS_USER=$(passwordgen);
RAND_PHPMYADMIN_HTACCESS_PASS=$(passwordgen);

mysql -u root -p"$DATABASE_PASS" -e "UPDATE mysql.user SET authentication_string=PASSWORD('$RAND_DB_PASS') WHERE User='root'"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User=''"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
mysql -u root -p"$DATABASE_PASS" -e "FLUSH PRIVILEGES"

sudo sed -i "s|ROOT_PASSWORD_HERE|$RAND_DB_PASS|" config.json

debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password $RAND_DB_PASS'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password $RAND_DB_PASS'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password $RAND_DB_PASS'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'

apt-get install -y phpmyadmin

phpenmod mcrypt
phpenmod mbstring
service apache2 reload

#cp phpmyadmin.conf /etc/apache2/conf-available/phpmyadmin.conf
service apache2 reload

cp .htaccess /usr/share/phpmyadmin/.htaccess
htpasswd -c -b /etc/phpmyadmin/.htpasswd $RAND_PHPMYADMIN_HTACCESS_USER $RAND_PHPMYADMIN_HTACCESS_PASS

sudo sed -i "s|PHPMYADMIN_HTACCESS_USERNAME|$RAND_PHPMYADMIN_HTACCESS_USER|" config.json
sudo sed -i "s|PHPMYADMIN_HTACCESS_PASSWORD|$RAND_PHPMYADMIN_HTACCESS_PASS|" config.json
