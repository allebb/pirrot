# Piplex on MacOSX

Piplex can be installed on MacOSX, there are however a number of requirements, these are as follows:-

* SOX (https://sourceforge.net/projects/sox/) - Provides the recording and playback functionality.
* PHP (https://php-osx.liip.ch/) - Provides the main daemon control and GPIO handling.

## Installing sox on MacOSX

To install Sox on your MacOSX based computer, the following steps are required.

1) Download the latest MacOSX build from SourceForge (https://sourceforge.net/projects/sox/files/sox/). ___Ensuring that you download the MacOSX build___.
2) Extract the files to the desktop.
3) Run the following command:
```shell
sudo mkdir /usr/local/sox
sudo cp -R ~/Desktop/sox-14.4.2/* /usr/local/sox
```
4) You can now run sox from the CLI like so:
```shell
/usr/local/sox/sox
```
5) Great, that's all we need for now!

## Installing PHP

PHP is the main programming language used by Piplex, the instructions on this site should enable you to install and get PHP 7.X installed and working from the command line.


