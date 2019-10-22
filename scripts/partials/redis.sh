#!/bin/bash

source /etc/stool/scripts/helper.sh

REDIS_PASS=$(openssl rand 60 | openssl base64 -A);

# REDIS
redisInstall () {
    sudo apt update -y
    sudo apt install redis-server -y

    sudo sed -i "s|supervised no|supervised systemd|" /etc/redis/redis.conf
    sudo sed -i "s|# bind 127.0.0.1 ::1|bind 127.0.0.1|" /etc/redis/redis.conf
    sudo sed -i "s|# requirepass foobared|requirepass $REDIS_PASS|" /etc/redis/redis.conf
    sudo sed -i "s|REDIS_PASSWORD|$REDIS_PASS|" $CONFIG_PATH

    sudo systemctl restart redis
}
echo "Installing Redis..."
redisInstall
