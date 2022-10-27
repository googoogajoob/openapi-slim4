#!/bin/bash
#
cd /var/www
composer install
apache2 -D FOREGROUND