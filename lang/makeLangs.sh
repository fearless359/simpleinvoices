#!/bin/bash

# cd /var/www/simpleinvoices/lang
mkdir -p ../newLangs
find ./ -maxdepth 1 -type d  -not -path "./" -not -path "./en_US" -printf '%P\n' | xargs -I file echo 'php -q langInsert.php file > ../newLangs/langFile.php' | bash
tar -czf newLangs.tar.gz ../newLangs
rm -rf ../newLangs
