#!/usr/bin/env bash

apt-get update
apt-get install nginx php5-fpm php5-cli php5-curl php5-common php5-mysql -y
if ! [ -L /var/www ]; then
	rm -rf /var/www
	ln -fs /vagrant /var/www
fi
rm -f /etc/nginx/sites-available/default
cp /vagrant/nginx-default /etc/nginx/sites-available/default
service php5-fpm restart
service nginx restart
