#!/bin/bash

git clone https://github.com/ben182/server-tool.git -b develop /etc/stool
chmod +x -R /etc/stool/scripts
bash /etc/stool/scripts/install.sh
