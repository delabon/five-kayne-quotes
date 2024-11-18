### How to setup

Open your terminal and run the following:

```sh
composer install
cp .env.example .env
vendor/bin/sail up --build -d
vendor/bin/sail artisan key:generate
vendor/bin/sail artisan migrate # select 'yes'
vendor/bin/sail artisan db:seed
npm install && npm run build
```

Open: http://localhost

Credentials: test@example.com / 12345

### How to run tests

```sh
vendor/bin/sail artisan test --testsuite=Unit
vendor/bin/sail artisan test --testsuite=Integration
vendor/bin/sail artisan test --testsuite=Feature
```
