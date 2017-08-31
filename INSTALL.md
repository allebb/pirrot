# Installation

Pirrot is designed and tested to be run on the RaspberryPi, during development and testing I used the Raspbian Stretch.

The following steps are required to install Pirrot on your RaspberryPi:

```shell
cd ~
wget https://github.com/allebb/pirrot/archive/X.X.X.tar.gz
sudo mkdir /opt/pirrot
sudo tar xf pirrot-X.X.X.tar.gz -C /opt/pirrot --strip 1
cd /opt/pirrot
sudo make install
```

4. Now reboot your RaspberryPi using ``reboot``.

Following the reboot, your repeater will be active and ready to run!

# Uninstalling

If you wish to uninstall Pirrot at a later date, you can run:

```shell
cd /opt/pirrot
make uninstall
```

