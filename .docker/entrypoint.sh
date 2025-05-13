#!/bin/sh

vendor/bin/phinx migrate

nginx
php-fpm -F

