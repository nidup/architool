# Architool

Experiments with Akeneo PIM code base, hexagonal architecture & Domain-Driven Design :rocket:

This tool helps to re-arrange our legacy Akeneo PIM code base by applying a set of commands.

The purpose is to provide a discussion support by demonstrating several approaches.

Idea is to make these approaches self-explained thanks to set of descriptive commands.

## Requirements

- Composer
- PHP 7.1
- Git
- Akeneo PIM dev version 1.8

## How To Install

Run `composer.phar update --prefer-dist` to install the tool's dependencies.

## How To Use

Run `bin/console nidup:architool:hexagonalize /home/nico/git/pim-ce-18`
