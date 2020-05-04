# Configuration

Pirrot stores user-defined configuration options in ``/etc/pirrot.conf``.

You can use a text editor such as ``nano`` or ``vi`` to edit the file.

Once you have made changes to the configuration file you must however restart the Pirrot daemon for those changes to take affect, you can restart the daemon using this command:

```shell
sudo /etc/init.d/pirrot restart
```

# /etc/pirrot.conf

The Pirrot configuration file is a key-value INI based configuration file, the below documentation explains the available values and the setting descriptions: 

| Key | Default value | Description |
|:---:|:--------------|:------------|
| timezone | UTC | The timezone that will be used when the automatic ident reads the time. |
| callsign | RPT1 | The repeater callsign - spoken when automatic identification is enabled. |
| enabled | true | Whether the parrot repeater is enabled, if set to ``false`` the received transmission will __not__ be transmitted. |
| courtesy_tone | BeeBoo | The file name (without extension) from the ``resources/sounds`` directory, this will play at the end of transmissions. Set to ``false`` if you would like to disable the courtesy tone. |
| auto_ident | false | Enables or disables automatic transmission of the repeater identification. |
| ident_interval|600| The number of seconds between each automatic identification __in seconds__.|
| delayed_playback_interval|0| An optional number of __seconds__ that the repeater will delay before playing back the received transmission. |
| pl_tone|110.9|Optional PL/CTCSS tone, this will be read with the automatic identification.|
| transmit_mode | cos | The repeater transmit mode, options are ``cos``,``vox`` and ``disabled``. Using ``disabled`` mode, the repeater will quitely listen (and optionally record transmissions eg. in surveillance mode. |
| ident_time | true | Will "speak" the time as part of the automatic identification. |
| ident_morse | false | Will transmit the repeater callsign in morse code at the end of the automatic identification.
| record_device | alsa | Sets the system sound driver/system that will be used, options are ``alsa``, ``coreaudion`` or ``waveaudio`` - This should be left as default for the RaspberryPi!|
| store_recordings | false | If set to ``true`` incoming transmissions will be saved into ``/opt/pirrot/storage/recordings`` directory with the timestamp. |
| purge_recording_after | 7 | Recordings will be deleted after X number of days. |
| in_cor_pin | 18 | The GPIO pin number for the COS input. |
| out_ptt_pin | 23 | The GPIO pin number for the PTT output relay. |
| out_ready_led_pin | 17 | The GPIO pin number for the Power LED output. |
| out_rx_led_pin | 27 | The GPIO pin number for the Receive LED output. |
| out_tx_led_pin | 22 | The GPIO pin number for the Transmit LED output. |
| cos_pin_invert | false | Enables the ability to invert the GPIO high/low for the ``in_cor_pin``. |
| ptt_pin_invert | false | Enables the ability to invert the GPIO high/low for the ``out_ptt_pin``. |
| ready_pin_invert | false | Enables the ability to invert the GPIO high/low for the ``out_ready_led_pin``. |
| rx_pin_invert | false | Enables the ability to invert the GPIO high/low for the ``out_rx_led_pin``. |
| tx_pin_invert | false | Enables the ability to invert the GPIO high/low for the ``out_tx_led_pin``. |

