#!/bin/bash

source /etc/stool/scripts/helper.sh

finish () {
    # WELCOME MESSAGE
    cp ${TEMPLATES_PATH}update-motd.d/99-stool /etc/update-motd.d/99-stool
    sudo chmod +x /etc/update-motd.d/99-stool

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
echo 'Type "stool installation:test" to test your installation'
