#!/bin/sh

echo '[git] Downloading the project'
git clone https://github.com/gnugat/fossil.git
cd fossil

echo '[curl] Getting Composer, the PHP dependency manager'
curl -sS https://getcomposer.org/installer | php

echo '[composer] Downloading the dependencies'
php composer.phar install --no-dev --optimize-autoloader
