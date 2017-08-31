# Pirrot - A Simplex Repeater controller for RaspberryPi

The Pirrot project exists to offer a cheaper alternative to buying a dedicated simplex recording device.

Pirrot also aims to provide additional functionality through the use of DTMF tones such as broadcast weather and time upon request.

## Requirements

The simplest hardware requirements are:

1. A Raspberry Pi 3
2. A Radio Transceiver (Baofeng used as per example)
3. A USB audio interface (EasyDigit as per example)

The radio should be setup to use VOX

## Audio Out

The radio should broadcast sound using the VOX functionality for simplicity.

## Features

* RaspberryPi3 WiFi setup as an access point (provides DNS, DHCP etc.)
* RaspberryPi3 NIC provides access for the Pi to be configured on the network.
* Saving recordings of voice optional (set in the system configuration) - Disabled by default.
* Browse-able list of recording (and graphs) if recording are enabled.
* Ability to set a courtesy tone.
* Ability to configure the server identify automatically on a schedule.
* Enable to purge recordings after a set time (eg. 30 days) - A Cron job will run and delete recordings old than that.
* Ability to enable or disable repeater identifier. - Disabled by default.
* Ability to upload recordings to Amazon S3 on a cron job (only works if connected to the LAN)
* Ability to change output transmission from VOX to COS and specify GPIO pins.

## GPIO pins

* Output - LED (COS LED) - Receiving transmission (fired when COS is high)
* Output - LED (TX LED) - Transmitting transmission (fired when the audio is playing)
* Output - LED (Online) - Shows that the repeater program is running.
* Output - Relay - PTT  - Triggers the PTT on the radio to re-transmit
* Input - Relay - COS - Triggered when COS is opened on the radio/

## Setting default Audio device on RaspberryPi

The following needs to be done in order to enable the external audion sound card, without this the user will get errors about unable to detect the default adapter

This disables the onboard audio card!

```shell
sudo nano /etc/modprobe.d/raspi-blacklist.conf
```

Now add the following line and then save the file:

```
blacklist snd_bcm2835
```

Next, we need to open this file:

```shell
sudo nano /lib/modprobe.d/aliases.conf
```

And comment out this line (prefix it with a hash "#"): ``options snd-usb-audio index=-2``

Now reboot the system like so:

```shell
reboot
```

All should now work fine and the errors around unable to open the default driver should no longer be an issue.