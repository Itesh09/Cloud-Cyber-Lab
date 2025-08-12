#!/bin/bash
sudo apt update
sudo apt install apache2 php php-mysqli mariadb-server git unzip -y
sudo systemctl start apache2
sudo systemctl start mariadb

git clone https://github.com/digininja/DVWA.git /var/www/html/dvwa
cd /var/www/html/dvwa
cp config/config.inc.php.dist config/config.inc.php
