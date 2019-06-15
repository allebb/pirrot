#!/usr/bin/env bash

echo "Installing Pirrot..."

PACKAGES=$(grep -vE "^\s*#" /opt/pirrot/build/scripts/packages.txt  | tr "\n" " ")
sudo apt-get install -y $PACKAGES

echo " # Checking for Pirrot configuration..."
if [ ! -f /etc/pirrot.conf ]; then
    echo " - Creating new pirrot.conf from template..."
    sudo cp /opt/pirrot/build/configs/pirrot_default.conf /etc/pirrot.conf
    sudo chmod 0644 /etc/pirrot.conf
fi

echo " # Checking if log files exist..."
if [ ! -f /var/log/pirrot.log ]; then
    echo " - Creating log file and setting permissions..."
    sudo touch /var/log/pirrot.log
    sudo chmod 0644 /var/log/pirrot.log
fi

# Chmod it...
echo " - Setting execution bit on /opt/pirrot/pirrot..."
sudo chmod +x /opt/pirrot/pirrot

# Chmod storage directories
sudo mkdir /opt/pirrot/storage
sudo mkdir /opt/pirrot/storage/input
sudo mkdir /opt/pirrot/storage/recordings
sudo chmod -R 664 /opt/pirrot/storage

# Copy the init.d script...
echo " - Installing the daemon..."
sudo cp /opt/pirrot/build/init.d/pirrot /etc/init.d/pirrot
sudo chmod +x /etc/init.d/pirrot
sudo update-rc.d pirrot defaults

# Installing composer
echo " - Installing Composer..."
wget https://getcomposer.org/composer.phar
sudo mv composer.phar /usr/bin/composer
sudo chmod +x /usr/bin/composer

# Run composer install...
echo " - Installing Pirrot Dependencies..."
sudo composer install --working-dir /opt/pirrot

# Disable onboard audio device (to enable USB device)
echo " - Disabling on-board audio device"
sudo sed -i "s|options snd-usb-audio index=-2|#options snd-usb-audio index=-2|" /lib/modprobe.d/aliases.conf
echo "blacklist snd_bcm2835" | sudo tee -a /etc/modprobe.d/raspi-blacklist.conf

# Finished!
echo ""
echo "Please reboot your RaspberryPi now to enable Pirrot!"
echo ""
while true; do
    read -e -p "Restart your device now (y/n)? " r
    case $r in
    [Yy]* ) break;;
    [Nn]* ) exit;
    esac
done
sudo shutdown -r now
