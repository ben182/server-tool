#!/bin/bash

source /etc/stool/scripts/helper.sh

echo "Netdata..."

sudo apt-get update -y
bash <(curl -Ss https://my-netdata.io/kickstart.sh) --dont-wait --stable-channel

sudo sed -i "s|# history = 3996|history = 43200|" /etc/netdata/netdata.conf

sudo cp /usr/lib/netdata/conf.d/health_alarm_notify.conf /etc/netdata/health_alarm_notify.conf
sudo chown root:netdata /etc/netdata/health_alarm_notify.conf

sudo service netdata restart

# MYSQL
sudo apt-get install python-mysqldb -y
