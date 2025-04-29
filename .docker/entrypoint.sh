#!/bin/sh

echo "⏳ Waiting for availability MySQL..."
until nc -z -v -w30 mysql 3306
do
  echo "❌ MySQL not available - waiting..."
  sleep 3
done

echo "✅ Performing migrations..."
vendor/bin/phinx migrate

nginx
php-fpm -F

