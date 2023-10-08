#/var/www/crsdb.com/html/srm-backend
git pull
composer update --no-dev --prefer-dist
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan migrate
php artisan tenants:migrate
php artisan tenants:seed --class TenantPermissionSeeder
echo 'Deploy finished.'
