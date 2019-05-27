passwordgen() {
    l=$1
    [ "$l" == "" ] && l=16
    tr -dc A-Za-z0-9 < /dev/urandom | head -c ${l} | xargs
}

apache_permissions() {
    chown -R stool:stool /home/stool
    chmod -R 755 /home/stool
    chmod g+s /home/stool
}

ABSOLUTE_PATH=/etc/stool/
CONFIG_PATH=${ABSOLUTE_PATH}config.json
SCRIPTS_PATH=${ABSOLUTE_PATH}scripts/
TEMPLATES_PATH=${ABSOLUTE_PATH}templates/
PUBLIC_IP=$(curl -sS ipinfo.io/ip)
