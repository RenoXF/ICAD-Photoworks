#/bin/bash

git config core.fileMode false
git config pull.rebase false
cp .env.example .env
chmod -R 777 .

sed -i 's/APP_PORT=80/APP_PORT=8888/' .env
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mariadb/' .env
sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=mariadb/' .env
sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=laravel/' .env
sed -i 's/# DB_USERNAME=root/DB_USERNAME=sail/' .env
sed -i 's/# DB_PASSWORD=/DB_PASSWORD=password/' .env
sed -i 's/SESSION_LIFETIME=120/SESSION_LIFETIME=360000000/' .env
sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/' .env
sed -i 's/APP_TIMEZONE=UTC/APP_TIMEZONE=Asia\/Jakarta/' .env
sed -i 's/APP_LOCALE=en/APP_LOCALE=id/' .env
sed -i 's/APP_FAKER_LOCALE=en_US/APP_FAKER_LOCALE=id_ID/' .env
sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=redis/' .env
sed -i 's/QUEUE_CONNECTION=database/QUEUE_CONNECTION=redis/' .env
sed -i 's/APP_ENV=local/APP_ENV=development/' .env

composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan storage:link
./vendor/bin/sail down
./vendor/bin/sail up -d