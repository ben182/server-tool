# Set correct localhost host
sudo sed -i".bak" "/127.0.0.1/d" /etc/hosts
sudo sed -i".bak" "/127.0.1.1/d" /etc/hosts

HOSTNAME=$(hostname)
HOSTS_LINE="127.0.0.1 $HOSTNAME.de localhost $HOSTNAME"
sudo -- sh -c -e "echo '$HOSTS_LINE' >> /etc/hosts";

# Disable IPv6
echo "net.ipv6.conf.all.disable_ipv6=1" >> /etc/sysctl.conf
echo "net.ipv6.conf.default.disable_ipv6=1" >> /etc/sysctl.conf
sudo sysctl -p
