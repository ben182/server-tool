#!/bin/bash

source /etc/stool/scripts/helper.sh

# NODE
nodeInstall () {
    curl -o- -sS https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm

    nvm install node
    nvm use node
    n=$(which node);n=${n%/bin/node}; chmod -R 755 $n/bin/*; sudo cp -r $n/{bin,lib,share} /usr/local
}
echo "Installing Node JS..."
nodeInstall &> /dev/null
