#!/usr/bin/env bash

echo "Uninstalling Pirrot..."

echo " - Deleting Pirrot Configuration file..."
rm /etc/pirrot.conf
echo " - Stopping Pirrot daemon..."
/etc/init.d/pirrot stop
echo " - Removing the Pirrot daemon..."
rm /etc/init.d/pirrot
echo " - Removing the Pirrot application..."
rm -Rf /opt/pirrot
echo " - Removing Composer..."
rm -f /usr/bin/composer
echo ""
echo "Done!"
echo ""