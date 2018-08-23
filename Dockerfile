# Docker build script for continuous deployment demo container

FROM alpine:3.8

# Install software
RUN apk update
RUN apk add php

# Add dumb init to improve sig handling (stop time in CircleCI of 10sec is too slow)
RUN wget -O /usr/local/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.2/dumb-init_1.2.2_amd64
RUN chmod +x /usr/local/bin/dumb-init

# Copy shell scripts
COPY bin /app/bin
RUN chmod -R +x /app/bin

# Copy contents of a web dir
COPY web/* /root/

EXPOSE 80

HEALTHCHECK --interval=3s --timeout=5s --start-period=2s --retries=5 \
    CMD wget -qO- http://localhost/health.php > /dev/null || exit 1

# Start the web server
ENTRYPOINT ["/usr/local/bin/dumb-init", "--"]
CMD ["php", "-S", "0.0.0.0:80", "-t", "/root"]
