#!/usr/bin/env bash
cd /tmp
echo "Uninstalling Pirrot..."
echo ""
echo " - Stopping Pirrot daemon..."
sudo /etc/init.d/pirrot stop
sleep 3
echo " - Re-enabling on-board audio..."
sudo sed -i "s|#options snd-usb-audio index=-2|options snd-usb-audio index=-2|" /lib/modprobe.d/aliases.conf
sudo truncate -s 0 /etc/modprobe.d/raspi-blacklist.conf
echo " - Deleting Pirrot Configuration file..."
sudo rm -f /etc/pirrot.conf
echo " - Removing the Pirrot daemon..."
sudo rm -f /etc/init.d/pirrot
echo " - Removing the Pirrot application..."
sudo rm -Rf /opt/pirrot
echo " - Removing Composer..."
sudo rm -f /usr/bin/composer
echo ""
echo "Done!"
echo ""
# Finished!
echo ""
echo "Please reboot your RaspberryPi now to complete the uninstallation!"
echo ""
while true; do
    read -e -p "Restart your device now (y/n)? " r
    case $r in
    [Yy]* ) break;;
    [Nn]* ) exit;
    esac
done
shutdown -r now
