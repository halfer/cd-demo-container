Continuous integration/deployment demo
===

Introduction
---

This is a simple demonstration repository to show how to use continuous integration and
continuous deployment to deploy a web service with zero downtime. To do that, I am using
some features in Docker Swarm to run multiple containers, and when a container is being
upgraded, traffic will be routed to the other one.

I am using these things:

* PHP for scripting
* The built-in PHP web-server
* Docker to run the container
* Docker Swarm to run multiple containers in a service
* CircleCI for integration
* GitLab as a Docker image registry
* Alpine Linux as a lightweight container OS
* Vultr as a cloud Linux host
* Git tags as an auto-deployment trigger

If you're learning any of these things, but want to change something, feel free to clone
and swap!

Target audience
---

This material is not suitable for beginners. A basic understanding of the Linux
command line and general hosting concepts are essential.

Preparation
---

To follow this tutorial, sign up for the following:

* GitHub
* Vultr
* GitLab
* CircleCI

The Linux host will generally cost some money, although on most cloud systems you
are charged very accurately, and as long as you destroy the machine when you are
finished, you'll incur very low costs (less than one US dollar).

General steps
---

I will expand on these in the future, but these will be a helpful checklist for
experienced users:

* Clone this repo
* Create a small Ubuntu server in the Vultr host
* Create an empty project in GitLab
* Connect CircleCI to GitHub and start building the project
* Customise the env vars in `config.yml` and push
* Install Docker in the remote server
* Create a Docker login in the remote server
* Start a Docker Swarm service in the remote
* Add a Git tag and push that tag to kick off an automatic deployment

Optional:

* Obtain a domain/sub-domain and point it to the server IP

Running instance
---

I have a [running instance](https://cd-demo.jondh.me.uk/) of this service. This one
is a bit different because, obviously, it runs via a secure site. This is achieved
by my adding a Traefik front-end proxy in front of the demo container.

License
---

This material is licensed under the [MIT License], which means you can pretty much do
anything you like with it.
