#!/usr/bin/env bash

echo "Building/installing Pirrot..."

echo " # Checking for Pirrot configuration..."
if [ ! -f /etc/piplex.conf ]; then
    echo " - Creating new pirrot.conf from template..."
    sudo cp /opt/piplex/build/configs/pirrot_default.conf /etc/pirrot.conf
    sudo chmod 0644 /etc/pirrot.conf
fi

echo " # Checking if log files exist..."
if [ ! -f /var/log/pirrot.log ]; then
    echo " - Creating log file and setting permissions..."
    sudo touch /var/log/pirrot.log
    sudo chmod 0644 /var/log/pirrot.log
fi

# Chmod it...
echo " # Setting execution bit on /opt/pirrot/pirrot..."
sudo chmod +x /opt/piplex/piplex

# Copy the init.d script...
echo " # Installing the daemon..."
sudo cp /opt/pirrot/init.d/pirrot /etc/init.d/pirrot
sudo chmod +x /etc/init.d/pirrot

# Run composer install...
echo " # Installing Composer "
sudo composer install --working-dir /opt/pirrot

echo "All done!"