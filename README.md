Continuous integration/deployment demo
===

[![CircleCI](https://circleci.com/gh/halfer/cd-demo-container.svg?style=svg)](https://circleci.com/gh/halfer/cd-demo-container)

Introduction
---

This is a simple demonstration repository to show how to use continuous integration and
continuous deployment to deploy a web service with zero downtime. To do that, I am using
some features in Docker Swarm to run multiple containers, such that during the short outage
during a container upgrade, traffic is automatically routed to a healthy running instance.

I am using these things:

* PHP for scripting
* The built-in PHP web-server
* GitHub for version control (obviously `;-)`)
* Docker to containerise the app
* Alpine Linux as a lightweight container OS
* Docker Swarm to run multiple containers in a service
* HTTP health-checks to help Docker maintain availability
* CircleCI for integration
* GitLab as a Docker image registry
* Vultr as a cloud Linux host
* Git tags as an auto-deployment trigger

If you're wanting to get this project working, but want to change individual things, go
right ahead. For example, you could use a different Git host or Docker registry
without much bother. The only thing that would need much more work to swap is CircleCI,
since the configuration file is specific to their system.

How it works
---

The heart of this project is a CircleCI configuration containing two jobs: one (`build`)
for building and testing a Docker image, and the other (`deploy`) for deploying it
automatically. When pushing code, the first job will run, and it will build the
Dockerfile. If this works, it will try to start it and run a test on it.

If the developer pushes a deploy tag (i.e. with a prefix of `deploy-`) then the first
job will additionally tag the Docker image with the Git tag and will push it to
the Docker image registry. If this is all successful, the second job will SSH to the
remote machine, pull the image from the registry, and start a rolling Swarm update.

In this example, the Swarm service is _always_ run using two replicas, so there
is always one operational container to service incoming web requests. Docker Swarm
uses a networking system called [Routing Mesh](https://docs.docker.com/engine/swarm/ingress/#using-the-routing-mesh)
together with the custom health-checks to determine how to update internal networking
rules during a rolling update.

Target audience
---

This material is not suitable for beginners. A basic understanding of the Linux
command line, containers and general hosting concepts are pretty much essential.

Preparation
---

To follow this tutorial, sign up for the following:

* GitHub
* Pretty much any VPS host
* GitLab
* CircleCI

The VPS host will generally cost some money. If you can go for a cloud provider they
will charge very accurately, and as long as you destroy the machine when you are
finished, you'll incur very low costs (less than one US dollar). I use Vultr, but
there's also Digital Ocean, Linode and many others available.

General steps
---

I will expand on these in the future, but these will be a helpful checklist for
experienced users:

* Fork this repo

    The project name can be anything, but it may be easier to stick with
    `cd-demo-container`.

    To use CircleCI, you _have_ to use either GitHub or BitBucket, at the time of
    writing. Although you can checkout from any Git repo, I don't think CircleCI
    will monitor code pushes on anything other than these two.

* Create a small server in the VPS host

    I use a machine with 1G of RAM but you might get away with less than that. I
    use Ubuntu as it is popular and widely supported, but if you prefer something
    else (Fedora, Centos, Debian, etc) then go for it.

* Create an empty project in GitLab (using the same name as the GitHub project)

    Your project URL will be `https://gitlab.com/username/cd-demo-container`, with
    the appropriate swap for the username.

* Connect CircleCI to GitHub and start building the project
* Customise the env vars in `config.yml` and push

    These vars will need to be changed:

        PROJECT_DOCKER_REGISTRY_USER: halfercode
        PROJECT_DOCKER_IMAGE_URL: registry.gitlab.com/halfercode
        PROJECT_DEPLOY_HOST: agnes.jondh.me.uk

    You can probably leave these be (change them if you know what you are doing):

        PROJECT_DOCKER_REGISTRY: registry.gitlab.com
        PROJECT_DOCKER_SERVICE_NAME: cd-demo
        PROJECT_DEPLOY_USER: root

* Install Docker in the remote server
* Login to the Docker registry in the remote server (`docker login ...`)
* Initialise a Docker swarm (`docker swarm init`)
* Start a Docker service in the remote ([example command](bin/swarm-start.sh))
* Add a Git tag and push that tag to kick off an automatic deployment (e.g.
`git tag -a deploy-v1 && git push --follow-tags`)

Optional:

* Obtain a domain/sub-domain and point it to the server IP
* Add other Docker machines into the Swarm (`docker swarm join`)

Running instance
---

I have a [running instance](https://cd-demo.jondh.me.uk/) of this service. This one
is a bit different because, obviously, it runs via a secure site. This is achieved
by adding a [Traefik](https://traefik.io/) front-end proxy in front of the demo
container.

Other resources
---

To achieve zero-downtime continuous deployment, I used the technique shown in
[this video](https://www.youtube.com/watch?v=dLBGoaMz7dQ); I recommend watching it,
as it is very well presented.

License
---

This material is licensed under the [MIT License](https://opensource.org/licenses/MIT),
which means you can pretty much do anything you like with it.
