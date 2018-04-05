A fully featured Digitalocean installer and management tool optimized for Laravel

## Install
wget -O - https://raw.githubusercontent.com/ben182/server-tool/master/scripts/get.sh | bash

This script is supposed to be run on a fresh ubuntu maschine. It will install:
* Apache
* MySQL
* phpMyAdmin
* PHP 7.1
* Composer
* Certbot
* A Github SSH Key
* Node.js
* Redis
* vnStat

Furthermore it will configure the complete server to be as secure as it can get:
* MySQL Secure Installation
* phpMyAdmin with Basic Authentication
* Secure Apache Configuration
* Redis Password

In the everyday usage the server-tools can also manage your server:
* Automatic Backups
    * MySQL
    * Redis
    * Complete Server Backups through Snapshots
* Backups can be stored on DigitalOcean Spaces
* Git Auto Deploy
* Version Check for Node.js etc.
* Adding vHosts
* Adding SSL
* Application Installs (Set up vHost, clone Git repo, laravel specific configuration, create mysql database, npm install, create git post hook)