# This will create a new user that can login to the admin directory
# htpasswd uses -B to force stronger encryption
echo -n "Username:";
read userName;
htpasswd -B web/.htpasswd $userName;
# below is the current new version of the file
cat web/.htpasswd;
