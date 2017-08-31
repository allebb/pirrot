#!/usr/bin/env bash

echo "Installing Pirrot..."

PACKAGES=$(grep -vE "^\s*#" /opt/pirrot/build/scripts/packages.txt  | tr "\n" " ")
xargs -a <(awk '! /^ *(#|$)/' "$PACKAGES") -r -- sudo apt-get install

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
chmod 666 -Rf /opt/pirrot/storage

# Copy the init.d script...
echo " - Installing the daemon..."
sudo cp /opt/pirrot/build/init.d/pirrot /etc/init.d/pirrot
sudo chmod +x /etc/init.d/pirrot
sudo update-rc.d pirrot defaults

# Installing composer
echo " - Installing Composer..."
wget https://getcomposer.org/composer.phar
mv composer.phar /usr/bin/composer
chmod +x /usr/bin/composer

# Run composer install...
echo " - Installing Pirrot Dependencies..."
sudo composer install --working-dir /opt/pirrot

# Finished!
echo ""
echo "Please reboot your RaspberryPi now to enable Pirrot!"
echo ""
