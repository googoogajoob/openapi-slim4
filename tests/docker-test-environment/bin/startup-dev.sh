#!/bin/bash

echo "startup-dev.sh executing"
cd /var/www
export XDEBUG_MODE=off
composer install
unset XDEBUG_MODE
cd /var/www/bin

#start apache
apache2-foreground