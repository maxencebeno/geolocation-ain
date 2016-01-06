#/bin/sh

app/console cache:clear
app/console assets:install
app/console assetic:dump
app/console cache:clear --env=prod --no-debug
app/console assets:install --env=prod --no-debug
app/console assetic:dump --env=prod --no-debug
chmod -R 777 app/cache
chmod -R 777 app/logs
