#!/bin/bash

source /etc/server-tool/scripts/helper.sh

finish () {
    # WELCOME MESSAGE
    cp ${TEMPLATES_PATH}update-motd.d/99-server-tools /etc/update-motd.d/99-server-tools
    sudo chmod +x /etc/update-motd.d/99-server-tools

    # TIMEZONE
    ln -sf /usr/share/zoneinfo/Europe/Berlin /etc/localtime

    # APACHE PERMISSIONS
    apache_permissions
    service apache2 reload
}
echo "Finish..."
finish &> /dev/null

# echo "Installation successfully completed in $((($(date +%s)-$start)/60)) minutes"
echo "All sensitive data is written to $CONFIG_PATH"
echo 'Important! Please log out of this ssh session and start a new one!'
echo 'Type "server-tools installation:test" to test your installation'
