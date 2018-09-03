# Docker build script for continuous deployment demo container
#
# Alpine 3.7 + 3.8 seems to suffer from spurious chars in PHP web output, trying
# Ubuntu instead now.

FROM ubuntu:18.10

# Install software
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php apache2

# Prep Apache
RUN echo "ServerName localhost" > /etc/apache2/conf-enabled/server-name.conf

# Other dependencies
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y wget netcat

# Add dumb init to improve sig handling (stop time in CircleCI of 10sec is too slow)
RUN wget -O /usr/local/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.2/dumb-init_1.2.2_amd64
RUN chmod +x /usr/local/bin/dumb-init

# Copy shell scripts
COPY bin /app/bin
RUN chmod -R +x /app/bin

# Copy contents of a web dir
RUN rm /var/www/html/*
COPY web/* /var/www/html/

EXPOSE 80

# The healthcheck is used by the Routing Mesh, during a rolling update, to understand
# when to avoid a container that is not ready to receive HTTP traffic.
HEALTHCHECK --interval=5s --timeout=5s --start-period=2s --retries=5 \
    CMD wget -qO- http://localhost/health.php > /dev/null || exit 1

# Report PHP version in build
RUN php -v

# Start the web server
ENTRYPOINT ["/usr/local/bin/dumb-init", "--"]
CMD ["sh", "/app/bin/container-start.sh"]
