#!/bin/bash

source /etc/stool/scripts/helper.sh

# YARN
yarnInstall () {
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
    sudo apt-get update -y
    sudo apt-get install --no-install-recommends yarn -y
}
echo "Installing Yarn..."
yarnInstall
