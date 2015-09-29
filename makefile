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
	# -Type "sudo make adduser" add a new user that can #
	#   login to the admin section of the site.         #
	# -Type "sudo make editusers" to delete users       #
	#####################################################
adduser:
	# This will create a new user that can login to the admin directory
	# htpasswd uses -B to force stronger encryption
	sudo bash adminScripts/makeNewUser.sh
	sudo make install
editusers:
	# below is the current users
	cat web/.htpasswd
	# delete the line with the username you want removed
	# press enter to open the users file
	read none
	sudo nano web/.htpasswd
	sudo make install
view-output:
	midori http://localhost/glue
test:
	make install
	sudo glue
full-install:
	# setup and install apache
	sudo apt-get install apache2 --assume-yes
	sudo apt-get install apache2-utils --assume-yes
	if ! grep "/var/www/html/glue/admin" /etc/apache2/apache2.conf;then bash -c "echo '<Directory /var/www/html/glue/admin>' >> /etc/apache2/apache2.conf;echo 'Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf;echo 'AllowOverride All' >> /etc/apache2/apache2.conf;echo 'Require all granted' >> /etc/apache2/apache2.conf;echo '</Directory>' >> /etc/apache2/apache2.conf";fi
	# add php
	sudo apt-get install php5 --assume-yes
	# install and setup firewall
	sudo apt-get install ufw --assume-yes
	sudo ufw enable
	sudo ufw allow proto tcp from any to any port 80
	make install
	sudo glue
install:
	# create crontab entry, remove it if it already exists
	echo "# run task every 15 minutes between hours 8 and 15" > /etc/cron.d/csvtohtml
	# run cron job between hours 8 and 15
	echo "*/15 8-15 * * * root glue -c" >> /etc/cron.d/csvtohtml
	# clean up empty lines in crontab
	sudo bash -c "cat /etc/crontab | tr -s '\n' > /etc/crontab"
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
	sudo cp -fv style.css /usr/share/signage/default/style.css
	sudo cp -fv head.html /usr/share/signage/default/head.html
	sudo cp -fv foot.html /usr/share/signage/default/foot.html
	# copy over the config file to /etc
	sudo cp -fv glue.cfg /etc/glue.cfg
	# add the menu if it dont exist
	sudo touch /usr/share/signage/menu.csv
	# allow php system to edit glue.cfg
	sudo chmod 0777 /etc/glue.cfg
	sudo chmod 0777 /usr/share/signage/menu.csv
	# link the file to be in /usr/bin/ and make it executable
	sudo chmod +x /usr/bin/glue
	sudo chmod +x /etc/cron.hourly/glue
	# copy over the webupdate script
	sudo cp -fvr web/* /var/www/html/glue/admin/
	# copy over the htaccess file and the htpasswd file
	sudo cp -fvr web/.ht* /var/www/html/glue/admin/
	# restart apache
	sudo service apache2 restart
	# run glue to create a page
	sudo glue
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
	# copy over the default users for admin area
	sudo cp -fv users.cfg /usr/share/signage/users.cfg
	# copy over the config file to /etc
	sudo cp -fv glue.cfg /etc/glue.cfg
	# link the file to be in /usr/bin/ and make it executable
	sudo chmod +x /usr/bin/glue
uninstall:
	# nuke out the files pushed in install
	# echo everything in case user has changed locations
	sudo rm -rvf /var/www/html/glue/ || echo 'lol'
	sudo rm /etc/cron.hourly/glue || echo 'lol'
	sudo rm /usr/bin/glue || echo 'lol'
	sudo rm /etc/glue.cfg || echo 'lol'
	sudo rm -rvf /usr/share/signage/ || echo 'lol'
	# clean up cron entries
	sudo sed -i "s/\*\/3\ \*\ \*\ \*\ \*\ root\ glue\ \-c//g" /etc/crontab
	sudo bash -c "cat /etc/crontab | tr -s '\n' > /etc/crontab"
push:
	# nuke the zipfile if it already exists
	rm glue.zip || echo 'already gone yo!'
	# zip up the program into glue.zip
	zip -rv glue.zip glue.cfg glue.py makefile style.css backgrounds
	# create directory for glue if it does not yet exist
	sudo mkdir -p /var/www/html/glue
	# copy the zipfile into the web directory
	sudo cp -v glue.zip /var/www/html/glue
project-report:
	sudo apt-get install gitstats gource --assume-yes
	rm -vr report/ || echo "No existing report..."
	mkdir -p report
	mkdir -p report/webstats
	# write the index page
	echo "<html style='margin:auto;width:800px;text-align:center;'><body>" > report/index.html
	echo "<a href='webstats/index.html'><h1>WebStats</h1></a>" >> report/index.html
	echo "<a href='log.html'><h1>Log</h1></a>" >> report/index.html
	echo "<video src='video.mp4' width='800' controls>" >> report/index.html
	echo "<a href='video.mp4'><h1>Gource Video Rendering</h1></a>" >> report/index.html
	echo "</video>" >> report/index.html
	echo "</body></html>" >> report/index.html
	# write the log to a webpage
	echo "<html><body>" > report/log.html
	echo "<h1><a href='index.html'>Back</a></h1>" >> report/log.html
	# generate the log into a variable
	git log --stat > report/logInfo
	echo "<code><pre>" >> report/log.html
	cat report/logInfo >> report/log.html
	echo "</pre></code>" >> report/log.html
	rm report/logInfo
	echo "</body></html>" >> report/log.html
	# generate git statistics
	gitstats -c processes='8' . report/webstats
	# generate a video with gource
	gource --max-files 0 -s 1 -c 4 -1280x720 -o - | avconv -y -r 60 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 1 -threads 8 -bf 0 report/video.mp4
