# Pirrot - A Simplex Radio Repeater controller for RaspberryPi

The Pirrot project exists to offer a cheaper (and fun) alternative to buying a dedicated simplex repeater controller.

Pirrot also offers other operation modes and features that most off-the-shelf simplex repeaters do not come with as standard such as automatic identification and the ability to operate in "surveillance mode".

You can find a list of user projects to get inspiration from [below](https://github.com/allebb/pirrot/blob/master/README.md#pirrot-being-used-in-the-wild).

## Features

* Ability to set a courtesy tone on end of transmission.
* Ability to configure the repeater to identify itself automatically on a schedule.
* Ability to enable or disable automatic repeater identifier (disabled by default).
* Ability to transmit on VOX (voice activation) or COR (carrier signal from radio to trigger a PTT relay).
* Save recordings of received transmissions (disabled by default).

See the full list of configuration items (features) on the [CONFIGURATION page](CONFIGURATION.md).

## Hardware requirements

The simplest hardware/software requirements are:

1. **A RaspberryPi** - I test Pirrot on the **RaspberryPi 3, 4 and the Zero W** but should work on others too!
2. Raspbian version **9 (stretch)** or **10 (buster)** running on your Pi (___either the "lite" or "desktop" versions___ but I recommend the "lite" version as it uses less system resources especially if you intend to run it headless).
3. **An external USB sound card**.

I will update the installer to support newer versions of Raspbian as and when they are released by the Raspberry Pi foundation.

Assuming you wish to use this with a radio transceiver, you will also need to wire up the transceiver's PTT button to the PTT relay pin on your RaspberryPi's GPIO pin (by default this is GPIO Pin #23, although you can adjust as required in the configuration file found at ``/etc/pirrot.conf``).

The audio in (receive) and audio out (transmit) connectors from your radio will need to be connected to the external USB sound card using the Mic Jack (for Transmit) and Speaker Jack (for Receive).

If you plan to use the repeater in COS/COR mode you will need to connect your COR GPIO pin to the transceiver. 

## Installation

Pirrot can be installed using Git or by downloading the latest tarball ([find the version number here](https://github.com/allebb/pirrot/releases)), installation steps are as follows:

To install using Git (**the recommended way** - this supports easy future updates using 'pirrot update' command) run the following commands at the terminal:

```shell
sudo apt-get install -y git
sudo git clone --single-branch --branch latest-stable https://github.com/allebb/pirrot /opt/pirrot
cd /opt/pirrot
sudo make install
```

Alternatively you can install Pirrot by downloading the latest tarball, **replace the X.X.X.X with the latest version available from the ([releases page](https://github.com/allebb/pirrot/releases))**:

```shell
cd ~
wget https://github.com/allebb/pirrot/archive/vX.X.X.tar.gz
sudo mkdir /opt/pirrot
sudo tar xf vX.X.X.tar.gz -C /opt/pirrot --strip 1
cd /opt/pirrot
sudo make install
```

Once installed, Pirrot will start automatically at boot up (ensure your USB audio adapter is connected though!)

## Configuration options

The Pirrot configuration file is found in ``/etc/pirrot.conf``, a full list of settings and descriptions can be found on the [configuration](CONFIGURATION.md) page.

When making changes to this file please ensure that you restart the Pirrot daemon by running ``sudo service pirrot restart`` to ensure that the changes to take affect.

## Sound adjustment

Using the ``alsamixer`` command in the terminal you can adjust your microphone and speaker volumes if required.

If you do adjust the volume, remember that to permanently save these settings use the ``alsactl store`` command to ensure they are kept after reboot.

## Default GPIO pins

By default, the Pirrot configuration file, located in ``/etc/pirrot.conf`` has default GPIO pins configured, these are as follows:

* __GPIO 18__ - COR Signal (Input/Relay) - _Triggered when the radio squelch is opened._
* __GPIO 23__ - PTT Switch (Output/Relay)  - _Triggers the PTT on the radio to transmit._
* __GPIO 17__ - Power/Ready LED (Output/LED) - _Illuminates when the Pirrot daemon is running (indicating that the repeater is ready and working)._
* __GPIO 27__ - Receive LED (Output/LED) - _Receiving transmission (Illuminated when COR signal is high **non-functional in VOX mode**)._
* __GPIO 22__ - Transmit LED (Output/LED) - _Transmitting transmission (Illuminated when the repeater is ident-ing/playing back a transmission)._

__Remember: When making changes to the ``/etc/pirrot.conf`` file you must restart the daemon using the ``sudo /etc/init.d/pirrot restart`` command.__

# Uninstalling

If you wish to uninstall Pirrot at a later date, you can run:

```shell
cd /opt/pirrot
sudo make uninstall
```

# PCB Interface

This PCB interface design and PCB schematic has kindly been designed and contributed by Peter Javorsky ([https://github.com/tekk](@tekk); and provides a simple interface to connect your radio(s) to a Raspberry Pi running Pirrot.

![Schematic](pcb/schematic.png)

![PCB](pcb/pcb.png)

You can find out more about the [PCB and interface here](pcb/README.md).

You can edit or order the fabrication of this PCB online at: https://easyeda.com/integrac/rpi-repeater

If you use this interface board, you should configure the Pirrot Output pin settings (in ``/etc/pirrot.conf``) with the following settings:

| Description | Setting Name | Value to be set |
|:---:|:--------------|:------------|
| RaspberryPi PTT pin | ``ptt_pin_invert`` | false |
| RaspberryPi COR pin | ``cos_pin_invert`` | true |


# Pirrot being used in the wild

Below is a compiled list of known projects and/or articles that have been created by users of Pirrot.

I hope you can visit these great project sites and get some inspiration to build your own using the Pirrot software.

* [Margirine Man's Baofeng BF-888S Compact Repeater Project](https://www.mdshooters.com/showthread.php?t=244553) (based on a Raspberry Pi Zero W) 


__If you have a write-,up, blog post or photo gallery, or know of a project using Pirrot, please let me know by emailing me at ballen@bobbyallen.me and I'll get the article linked here to help and/or inspire others.__
