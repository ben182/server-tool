#!/bin/bash

source /etc/stool/scripts/helper.sh

# NODE
userManagement () {
    sudo cp ${TEMPLATES_PATH}sudoers.d/stool /etc/sudoers.d/stool
    sudo adduser --disabled-password --gecos "" stool
}
echo "User Management..."
userManagement &> /dev/null
