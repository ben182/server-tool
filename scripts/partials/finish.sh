#!/bin/bash

source /etc/stool/scripts/helper.sh

finish () {
    # WELCOME MESSAGE
    cp ${TEMPLATES_PATH}update-motd.d/99-stool /etc/update-motd.d/99-stool
    sudo chmod +x /etc/update-motd.d/99-stool

    # TIMEZONE
    ln -sf /usr/share/zoneinfo/Europe/Berlin /etc/localtime

    # GIT CONFIG
    git config --global --unset-all core.filemode
    git config --global core.filemode false

    # FIR FOR LARAVEL MIX
    sudo apt-get install libpng-dev -y

    # UNZIP
    sudo apt-get install unzip -y

    # PUPPETEER REQUIREMENTS
    sudo apt-get install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget
    sudo npm install --global --unsafe-perm puppeteer

    # AUTOMATIC SECURITY UPDATES
    sudo apt install -y unattended-upgrades
    sudo cp ${TEMPLATES_PATH}50unattended-upgrades /etc/apt/apt.conf.d/50unattended-upgrades
    sudo cp ${TEMPLATES_PATH}20auto-upgrades /etc/apt/apt.conf.d/20auto-upgrades

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
