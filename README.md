### How to setup

Open your terminal and run the following:

```sh
composer install
npm install && npm run build
php artisan sail:install
vendor/bin/sail up
vendor/bin/sail artisan migrate
vendor/bin/sail artisan db:seed
```

Open: http://localhost

Credentials: test@example.com / 12345

### How to run tests

```sh
vendor/bin/sail artisan test --testsuite=Unit
vendor/bin/sail artisan test --testsuite=Integration
vendor/bin/sail artisan test --testsuite=Feature
```
