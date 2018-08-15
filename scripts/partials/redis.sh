#!/bin/bash

source /etc/stool/scripts/helper.sh

REDIS_PASS=$(passwordgen);

# REDIS
redisInstall () {
    apt-get install build-essential tcl -y
    cd /tmp
    curl -O http://download.redis.io/redis-stable.tar.gz
    tar xzvf redis-stable.tar.gz
    cd redis-stable
    make
    #make test
    make install
    mkdir /etc/redis
    cp /tmp/redis-stable/redis.conf /etc/redis
    sudo sed -i "s|supervised no|supervised systemd|" /etc/redis/redis.conf
    sudo sed -i "s|dir ./|dir /var/lib/redis|" /etc/redis/redis.conf
    sudo sed -i "s|# requirepass foobared|requirepass $REDIS_PASS|" /etc/redis/redis.conf
    sudo sed -i "s|REDIS_PASSWORD|$REDIS_PASS|" $CONFIG_PATH
    cp ${TEMPLATES_PATH}redis/redis.service /etc/systemd/system/redis.service

    adduser --system --group --no-create-home redis
    mkdir /var/lib/redis
    chmod 700 /var/lib/redis
    chown redis:redis /var/lib/redis
    chown redis:root /etc/redis/redis.conf
    chmod 600 /etc/redis/redis.conf
    systemctl start redis
    systemctl enable redis

    # REDIS BACKUP
    gem install redis-dump
}
echo "Installing Redis..."
redisInstall &> /dev/null