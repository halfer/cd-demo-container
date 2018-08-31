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
* Alpine Linux as a lightweight container OS

If you're learning any of these things, but want to change something, feel free to clone
and swap!

License
---

This material is licensed under the [MIT License], which means you can pretty much do
anything you like with it.
