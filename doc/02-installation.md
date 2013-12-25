# Installation

The steps described here are the exact same ones followed by the
`bin/installer.sh` script.

## 1. Downloading the project

First of all, you should download the project using git:

    git clone https://github.com/gnugat/fossil.git
    cd fossil

This will allow you to run `git pull` to get the newest updates.

## 2. Downloading its dependencies

Fossil uses [Composer](http://getcomposer.org/) to manage its dependencies:

    curl -sS https://getcomposer.org/installer | php
    php composer.phar install --no-dev --optimize-autoloader

If you want to install the project for development purpose, you can simply run:

    php composer.phar install

## Next readings

* [usage](03-usage.md)
* [tests](04-tests.md)

## Previous readings

* [introduction](01-introduction.md)
