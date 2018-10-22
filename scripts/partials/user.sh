#!/bin/bash

source /etc/stool/scripts/helper.sh

# NODE
userManagement () {
    sudo cp ${TEMPLATES_PATH}sudoers.d/stool /etc/sudoers.d/stool
    sudo adduser --disabled-password --gecos "" stool

    echo "AllowUsers stool root" >> /etc/ssh/sshd_config
    sudo systemctl restart ssh

    sudo -Hu stool ssh-keygen -f "/home/stool/.ssh/id_rsa" -t rsa -b 4096 -N ''
    cp /root/.ssh/authorized_keys /home/stool/.ssh/

    sudo chown -R stool:stool /home/stool/.ssh/
    chmod 600 /home/stool/.ssh/authorized_keys
}
echo "User Management..."
userManagement &> /dev/null
