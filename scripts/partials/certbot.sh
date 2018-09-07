#!/bin/bash

# CERTBOT
certbotInstall () {
    add-apt-repository -y ppa:certbot/certbot
    apt-get -y update
    apt-get install -y python-certbot-apache
    crontab -l | { cat; echo "0 */12 * * * certbot renew --post-hook \"systemctl reload apache2\""; } | crontab -
}
echo "Installing Certbot..."
certbotInstall &> /dev/null