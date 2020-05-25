install:
	@/opt/pirrot/build/scripts/install.sh

uninstall:
	@cp /opt/pirrot/build/scripts/uninstall.sh /tmp/pirrot_uninstaller
	@/tmp/pirrot_uninstaller

clean:
	rm -f /opt/pirrot/storage/input/*.ogg
	rm -f /opt/pirrot/storage/recordings/*.ogg

.PHONY: install uninstall clean