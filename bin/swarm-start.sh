#!/bin/sh
#
# This script is an example of how to add this service to Docker Swarm

docker service create \
    --name cd-demo \
    --env CD_DEMO_VERSION=latest \
    --replicas 2 \
    --with-registry-auth \
    --publish 80:80 \
    registry.gitlab.com/halfercode/cd-demo-container
