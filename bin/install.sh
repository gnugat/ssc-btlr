#!/usr/bin/env bash
set -e

SHORT_HELP="$0 [-h] [-d] [-u] [-v]"

_COMMAND='install'
_DEV_DEPS='--no-dev'
_VERBOSITY='--quiet'
while getopts ":hduv" opt; do
    case $opt in
        h)
            echo 'Usage:'
            echo "  $SHORT_HELP"
            echo ''
            echo 'Options:'
            echo '  -h     Display this help message'
            echo '  -d     Installs development and test dependencies'
            echo '  -u     Updates dependencies'
            echo '  -v     Increases verbosity'
            echo ''
            echo 'Help:'
            echo '  Install:'
            echo ''
            echo "    $0"
            echo ''
            echo '  We can also install development and test dependencies:'
            echo ''
            echo "    $0 -d"
            echo ''
            echo '  Finally we can update dependencies:'
            echo ''
            echo "    $0 -u"
            echo ''
            exit 0
            ;;
        d)
            _DEV_DEPS=''
            ;;
        u)
            _COMMAND='update'
            _DEV_DEPS=''
            rm -rf  ./composer.lock ./vendor
            ;;
        v)
            _VERBOSITY=''
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
echo '// Installing...'

composer $_COMMAND $_VERBOSITY --no-interaction $_DEV_DEPS --optimize-autoloader --apcu-autoloader

echo ''
echo ' [OK] Installed'
echo ''
