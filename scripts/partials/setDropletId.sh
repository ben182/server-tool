# sets / refreshes the droplet id
export DROPLET_ID=$(curl -s http://169.254.169.254/metadata/v1/id)
sed -i "/DROPLET_ID=\"${DROPLET_ID}\"/d" /etc/environment
echo "DROPLET_ID=\"${DROPLET_ID}\"" >> /etc/environment