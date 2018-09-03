#!/bin/sh

timeout 5 sh -c 'while ! echo exit | nc localhost 80; do sleep 0.5; done'
