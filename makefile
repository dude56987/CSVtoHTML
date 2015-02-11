help:
	#####################################################
	# Type "make install" to install the program        #
	# Type "make uninstall" to remove the program       #
	#####################################################
	# The below commands are for developers             #
	#####################################################
	# -Type "sudo make test" to install and run the     #
	#   program                                         #
	# -Type "make view-output" to view the generated    #
	#   webpage                                         #
	# -Type "sudo make test-install" to install without #
	#   setting up cron                                 #
	# -Type "sudo make push" to create a zip and push   #
	#   it to local apache server                       #
	#####################################################
view-output:
	midori http://localhost/glue
test:
	make install
	sudo glue
full-install:
	sudo apt-get install apache2 --assume-yes
	sudo apt-get install ufw --assume-yes
	sudo apt-get install php5 --assume-yes
	sudo ufw enable
	sudo ufw allow proto tcp from any to any port 80
	make install
	sudo glue
install:
	# create crontab entry, remove it if it already exists
	sudo sed -i "s/\*\/3\ \*\ \*\ \*\ \*\ root\ glue\ \-c//g" /etc/crontab
	sudo bash -c 'echo "*/3 * * * * root glue -c" >> /etc/crontab'
	#sed -i "/^$/d" /etc/crontab;
	# create directories
	sudo mkdir -p /usr/share/signage
	sudo mkdir -p /usr/share/signage/default
	sudo mkdir -p /var/www/html/glue
	sudo mkdir -p /var/www/html/glue/admin
	sudo mkdir -p /var/www/html/glue/backgrounds
	sudo mkdir -p /var/www/html/glue/backgrounds/extra
	sudo mkdir -p /var/www/html/glue/backgrounds/1
	sudo mkdir -p /var/www/html/glue/backgrounds/2
	sudo mkdir -p /var/www/html/glue/backgrounds/3
	sudo mkdir -p /var/www/html/glue/backgrounds/4
	sudo mkdir -p /var/www/html/glue/backgrounds/5
	sudo mkdir -p /var/www/html/glue/backgrounds/6
	sudo mkdir -p /var/www/html/glue/backgrounds/7
	sudo mkdir -p /var/www/html/glue/backgrounds/8
	sudo mkdir -p /var/www/html/glue/backgrounds/9
	sudo mkdir -p /var/www/html/glue/backgrounds/10
	sudo mkdir -p /var/www/html/glue/backgrounds/11
	sudo mkdir -p /var/www/html/glue/backgrounds/12
	# copy over backgrounds
	sudo cp -rvf backgrounds /var/www/html/glue/
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
	# copy over the webupdate script
	sudo cp -fvr web/* /var/www/html/glue/admin/
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
	zip -rv glue.zip glue.cfg glue.py makefile style.css backgrounds
	# create directory for glue if it does not yet exist
	sudo mkdir -p /var/www/html/glue
	# copy the zipfile into the web directory
	sudo cp -v glue.zip /var/www/html/glue
