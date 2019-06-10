#!/bin/bash

source /etc/stool/scripts/helper.sh

echo "Netdata..."

HTACCESS_USER=$(passwordgen);
HTACCESS_PASS=$(passwordgen);

sudo apt-get install netdata -y

cp ${TEMPLATES_PATH}apache/netdata.conf /etc/apache2/sites-available/netdata.conf
sudo sed -i "s|IP_HERE|$PUBLIC_IP|" /etc/apache2/sites-available/netdata.conf
a2ensite netdata.conf

htpasswd -c -b /etc/netdata/.htpasswd $HTACCESS_USER $HTACCESS_PASS
sudo sed -i "s|NETDATA_HTACCESS_USERNAME|$HTACCESS_USER|" $CONFIG_PATH
sudo sed -i "s|NETDATA_HTACCESS_PASSWORD|$HTACCESS_PASS|" $CONFIG_PATH
