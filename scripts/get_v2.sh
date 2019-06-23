#!/bin/bash

git clone https://github.com/ben182/server-tool.git -b feature/v2 /etc/stool
chmod +x -R /etc/stool/scripts
bash /etc/stool/scripts/install.sh
