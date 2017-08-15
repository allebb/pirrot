# SOX Commands

This file serves as a place for me to store and keep examples of SOX commands whilst development is taking place:

Generally on Linux, you would use ALSA to record (using the default input device):
```
./sox -t alsa default ./record.wav silence 1 0.1 5% 1 1.0 5%
```

On a Mac, using the instructions for installing SOX on a Mac, I was able to record as follows:

```
./sox -t coreaudio default /Users/ballen/Desktop/test.wav silence 1 0.1 5% 1 1.0 5%
```

You could also, change the audio stop recording to be after 2.0 seconds instead like so:

```
./sox -t coreaudio default /Users/ballen/Desktop/test.wav silence 1 0.1 5% 1 2.0 5%
```

You can the automatically playback the audio after the recording has completed like so:
```
./play /Users/ballen/Desktop/test.wav
```

A better way to do this however is to use:

```

```

SOX can also merge two audio files into a single one, like so:

```
./sox −m music.mp3 voice.wav mixed.flac
```

Apparently, according to this site, you can substitute ``-t coreaudio``/``-t alsa`` with ``-d`` and it will then choose the correct input automatically.

This is a fantastic resource for SOX related stuff and avilable commands/examples: http://sox.sourceforge.net/sox.html

An example of a BASH script for creating an echo repeater, found on this page: https://unix.stackexchange.com/questions/55032/end-sox-recording-once-silence-is-detected:
```shell
#!/bin/bash
while true; do
  rec buffer.ogg silence 1 0.1 5% 1 1.0 5%
  DATE=`date +%Y%m%d%H%M%S`
  DPATH=`date +%Y/%m/%d/`
  mkdir -p ./spectro/$DPATH
  mkdir -p ./voice/$DPATH
  echo Renaming buffer file to $DATE
  sox buffer.ogg -n spectrogram -x 300 -y 200 -z 100 -t $DATE.ogg -o ./spectro/$DPATH/$DATE.png
  sox buffer.ogg normbuffer.ogg gain -n -2
  sox normbuffer.ogg -n spectrogram -x 300 -y 200 -z 100 -t $DATE.norm.ogg -o ./spectro/$DPATH/$DATE.norm.png
  mv normbuffer.ogg ./voice/$DPATH/$DATE.ogg
  play pre.ogg ./voice/$DPATH/$DATE.ogg post.ogg 
done
```

My combined example that is working on MacOSX is as follows:-

```shell
#!/bin/bash
SOX=/usr/local/sox/sox
REC=/usr/local/sox/rec
PLAY=/usr/local/sox/play
while true
do
  echo "Starting RX..."
  $SOX -t coreaudio default buffer.wav -V0 silence 1 0.1 5% 1 1.0 5%
  DATE=`date +%Y%m%d%H%M%S`
  DPATH=`date +%Y/%m/%d/`
  mkdir -p ./spectro/$DPATH
  mkdir -p ./voice/$DPATH
  echo Renaming buffer file to $DATE
  $SOX buffer.wav -n spectrogram -x 300 -y 200 -z 100 -t $DATE.wav -o ./spectro/$DPATH/$DATE.png
  $SOX buffer.wav normbuffer.wav gain -n -2
  $SOX normbuffer.wav -n spectrogram -x 300 -y 200 -z 100 -t $DATE.norm.wav -o ./spectro/$DPATH/$DATE.wav.png
  mv normbuffer.wav ./voice/$DPATH/$DATE.wav
  echo "Starting TX..."
  $PLAY pre.wav ./voice/$DPATH/$DATE.wav post.wav
done
```

You can also use:

```shell
#!/bin/bash
SOX=/usr/local/sox/sox
REC=/usr/local/sox/rec
PLAY=/usr/local/sox/play
echo "Starting RX..."
$REC -t coreaudio default buffer.wav -V0 silence 1 0.1 5% 1 1.0 5%
DATE=`date +%Y%m%d%H%M%S`
DPATH=`date +%Y/%m/%d/`
mkdir -p ./spectro/$DPATH
mkdir -p ./voice/$DPATH
echo Renaming buffer file to $DATE
$SOX buffer.wav -n spectrogram -x 300 -y 200 -z 100 -t $DATE.wav -o ./spectro/$DPATH/$DATE.png
$SOX buffer.wav normbuffer.wav gain -n -2
$SOX normbuffer.wav -n spectrogram -x 300 -y 200 -z 100 -t $DATE.norm.wav -o ./spectro/$DPATH/$DATE.wav.png
mv normbuffer.wav ./voice/$DPATH/$DATE.wav
echo "Starting TX..."
$PLAY pre.wav ./voice/$DPATH/$DATE.wav post.wav
./repeater.sh
```

Bash seems to behave rather strange, but instead I came up with this using PHP and works great:

```php
#!/usr/bin/env php
<?php
while(true){
	echo "Starting RX...";
	system('/usr/local/sox/sox -t coreaudio default buffer.wav -V0 silence 1 0.1 5% 1 1.0 5%');

	echo "Starting TX...";
	system('/usr/local/sox/play buffer.wav RC210_Number_11.wav');
}
```

My plan would be to use a main service script that forks two processes, firstly would be the main repeater code and secondly the identification loop.