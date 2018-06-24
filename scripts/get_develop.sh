#!/bin/bash

git clone https://github.com/ben182/server-tool.git -b develop /etc/server-tool
chmod +x -R /etc/server-tool/scripts
bash /etc/server-tool/scripts/install.sh
