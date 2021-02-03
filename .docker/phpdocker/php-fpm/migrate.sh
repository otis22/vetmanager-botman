#!/bin/bash

echo "chown -R www-data:www-data /application/storage"
chown -R www-data:www-data /application/storage
echo "chmod -R 755 /application/storage"
chmod -R 755 /application/storage
chown -R www-data:www-data /application/storage/logs

echo "start migrate script"

function mysql_exec {
  /usr/bin/mysql -h$DB_HOST -u$DB_USERNAME -p$DB_PASSWORD -e "$@" >&2
}

echo "start Waiting db ${DB_DATABASE}"

while true; do
  if mysql_exec "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '${DB_DATABASE}'" > /dev/null; then
    break;
  fi
  echo "Waiting for mysql ${DB_DATABASE} loaded!"
  sleep 1;
done

echo "start Waiting file autoload.php"

while true; do
  if [ -f /application/vendor/autoload.php ]; then
    break;
  fi
  echo "Waiting for file autoload.php!"
  sleep 1;
done

php artisan config:clear
php artisan migrate --force
php artisan db:seed

echo "finish"
