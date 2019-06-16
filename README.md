# Pirrot - A Simplex Radio Repeater controller for RaspberryPi

The Pirrot project exists to offer a cheaper alternative to buying a dedicated simplex repeater controller.

Pirrot also offers other operation modes that most simplex repeaters do not come with as standard such as automatic identification and the ability to operate in "surveillance mode".

## Features

* Ability to set a courtesy tone on end of transmission.
* Ability to configure the repeater to identify itself automatically on a schedule.
* Ability to enable or disable automatic repeater identifier. - Disabled by default.
* Ability to change output transmission from VOX (voice activation) to COR (COR signal from radio to an input relay).
* Save recordings of received transmissions - Disabled by default.

See the full list of configuration items (features) on the [CONFIGURATION page](CONFIGURATION.md).

## Hardware requirements

The simplest hardware requirements are:

1. A RaspberryPi 2+ (running _Raspbian Stretch_ or _Raspbian Stretch Lite_)
2. A USB audio interface (or USB for testing purposes)

Assuming you wish to use this with a radio transceiver, you will also need to wire up the transceivers PTT button to the PTT relay pin on your RaspberryPi's GPIO pin (by default this is GPIO Pin #23, although you can adjust as required in the "/etc/pirrot.conf" file).

The audio in (receive) and audio out (transmit) connectors from your radio will need to be connected to the external USB sound card using the Mic Jack (for Transmit) and Speaker Jack (for Receive).

If you plan to use the repeater in COR mode you will need to connect your COR GPIO pin to the transceiver. 

## Installation

Pirrot can be installed either by downloading the latest tarball ([find the version number here](https://github.com/allebb/pirrot/releases)) from the command line as follows:

__Replace the X.X.X.X with the latest version available from the releases page__

```shell
cd ~
wget https://github.com/allebb/pirrot/archive/vX.X.X.tar.gz
sudo mkdir /opt/pirrot
sudo tar xf vX.X.X.tar.gz -C /opt/pirrot --strip 1
cd /opt/pirrot
sudo make install
```

...alternatively you can use Git to download and install using this method instead:

```shell
sudo apt-get install -y git
sudo git clone https://github.com/allebb/pirrot /opt/pirrot
cd /opt/pirrot
sudo make install
```

Once installed, Pirrot will start automatically at boot up (ensure your USB audio adapter is connected though!)

## Configuration options

The Pirrot configuration file is found in ``/etc/pirrot.conf``, a full list of settings and descriptions can be found on the [configuration](CONFIGURATION.md) page.

When making changes to this file please ensure that you restart the Pirrot daemon by running ``sudo /etc/init.d/pirrot restart`` to ensure that the changes to take affect.

## Sound adjustment

Using the ``alsamixer`` command in the terminal you can adjust your microphone and speaker volumes if required.

## Default GPIO pins

By default, the Pirrot configuration file, located in ``/etc/pirrot.conf`` has default GPIO pins configured, these are as follows:

* __GPIO 18__ - COR Signal (Input/Relay) - _Triggered when the radio squelch is opened._
* __GPIO 23__ - PTT Switch (Output/Relay)  - _Triggers the PTT on the radio to transmit._
* __GPIO 17__ - Power/Ready LED (Output/LED) - _Shows that the repeater program is running._
* __GPIO 27__ - Receive LED (Output/LED) - _Receiving transmission (fired when COR signal is high/non-functional in VOX mode)._
* __GPIO 22__ - Transmit LED (Output/LED) - _Transmitting transmission (fired when the repeater is ident-ing/playing back audio)._

__Remember: When making changes to the ``/etc/pirrot.conf`` file you must restart the daemon using the ``sudo /etc/init.d/pirrot restart`` command.__

# Uninstalling

If you wish to uninstall Pirrot at a later date, you can run:

```shell
cd /opt/pirrot
sudo make uninstall
```
