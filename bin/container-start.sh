#!/bin/sh
#
# This script is the default CMD in the Dockerfile

# Generate a GUID for this server:
php -r "echo hash('sha256', uniqid());" > /var/www/html/guid.txt
chown www-data /var/www/html/guid.txt

# Start web server
apachectl -D FOREGROUND
