#!/bin/bash

source /etc/stool/scripts/helper.sh

# NODE
nodeInstall () {
    curl -sL https://deb.nodesource.com/setup_10.x | sudo -E bash -
    sudo apt-get install -y nodejs
    # curl -sL https://deb.nodesource.com/setup_10.x -o nodesource_setup.sh
    # curl -o- -sS https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash

    # export NVM_DIR="$HOME/.nvm"
    # [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm

    # nvm install node
    # nvm use node
    # n=$(which node);n=${n%/bin/node}; chmod -R 755 $n/bin/*; sudo cp -r $n/{bin,lib,share} /usr/local
}
echo "Installing Node JS..."
nodeInstall &> /dev/null
