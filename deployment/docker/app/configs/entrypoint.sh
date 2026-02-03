#!/usr/bin/env sh

ECONUMO_CONFIG_API_URL="${ECONUMO_CONFIG_API_URL:=}"
LILTAG_CONFIG_URL="${LILTAG_CONFIG_URL:=}"
LILTAG_CACHE_TTL="${LILTAG_CACHE_TTL:=0}"
ALLOW_REGISTRATION="${ALLOW_REGISTRATION:=true}"
PAYWALL_ENABLED="${PAYWALL_ENABLED:=false}"
echo "window.econumoConfig = {
  API_URL: \"${ECONUMO_CONFIG_API_URL}\",
  LILTAG_CONFIG_URL: \"${LILTAG_CONFIG_URL}\",
  LILTAG_CACHE_TTL: ${LILTAG_CACHE_TTL},
  ALLOW_REGISTRATION: ${ALLOW_REGISTRATION},
  PAYWALL_ENABLED: ${PAYWALL_ENABLED}
}" > /usr/share/nginx/html/econumo-config.js

chown -R www-data:www-data /var/www/ /usr/share/nginx/html/

# Ensure database directory exists and has correct permissions
mkdir -p /var/www/var/db
chown -R www-data:www-data /var/www/var/db
chmod -R 775 /var/www/var/db

if [ ! -f "/var/www/var/db/econumo.sqlite" ] && [ ! -f "/var/www/var/db/db.sqlite" ]; then
  su -s /bin/sh www-data -c "cd /var/www && php bin/console doctrine:database:create -q"
fi

su -s /bin/sh www-data -c "cd /var/www && php bin/console doctrine:migrations:migrate --quiet --no-interaction --allow-no-migration"
su -s /bin/sh www-data -c "cd /var/www && php bin/console cache:clear"
su -s /bin/sh www-data -c "cd /var/www && php bin/console lexik:jwt:generate-keypair --skip-if-exists"

# Configure and start cron job for recurring transactions
echo "0 0 * * * cd /var/www && php bin/console econumo:process-recurring >> /var/www/var/log/recurring.log 2>&1" >> /etc/crontabs/root
/usr/sbin/crond

/usr/bin/supervisord -n -c /etc/supervisord.conf