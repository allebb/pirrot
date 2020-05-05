#!/usr/bin/env bash

if [[ -f /etc/os-release ]]; then
    OS=$(grep -w ID /etc/os-release | sed 's/^.*=//')
    VER_NAME=$(grep VERSION /etc/os-release | sed 's/^.*"\(.*\)"/\1/')
    VER_NO=$(grep VERSION_ID /etc/os-release | sed 's/^.*"\(.*\)"/\1/')
 else
    echo "!! INSTALLER ERROR (003) !!"
    echo "The uninstaller could not determine the OS version!"
    echo "Please raise a bug at: https://github.com/allebb/pirrot/issues"
    echo "and ensure you include what version of Raspbian you are trying to"
    echo "remove Pirrot from."
    echo ""
fi

echo "OS detected: ${OS} ${VER_NAME}"

if [[ ! -f /opt/pirrot/build/scripts/os_versions/${OS}_${VER_NO}.uninstall ]]; then
    echo "!! INSTALLER ERROR (004) !!"
    echo "The uninstaller could not find Rasbian version specific install sources,"
    echo "Please raise a bug at: https://github.com/allebb/pirrot/issues"
    echo "and ensure you include what version of Raspbian you are trying to"
    echo "uninstall Pirrot on."
    echo ""
fi

cd /tmp
echo "Uninstalling Pirrot..."
echo ""
echo " - Stopping the Pirrot daemon (if running)..."
sudo /etc/init.d/pirrot stop
echo " - Re-enabling on-board audio..."
sudo sed -i "s|#options snd-usb-audio index=-2|options snd-usb-audio index=-2|" /lib/modprobe.d/aliases.conf
sudo truncate -s 0 /etc/modprobe.d/raspi-blacklist.conf
echo " - Deleting Pirrot Configuration file..."
sudo rm -f /etc/pirrot.conf
echo " - Disabling Pirrot auto-start..."
sudo update-rc.d -f pirrot remove
echo " - Removing the Pirrot daemon..."
sudo rm -f /etc/init.d/pirrot
echo " - Removing Composer..."
sudo rm -f /usr/bin/composer
echo "- Running OS specific cleanup..."
source /opt/pirrot/build/scripts/os_versions/${OS}_${VER_NO}.uninstall
echo ""
echo "- Deleting Pirrot application from disk..."
sudo rm -Rf /opt/pirrot

echo "Done!"
echo ""
# Finished!
echo ""
echo "Please reboot your RaspberryPi now to complete the un-installation!"
echo ""
while true; do
    read -e -p "Restart your device now (y/n)? " r
    case $r in
    [Yy]* ) break;;
    [Nn]* ) exit;
    esac
done
sudo shutdown -r now
