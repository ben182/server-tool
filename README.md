![alt text](stool_v2_min.png "Logo")

A fully featured LAMP+ installer and management tool for Ubuntu servers

## Install

**Latest stable version**

`bash <(curl -s https://raw.githubusercontent.com/ben182/server-tool/master/scripts/get.sh)`

**Nightly builds**

`bash <(curl -s https://raw.githubusercontent.com/ben182/server-tool/master/scripts/get_develop.sh)`

`bash <(curl -s https://raw.githubusercontent.com/ben182/server-tool/feature/v2/scripts/get_v2.sh)`

This script is supposed to be run on a fresh Ubuntu machine. It will install:

* Apache
* MySQL
* phpMyAdmin
* PHP 5.6, 7.0, 7.1, 7.2, 7.3
* Composer
* Certbot
* Node.js
* Yarn
* Redis
* vnStat

Furthermore it will configure the complete server to be as secure as it can get:
* MySQL Secure Installation
* phpMyAdmin with Basic Authentication
* Secure Apache Configuration
* Redis Password

In the everyday usage the server-tools can also manage your server:
* Adding vHosts
* Adding SSL
* PHP version switch
* Creating deamons
* Managing Floating IP's
* Create MySQL databases and users
* Change PHP settings
* Adding Basic Auth protection to vHosts
* Installing and configuring Wordpress
