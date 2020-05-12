#!/usr/bin/env bash

echo "Installing Pirrot..."

sudo apt-get update

if [[ -f /etc/os-release ]]; then
    OS=$(grep -w ID /etc/os-release | sed 's/^.*=//')
    VER_NAME=$(grep VERSION /etc/os-release | sed 's/^.*"\(.*\)"/\1/')
    VER_NO=$(grep VERSION_ID /etc/os-release | sed 's/^.*"\(.*\)"/\1/')
 else
    echo "!! INSTALLER ERROR (001) !!"
    echo "The installer could not determine the OS version!"
    echo "Please raise a bug at: https://github.com/allebb/pirrot/issues"
    echo "and ensure you include what version of Raspbian you are trying to"
    echo "install Pirrot on."
    echo ""
fi

echo "OS detected: ${OS} ${VER_NAME}"

if [[ -f /opt/pirrot/build/scripts/os_versions/${OS}_${VER_NO}.install ]]; then
    echo "Running version specific installer steps..."
    source /opt/pirrot/build/scripts/os_versions/${OS}_${VER_NO}.install
 else
    echo "!! INSTALLER ERROR (002) !!"
    echo "The installer could not find Rasbian version specific install sources,"
    echo "Please raise a bug at: https://github.com/allebb/pirrot/issues"
    echo "and ensure you include what version of Raspbian you are trying to"
    echo "install Pirrot on."
    echo ""
fi


echo " # Checking for Pirrot configuration..."
if [[ ! -f /etc/pirrot.conf ]]; then
    echo " - Creating new pirrot.conf from template..."
    sudo cp /opt/pirrot/build/configs/pirrot_default.conf /etc/pirrot.conf
    sudo chmod 0644 /etc/pirrot.conf
fi

echo " # Checking if log files exist..."
if [[ ! -f /var/log/pirrot.log ]]; then
    echo " - Creating log file and setting permissions..."
    sudo touch /var/log/pirrot.log
    sudo chmod 0644 /var/log/pirrot.log
fi

# Chmod it...
echo " - Setting execution bit on /opt/pirrot/pirrot..."
sudo chmod +x /opt/pirrot/pirrot

# Make "pirrot" accessible from the PATH...
sudo ln -s /opt/pirrot/pirrot /usr/local/bin/pirrot


# Chmod storage directories
sudo mkdir /opt/pirrot/storage
sudo mkdir /opt/pirrot/storage/input
sudo mkdir /opt/pirrot/storage/recordings
sudo chmod -R 755 /opt/pirrot/storage

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
sudo composer install --working-dir /opt/pirrot --no-dev --no-interaction

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
