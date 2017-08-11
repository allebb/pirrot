#!/usr/bin/env bash

echo "Building/installing Piplex..."

echo " # Checking if log files exist..."
if [ ! -f /var/log/piplex.log ]; then
    echo " - Creating log file and setting permissions..."
    sudo touch /var/log/piplex.log
    sudo chmod 0644 /var/log/piplex.log
fi

# Copy the PHP file to a new file (with no extention)
echo " # Deploying application..."
sudo cp /opt/piplex/piplex.php /opt/piplex/piplex

# Chmod it...
echo " # Setting execution bit on /opt/piplex/piplex..."
sudo chmod +x /opt/piplex/piplex

# Copy the init.d script...
echo " # Installing the daemon..."
sudo cp /opt/piplex/init.d/piplex /etc/init.d/piplex
sudo chmod +x /etc/init.d/piplex

# Run composer install...
echo " # Installing Composer "
sudo composer install --working-dir /opt/piplex

echo "All done!"