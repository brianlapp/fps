## Installation

Clone the repo locally:

```sh
git clone git@github.com:marjan-pahor/new-search.git
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm i
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder (on initial install only):

```sh
php artisan db:seed
```

SCHEDULED JOBS:

set `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
` in the server cron

SET ALGOLIA INDICES
```shell
php artisan algolia:import-settings
```
QUEUES

```sh
php artisan queue:work redis --queue=scout
php artisan queue:work redis --queue=default --tries=3
php artisan queue:work redis --queue=improvement --tries=3
```

Download MaxMind DB (needs credentials set in .ENV)
```sh 
php artisan geoip:update
```
