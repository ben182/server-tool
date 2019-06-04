#!/bin/bash

source /etc/stool/scripts/helper.sh

echo "User Management..."
sudo cp ${TEMPLATES_PATH}sudoers.d/stool /etc/sudoers.d/stool
sudo adduser --disabled-password --gecos "" stool
sudo usermod -a -G www-data stool

sudo cp ${TEMPLATES_PATH}.bash_aliases /home/stool/.bash_aliases
sudo cat ${TEMPLATES_PATH}.profile >> /home/stool/.profile

echo "AllowUsers stool" >> /etc/ssh/sshd_config

# SSH PORT
echo "Port 12920" >> /etc/ssh/sshd_config
sudo ufw allow 12920/tcp

# DISABLE ROOT LOGIN
sudo sed -i "s|PermitRootLogin yes|PermitRootLogin no|" /etc/ssh/sshd_config

sudo systemctl restart ssh

sudo -Hu stool ssh-keygen -f "/home/stool/.ssh/id_rsa" -t rsa -b 4096 -N ''
cp /root/.ssh/authorized_keys /home/stool/.ssh/

sudo chown -R stool:stool /home/stool/.ssh/
chmod 600 /home/stool/.ssh/authorized_keys
chmod 400 /home/stool/.ssh/id_rsa

mkdir /home/stool/.stool
