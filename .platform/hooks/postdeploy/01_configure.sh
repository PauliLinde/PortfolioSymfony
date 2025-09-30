#!/bin/bash
# Ensure proper permissions
chmod -R 755 /var/app/current/public
chown -R webapp:webapp /var/app/current/var

# Clear Symfony cache
cd /var/app/current
php bin/console cache:clear --no-warmup --env=prod
php bin/console cache:warmup --env=prod
