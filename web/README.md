# Pirrot Web Interface Development Notes

This web interface uses [Lumen](https://lumen.laravel.com/) as the light-weight, superfast web framework chosen for this project.

## Enabling the web interface

Information about enabling the web interface in a production environment can be found on the [main Pirrot readme file](../README.md).

## Developing the web interface

As you might expect, development of Pirrot and the web interface is far more efficient being done on a remote computer with more memory and CPU resources than on a Raspberry Pi.

To access and develop the web interface on a dedicated Development machine (that isn't running Pirrot) the following commands should get you up and running:

```shell
# Clone the Pirrot Git Repo
cd ~/path/to/where/you/store/your/code
sudo git clone https://github.com/allebb/pirrot

# Change into the main "web" root...
cd web/

# Copy over the "dev" specific environment settings
# Important as this will override Raspbian specific OS paths etc.
cp .env.dev .env

# Install the composer dependencies for the web interface
composer install

# Create a sqlite databse
touch database/database.sqlite

# Run database migrations
php artisan migrate

# Start the development web server by running
php -S localhost:8000 -t public
```

You should now be able to access the web interface locally at: http://localhost:8000

When the web interface is running on a Raspberry Pi (and is using production ENV settings), it accesses and utilises Pirrot configuration files in the main Pirrot application directory and Linux system paths (eg. ``/opt/pirrot``, ``/etc/pirrot.conf`` and ``/var/log/pirrot.log``) as these are not available on an OSX or Windows based machine, I have emulated some paths and files to simplify deployment in a development environment.

When running in Development mode, HTTP based authentication has been disabled.

## Example recordings in "development"

The web interface will automatically create and copy some example recordings into the ``/public/recordings`` directory when the ``APP_ENV`` is set to "local" and there is not a directory or symlink detected at ``/public/recordings``.
