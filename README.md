# Architool

Experiments with the Akeneo PIM & hexagonal architecture :rocket:

## Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting started

Have a Akeneo PIM 1.8 installed.

## Install the tool

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `docker-compose up -d`.
- Run `bin/docker/composer.sh update --prefer-dist` to install the project's dependencies.

## Use it

- Run `bin/docker/console.sh nidup:architool:hexagonalize /home/nico/fit/pim-ce-18

## Thanks

@matthiasnoback for the great devtools docker images ;)
