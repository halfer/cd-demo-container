#!/bin/sh
#
# This script is the default CMD in the Dockerfile

# Generate a GUID for this server:
php -r "echo hash('sha256', uniqid());" > /var/www/localhost/htdocs/guid.txt
chown apache /var/www/localhost/htdocs/guid.txt

# Start web server
/usr/sbin/httpd -DFOREGROUND
