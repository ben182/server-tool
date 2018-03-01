#!/bin/bash

git clone https://github.com/ben182/server-tool.git /etc/server-tool
chmod +x /etc/server-tool/install.sh
chmod +x /etc/server-tool/vhost.sh
bash /etc/server-tool/install.sh
