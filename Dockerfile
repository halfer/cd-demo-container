# Docker build script for continuous deployment demo container

FROM alpine:3.8

# Install software
RUN apk update
RUN apk add php

# Add dumb init to improve sig handling (stop time in CircleCI of 10sec is too slow)
RUN wget -O /usr/local/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.2/dumb-init_1.2.2_amd64
RUN chmod +x /usr/local/bin/dumb-init

# Copy shell scripts
COPY bin /root/bin

# Copy a hello world webpage
COPY web/index.php /root/index.php

EXPOSE 80

# Start the web server
ENTRYPOINT ["/usr/local/bin/dumb-init", "--"]
CMD ["php", "-S", "0.0.0.0:80", "-t", "/root"]
