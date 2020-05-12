# Pirrot Web Interface

This web interface uses [Lumen](https://lumen.laravel.com/) for the light-weight, super fast web framework.

## Enabling the web interface

To enable this web interface on your Pirrot Repeater, edit the ``/etc/pirrot.conf`` and set the value of ``web_interface_enabled`` to ``true``, then restart the Pirrot daemon by running:

```shell
sudo service pirrot restart
```

You should then be able to access the web interface by pointing your browser to: http://{rpi-ip-or-hostname}:8440.

The default password to login is: ``pirrot``, you will be asked to reset it on first login.
