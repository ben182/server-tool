passwordgen() {
    l=$1
    [ "$l" == "" ] && l=16
    tr -dc A-Za-z0-9 < /dev/urandom | head -c ${l} | xargs
}

export DEBIAN_FRONTEND="noninteractive"

sudo apt-get update -y
sudo apt-get upgrade -y

apt install aptitude -y

sudo apt-get install apache2 -y
sudo ufw allow in "Apache Full"

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password root"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password root"
sudo apt-get -y install mysql-server

apt-get install python-software-properties -y
add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt install -y php7.1 php7.1-xml php7.1-mbstring php7.1-mysql php7.1-json php7.1-curl php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd libapache2-mod-php7.1 php7.1-zip

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/bin/composer

chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

DATABASE_PASS=root
RAND_PASS=$(passwordgen);

mysql -u root -p"$DATABASE_PASS" -e "UPDATE mysql.user SET authentication_string=PASSWORD('$RAND_PASS') WHERE User='root'"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User=''"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
mysql -u root -p"$DATABASE_PASS" -e "FLUSH PRIVILEGES"