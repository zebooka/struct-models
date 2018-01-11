#!/bin/bash

[ $# -eq 0 ] && set "."
cd "$(dirname "$0")" &&
../vendor/bin/phpunit "$@"
exit $?
