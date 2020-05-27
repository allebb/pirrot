# Configuration

Pirrot stores user-defined configuration options in ``/etc/pirrot.conf``.

You can use a text editor such as ``nano`` or ``vi`` to edit the file.

Once you have made changes to the configuration file you must however restart the Pirrot daemon for those changes to take affect, you can restart the daemon using this command:

```shell
sudo service pirrot restart
```

If you have network access to your Raspberry Pi, you could instead enable the optional admin web interface and update the settings using your browser instead.

# /etc/pirrot.conf

The Pirrot configuration file is a key-value INI based configuration file, the below documentation explains the available values and the setting descriptions: 

| Key | Default value | Description |
|:---:|:--------------|:------------|
| timezone | UTC | The timezone that will be used when the automatic ident reads the time. |
| callsign | RPT1 | The repeater callsign - spoken when automatic identification is enabled. |
| enabled | true | Whether the repeater is enabled, if set to ``false`` the received transmission(s) will __not__ be transmitted. |
| transmit_mode | simplex-cor | The repeater transmit mode, options are ``simplex-vox``,``simplex-cor`` and ``duplex-cor``. |
| courtesy_tone | BeeBoo | The file name (without extension) from the ``resources/sounds`` directory, this will play at the end of transmissions. Set to ``false`` if you would like to disable the courtesy tone. |
| auto_ident | false | Enables or disables automatic transmission of the repeater identification. |
| ident_use_custom | false | Override the computer spoken "auto_ident" repeater ident message and will instead play a custom MP3 file which should be uploaded to ``/opt/pirrot/storage/input/custom.mp3`` |
| ident_interval|600| The number of seconds between each automatic identification __in seconds__.|
| ident_time | true | Will "speak" the time as part of the automatic identification. |
| ident_morse | false | Will transmit the repeater callsign in morse code at the end of the automatic identification (Not implemented as yet). |
| pl_tone|110.9|Optional PL/CTCSS tone, this will be read with the automatic identification.|
| delayed_playback_interval|0| An optional number of __seconds__ that the repeater will delay before playing back the received transmission. |
| record_device | alsa | Sets the system sound driver/system that will be used, options are ``alsa``, ``coreaudion`` or ``waveaudio`` - This should be left as default for the RaspberryPi!|
| store_recordings | false | If set to ``true`` incoming transmissions will be saved into ``/opt/pirrot/storage/recordings`` directory, the file will use the current timestamp. |
| purge_recording_after | 7 | Recordings will be automatically deleted this number of days. |
| web_interface_enabled | false | Enables the light-weight admin web interface, accessible at http://{IP_ADDRESS}:8440 |
| web_interface_port | 8440 | The TCP port that the web server will listen on |
| web_interface_bind_ip | 0.0.0.0 | Allows binding to a specific IP address (eg. 127.0.0.1 for local access only), default is to allow connections from all. |
| web_interface_logging | false | Enables access logs to be saved to /var/log/pirrot-web.log |
| web_gps_enabled | false | Enable GPS position and atomic clock (time) output on the dashboard. |
| tripwire_enabled | false | Enable the tripwire feature (sends a web hook when transmission is received) |
| tripwire_url | null | The URL to send the HTTP request payload to when the "tripwire" is activated (a transmission is received) |
| tripwire_ignore_interval | 300 | This value will ensure that further transmissions within this time period (in seconds) do not trigger another HTTP POST request. | 
| tripwire_request_timeout | 30 | HTTP request timeout (in seconds) |
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
| archive_enabled | false | Enable audio recording archiving to a remote FTP/FTPS server? |
| ftp_host | ftp.example.com | The FTP/FTPS server hostname or IP address. |
| ftp_ssl | false | Use SSL when connecting to the server (FTPS) |
| ftp_port | 21 | The FTP/FTPS server port. |
| ftp_passive | false | Use passive (PASV) mode. |
| ftp_user | jbloggs | The FTP account username. |
| ftp_pass | password | The FTP account password. |
| ftp_path | / | Remote server path to upload the files to. |
| ftp_delete_on_success | true | Delete the local recordings if upload to FTP was successful? |
| ftp_timeout | 30 | Timeout (in seconds) when trying to connect to the FTP server |
| tts_api_key| null | Your API key for the Google Translate/Text to Speech Service. |
| tts_language | en | The language that should be used for text synthesis. | 
| tts_custom_ident | null | An optional custom TTS message to be broadcast at the ident interval. |
| owm_api_key | null | OpenWeatherMap.org API key |
| owm_enabled | false | Broadcast current weather after the station ident? |
| owm_locale | London,UK | The location of your station or where you want the weather report taken from. |
| owm_template | ... | The "spoken" format for the weather broadcast. | 

