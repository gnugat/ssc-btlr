#!/bin/sh

echo 'Dropping database'
php ./app/console doctrine:database:drop --force

echo 'Creating database'
php ./app/console doctrine:database:create

echo 'Creating schema'
php ./app/console doctrine:schema:create

echo 'Loading fixtures'
php ./app/console widop:fixtures:load
