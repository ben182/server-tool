#!/bin/bash

source /etc/server-tool/scripts/helper.sh

# VNSTAT
vnstatInstall () {
    sudo apt-get install vnstat -y
    sudo service vnstat start
}
echo "Installing vnStat..."
vnstatInstall &> /dev/null