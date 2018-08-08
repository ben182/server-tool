---
home: true
---

[Get Started](/installation.md)

This tool is supposed to be run on a fresh ubuntu maschine. It will install:
* Apache
* MySQL
* PHP 5.6, 7.0, 7.1, 7.2

Optional:
* phpMyAdmin
* Composer
* Certbot
* An SSH Key
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
    * Complete Server Backups through DigitalOcean Snapshots
* Backups can be stored on DigitalOcean Spaces
* Git Auto Deploy
* Version Check for Node.js etc.
* Adding vHosts
* Provisioning SSL Certificates
* Application Installs (Set up vHost, clone Git repo, laravel specific configuration, create mysql database, npm install, create git post hook)
* PHP Version Switch