#!/usr/bin/env bash

echo "Uninstalling Pirrot..."

echo " - Deleting Pirrot Configuration file..."
rm -f /etc/pirrot.conf
echo " - Stopping Pirrot daemon..."
sudo /etc/init.d/pirrot stop
echo " - Removing the Pirrot daemon..."
rm -f /etc/init.d/pirrot
echo " - Removing the Pirrot application..."
rm -Rf /opt/pirrot
echo " - Removing Composer..."
rm -f /usr/bin/composer
echo ""
echo "Done!"
echo ""
