
# Tradie White Page MPA Frontend

This project is a PHP (Lumen) application for the Tradie White Page multi-page frontend.

## How to Run

1. Install dependencies:
	```bash
	composer install
	```

2. Start the application using PHP built-in server:
	```bash
	php -S localhost:8000 -t public
	```

3. Access the app at [http://localhost:8000](http://localhost:8000)

## Run with Docker

You can run the Lumen MPA using Docker (PHP-FPM + Nginx).

### 1. Build & start (docker compose)

```bash
docker compose up --build -d
```

Access the app at: http://localhost:8080

### 2. Environment variables

Create a `.env` (copy from `.env.example`) before building so PHP dependencies using env vars get correct values. You can also override at runtime:

```bash
API_BASE_URL=https://api.example.test APP_DEBUG=true docker compose up -d --build
```

### 3. Executing Artisan / Composer inside container

```bash
docker compose exec app php artisan list
docker compose exec app composer install
```

### 4. Xdebug (optional)

Xdebug is installed. To enable remote debugging set in your `.env` (or via compose env):

```
XDEBUG_MODE=develop,debug
XDEBUG_CONFIG=client_host=host.docker.internal client_port=9003
```

Then restart containers.

### 5. Rebuild without dev dependencies

```bash
COMPOSER_NO_DEV=1 docker compose build --no-cache app
```

### 6. Clean up

```bash
docker compose down -v
```

### 7. Without docker compose (manual)

```bash
docker build -t tradie-mpa-frontend .
docker run --rm -p 9000:9000 --name tradie-mpa-frontend tradie-mpa-frontend
```
Then run a separate Nginx container mounting `nginx.conf` and pointing to `php-fpm:9000` (compose automates this for you).

## Folder Structure

```
├── app/                # Application core (controllers, models, jobs, etc.)
│   ├── Console/        # Custom Artisan commands
│   ├── Events/         # Event classes
│   ├── Exceptions/     # Exception handlers
│   ├── Http/           # Controllers and middleware
│   ├── Jobs/           # Queue jobs
│   ├── Listeners/      # Event listeners
│   ├── Models/         # Eloquent models
│   └── Providers/      # Service providers
├── bootstrap/          # Application bootstrap files
├── database/           # Migrations, seeders, factories
├── public/             # Public web root (entry point)
├── resources/
│   └── views/          # Blade templates
├── routes/             # Route definitions
├── storage/            # Logs, cache, etc.
├── tests/              # Test cases
├── vendor/             # Composer dependencies
├── composer.json       # Dependency definition
├── artisan             # Artisan CLI
```

## Maintenance Notes

- Keep controllers, models, and business logic organized in the `app/` folder.
- Use migrations and seeders in `database/` for database changes.
- Place Blade templates in `resources/views/`.
- Define routes in `routes/web.php`.
- Run tests from the `tests/` folder.

## Environment Variables

Copy `.env.example` to `.env` and set values as needed. Key variable:

- `API_BASE_URL` — the gateway domain for all backend API calls.
	- Local example: `http://localhost:8888`
	- Production (Railway): set in the Service Variables to your public domain, e.g. `https://<your-backend>.up.railway.app`

In Blade files, JavaScript reads this value via:

```html
<script>
	const apiBaseUrl = "{{ env('API_BASE_URL') }}";
	// use apiBaseUrl to build API URLs
</script>
```

## License

This project is open-sourced software licensed under the MIT license.
