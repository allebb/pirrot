install:
	./opt/pirrot/build/scripts/install.sh

uninstall:
	./opt/pirrot/build/scripts/uninstall.sh

clean:
	rm -f /opt/pirrot/storage/input/*.ogg
	rm -f /opt/pirrot/storage/recordings/*.ogg

.PHONY: install uninstall clean
