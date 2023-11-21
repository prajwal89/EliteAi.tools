# *befor deploying
php vendor/bin/pint
php artisan test
php artisan dusk

php artisan meilisearch:index-all-documents-of-table tools
php artisan meilisearch:sync-settings tools

php artisan queue:retry all

php artisan migrate

# *deployment script
cd domains/clgnotes.esy.es/public_html
git stash
git pull
composer2 install --ignore-platform-reqs --no-scripts
composer2 dump-autoload
php artisan optimize:clear
php artisan optimize
php artisan test
