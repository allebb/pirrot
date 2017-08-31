# Installation

Pirrot is designed and tested to be run on the RaspberryPi, during development and testing I used the Raspbian Stretch.

The following steps are required to install Pirrot on your RaspberryPi:

1. Download the [latest release](#)
2. Extract the files as follows using ``sudo tar xf pirrot-X.X.X.tar.gz -C /opt/pirrot``
3. Run the installed as follows ``cd /opt/pirrot && make install``
4. Now reboot your RaspberryPi using ``reboot``.

Following the reboot, your repeater will be active and ready to run!

# Uninstalling

If you wish to uninstall Pirrot at a later date, you can run:

```shell
cd /opt/pirrot
make uninstall
```

