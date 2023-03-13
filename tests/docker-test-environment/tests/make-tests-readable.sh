#!/bin/bash

chown 1000:www-data -R /var/www/tests/codeception/_output
#find codeception/_output -depth -name '*\\*' -execdir bash -c 'mv {} "$(tr {} '\\' '.')"' \;
#find codeception/_output -depth -name '*\\*' -execdir bash -c "oldfilename={}; newfilename=$(tr $oldfilename '\\' '.'); mv $oldfilename $newfilename" \;
