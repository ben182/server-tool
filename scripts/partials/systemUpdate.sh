#!/bin/bash

# UPDATE
systemUpdate () {
    sudo apt-get update -y
    sudo apt-get upgrade -y
}
echo "System Update..."
systemUpdate &> /dev/null