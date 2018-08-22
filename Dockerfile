# Docker build script for continuous deployment demo container

FROM alpine:3.8

# Install software
RUN apk update
RUN apk add php

# Copy a hello world webpage
COPY web/index.php /root/index.php

# Start the web server
EXPOSE 80
ENTRYPOINT ["php -S localhost:80 -t /root"]
