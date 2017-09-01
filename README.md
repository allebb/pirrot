# Pirrot - A Simplex Repeater controller for RaspberryPi

The Pirrot project exists to offer a cheaper alternative to buying a dedicated simplex recording device.

Pirrot also aims to provide additional functionality through the use of DTMF tones such as broadcast weather and time upon request.

## Requirements

The simplest hardware requirements are:

1. A Raspberry Pi 3
2. A Radio Transceiver (Baofeng used as per example)
3. A USB audio interface (EasyDigit as per example)

The radio should be setup to use VOX

## Sound adjustment

Using the ``alsamixer`` command you can adjust your microphone and speaker volumes as required.

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
