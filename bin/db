#!/bin/sh

env="prod"
if [ $# -gt 0 ]
then
    env=${1}
fi

echo 'Dropping database'
php ./app/console doctrine:database:drop --force --env=${env}

echo 'Creating database'
php ./app/console doctrine:database:create --env=${env}

echo 'Creating schema'
php ./app/console doctrine:schema:create --env=${env}

echo 'Loading fixtures'
php ./app/console widop:fixtures:load --env=${env}
