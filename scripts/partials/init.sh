#!/bin/bash

source /etc/stool/scripts/helper.sh

bash ${SCRIPTS_PATH}partials/setDropletId.sh

cp ${ABSOLUTE_PATH}config.example.json ${ABSOLUTE_PATH}config.json
cp ${ABSOLUTE_PATH}installation.example.json ${ABSOLUTE_PATH}installation.json

bash ${SCRIPTS_PATH}partials/systemUpdate.sh