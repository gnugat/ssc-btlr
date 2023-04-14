#!/usr/bin/env bash

SHORT_HELP="$0 [-h] [-f] [-u]'"
_STOP_ON_FAILURE=''

while getopts ":hfu" opt; do
    case $opt in
        h)
            echo 'Usage:'
            echo "  $SHORT_HELP"
            echo ''
            echo 'Options:'
            echo '  -f           Fix coding style'
            echo '  -u           Stop tests at first failure'
            echo ''
            exit 0
            ;;
        f)
            echo ''
            echo '// Fixing CS...'
            echo ''

            PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --allow-risky=yes
            exit 0
            ;;
        u)
            _STOP_ON_FAILURE='--stop-on-failure'
            ;;
        \?)
            echo '' >&2
            echo "    [KO] The \"-$OPTARG\" option does not exist" >&2
            echo '' >&2
            echo "    $SHORT_HELP" >&2
            echo '' >&2
            exit 1
            ;;
    esac
done
unset SHORT_HELP

echo ''
echo '// Running tests...'
echo ''

composer --quiet dump-autoload --optimize --apcu
rm -rf var/tests
mkdir -p var/tests/var/cht/logs/summary

vendor/bin/phpunit $_STOP_ON_FAILURE && \
    PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --allow-risky=yes --dry-run

unset _STOP_ON_FAILURE
