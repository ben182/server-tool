export DEBIAN_FRONTEND="noninteractive"

sudo apt-get update
sudo apt-get upgrade

sudo apt-get install apache2
sudo ufw allow in "Apache Full"

sudo apt-get install mysql-server

apt-get install python-software-properties
add-apt-repository ppa:ondrej/php
apt-get update
apt install php7.1 php7.1-xml php7.1-mbstring php7.1-mysql php7.1-json php7.1-curl php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd libapache2-mod-php7.1 php7.1-zip

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/bin/composer

chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html