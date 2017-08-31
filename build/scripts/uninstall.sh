#!/usr/bin/env bash

echo "Uninstalling Pirrot..."

echo " - Deleting Pirrot Configuration file..."
sudo rm -f /etc/pirrot.conf
echo " - Stopping Pirrot daemon..."
sudo /etc/init.d/pirrot stop
echo " - Removing the Pirrot daemon..."
sudo rm -f /etc/init.d/pirrot
echo " - Removing the Pirrot application..."
sudo rm -Rf /opt/pirrot
echo " - Removing Composer..."
sudo rm -f /usr/bin/composer
echo ""
echo "Done!"
echo ""
