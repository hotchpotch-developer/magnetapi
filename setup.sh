composer update
php artisan optimize:clear
php artisan cache:clear
php artisan migrate
composer dump-autoload
exit
