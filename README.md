# Laravel Translation Management Task

This is a Translation Management API built with Laravel 10, designed for storing and retrieving translations efficiently.

## Features
- Store translations for multiple locales (`en`, `fr`, `es`, etc.).
- Tag translations for context (`mobile`, `desktop`, `web`).
- Search translations by tags, keys, or content.
- JSON export endpoint for frontend applications.
- Performance optimized for large datasets (100k+ records).
- Authentication using Laravel Sanctum.
- Docker support for easy setup.
- API documentation via Swagger.
- Comprehensive unit and feature tests.

## Installation

### Prerequisites
- Docker & Docker Compose
- PHP 8.2
- Composer

### Setup
1. Clone the repository:
   ```sh
   git clone https://github.com/haider8278/TranslationTask
   cd TranslationTask
   ```

Copy environment variables:

```sh 
cp .env.docker .env
```

Build and run the Docker containers:
```sh
docker-compose up -d --build
```

Run database migrations:
```sh
docker-compose exec app php artisan migrate --seed
```

Generate application key:

```sh
docker-compose exec app php artisan key:generate
```

Create a user for authentication:

```sh
docker-compose exec app php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password')
]);
```

Get API token:

```sh
curl -X POST -d "email=admin@example.com&password=password" http://localhost:8000/api/login
```

### Swagger API Documentation
The API documentation is available at `http://localhost:8000/api/documentation`
