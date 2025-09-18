
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

## License

This project is open-sourced software licensed under the MIT license.
