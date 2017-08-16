# Piplex - A Simplex Repeater controller for RaspberryPi

The Piplex project exists to offer a cheaper alternative to buying a dedicated simplex recording device.

Piplex also aims to provide additional functionality through the use of DTMF tones such as broadcast weather and time upon request.

## Requirements

The simplest hardware requirements are:

1. A Raspberry Pi 3
2. A radio (Baofeng used as per example)
3. A USB audio interface (EasyDigit as per example)

The radio should be setup to use VOX

## Audio Out

The radio should broadcast sound using the VOX functionality for simplicity.


## Features

* RaspberryPi3 WiFi setup as an access point (provides DNS, DHCP etc.)
* RaspberyPi3 NIC provides access for the Pi to be configured on the network.
* Saving recordings of voice optional (set in the system configuration) - Disabled by default.
* Browsable list of recording (and graphs) if recording are enabled.
* Enable to purge recordings after a set time (eg. 30 days) - A Cron job will run and delete recordings old than that.
* Ability to enable or disable repeater identifier. - Disabled by default.
* Ability to upload recordings to Amazon S3 on a cron job (only works if connected to the LAN)
* Ability to change output transmission from VOX to COS and specify GPIO pins.

## GPIO pins

* Output - LED (COS LED) - Recieving transmission (fired when COS is high)
* Ouptut - LED (TX LED) - Transmitting transmission (fired when the audio is playing)
* Output - LED (Online) - Shows that the repeater program is running.
* Output - Relay - PTT  - Triggers the PTT on the radio to re-transmit
* Input - Relay - COS - Triggered when COS is opened on the radio/
