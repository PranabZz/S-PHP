touch /var/www/html/app/services/jobs.json
chmod 664 /var/www/html/app/services/jobs.json
php do migrate
exec apache2-foreground