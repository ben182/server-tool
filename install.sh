export DEBIAN_FRONTEND="noninteractive"

sudo apt-get update -y
sudo apt-get upgrade -y

apt install aptitude -y

sudo apt-get install apache2 -y
sudo ufw allow in "Apache Full"

sudo apt-get install mysql-server -y

apt-get install python-software-properties -y
add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt install -y php7.1 php7.1-xml php7.1-mbstring php7.1-mysql php7.1-json php7.1-curl php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd libapache2-mod-php7.1 php7.1-zip

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/bin/composer

chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

DATABASE_PASS=K11Janina!
mysqladmin -u root password "$DATABASE_PASS"
mysql -u root -p"$DATABASE_PASS" -e "UPDATE mysql.user SET Password=PASSWORD('$DATABASE_PASS') WHERE User='root'"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User=''"
mysql -u root -p"$DATABASE_PASS" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
mysql -u root -p"$DATABASE_PASS" -e "FLUSH PRIVILEGES"