help:
	#####################################################
	# Type "sudo make install" to install the program   #
	# Type "sudo make uninstall" to remove the program  #
	#####################################################
	# The below are for developers                      #
	# -Type "sudo make test-install" to install without #
	#   setting up cron                                 #
	# -Type "sudo make push to create a zip and push it #
	#   to local apache server                          #
	#####################################################
install:
	# create directories
	sudo mkdir -p /usr/share/signage
	sudo mkdir -p /usr/share/signage/default
	sudo mkdir -p /var/www/html/glue
	# copy over the program
	sudo cp -fv glue.py /usr/bin/glue
	sudo link /usr/bin/glue /etc/cron.hourly/glue || echo 'file exists!'
	# copy over the default css
	sudo cp -fv style.css /usr/share/signage/style.css
	# copy over the config file to /etc
	sudo cp -fv glue.cfg /etc/glue.cfg
	# link the file to be in /usr/bin/ and make it executable
	sudo chmod +x /usr/bin/glue
	sudo chmod +x /etc/cron.hourly/glue
test-install:
	# dont make the cron job work
	# create directories -p is like force
	sudo mkdir -p /usr/share/signage
	sudo mkdir -p /usr/share/signage/default
	sudo mkdir -p /var/www/html/glue
	# copy over the program
	sudo cp -fv glue.py /usr/bin/glue
	# copy over the default css
	sudo cp -fv style.css /usr/share/signage/style.css
	# copy over the config file to /etc
	sudo cp -fv glue.cfg /etc/glue.cfg
	# link the file to be in /usr/bin/ and make it executable
	sudo chmod +x /usr/bin/glue
uninstall:
	# nuke out the files pushed in install
	# echo everything in case user has changed locations
	sudo rm /var/www/html/index.html || echo 'lol'
	sudo rm /etc/cron.hourly/glue || echo 'lol'
	sudo rm /usr/bin/glue || echo 'lol'
	sudo rm /etc/glue.cfg || echo 'lol'
	sudo rm /usr/share/signage/style.css || echo 'lol'
push:
	# nuke the zipfile if it already exists
	rm glue.zip || echo 'already gone yo!'
	# zip up the program into glue.zip
	zip -rv glue.zip glue.cfg glue.py makefile style.css 
	# copy the zipfile into the web directory
	sudo cp -v glue.zip /var/www/html
