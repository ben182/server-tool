#!/bin/bash

source /etc/stool/scripts/helper.sh

# GITHUB SSH KEY
sshKey () {
    sudo mkdir -m 0700 /var/www/.ssh
    sudo chown -R www-data:www-data /var/www/.ssh
    sudo -Hu www-data ssh-keygen -f "/var/www/.ssh/id_rsa" -t rsa -b 4096 -N ''
    ssh-keyscan github.com >> /var/www/.ssh/known_hosts
    ssh-keyscan github.com >> ~/.ssh/known_hosts

    SSH_KEY=$(cat /var/www/.ssh/id_rsa.pub)
    sudo sed -i "s|GITHUB_SSH|$SSH_KEY|" $CONFIG_PATH

    cp /var/www/.ssh/id_rsa /root/.ssh/id_rsa
    cp /var/www/.ssh/id_rsa.pub /root/.ssh/id_rsa.pub
}
echo "Installing SSH Key..."
sshKey &> /dev/null