# OCM task

App for fetching data from external API and storing data in database. Due to high volume of data on initial route hit partial data will be downloaded so the user can interact and rest of data is forwarded to background job to download and store in database. It usually takes 20 seconds to download data so after that full data will be ready.

API has corrupted data and duplicates which i wasnt aware of when i started using it, so the duplicates in the database are strictly due to bad data.

Search bar hits the same route because it would be redundant to duplicate code for the controller.

## Features

-   store data from external api
-   display or search paginated data

## Setup

1. Copy env file

```bash
cp .env.example .env
```

2. Install required packages (Docker login is required)

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

3. Start laravel sail

```bash
./vendor/bin/sail up -d
```

4. Generate app key

```bash
./vendor/bin/sail php artisan key:generate
```

5. Migrate the database

```bash
./vendor/bin/sail php artisan migrate
```

6. Install npm packages

```bash
./vendor/bin/sail npm install
```

7. Run laravel queue

```bash
./vendor/bin/sail php artisan queue:work
```

8. Run the app in seperate terminal

```bash
./vendor/bin/sail npm run dev
```

Now you can access the app using url: [http://localhost](http://localhost)

## Aditional features

You can also run tests with coverage by running:

```bash
./vendor/bin/sail php artisan test --coverage
```

Current test coverage is 83%
