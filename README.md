# Chipper Together

Chipper Together is a social media app for sharing "Chips" — short posts — with a secure, user-friendly feed. Users can create accounts, post, edit, and delete Chips while enjoying robust security and a smooth experience for connecting with others.

Repository: [fortune-c/chipper](https://github.com/fortune-c/chipper)

## Features
- User registration, login, and authentication
- Create, edit, delete, and view Chips (short posts)
- Secure feed and user-friendly UI
- Basic authorization so users can manage their own Chips
- Responsive frontend (built with Laravel + common frontend tools)

## Tech stack
- Backend: Laravel (PHP)
- Database: MySQL / MariaDB / PostgreSQL (configurable via .env)
- Frontend tooling: Node / npm (for asset compilation)
- Optional: Laravel Queues, Scheduler, and Storage (for attachments)

## Prerequisites
- PHP 8.1+ (or the version your Laravel release requires)
- Composer
- Node.js & npm (for compiling assets)
- A database server (MySQL / MariaDB / Postgres)
- Git

## Quick start

1. Clone the repo
   git clone https://github.com/fortune-c/chipper.git
   cd chipper

2. Install PHP dependencies
   composer install

3. Install frontend dependencies and build assets
   npm install
   npm run dev
   (or `npm run build` for production)

4. Environment
   cp .env.example .env
   Edit `.env` and set your database and other environment variables.

5. App key & storage
   php artisan key:generate
   php artisan storage:link

6. Database
   php artisan migrate
   php artisan db:seed   # optional, if seeders exist

7. Serve the app
   php artisan serve
   Visit http://localhost:8000

## Running tests
Run the test suite:
php artisan test

## Common tips
- To run queues: php artisan queue:work
- To run scheduler locally: php artisan schedule:run
- Use `.env` to configure mail, third-party services, and storage drivers.

## Contributing
Contributions are welcome. Please fork the repository, make your changes on a feature branch, and open a pull request with a clear description of your changes.

## License
MIT — see LICENSE file for details.

## Contact
Project maintained by fortune-c — open issues or pull requests in the repository: https://github.com/fortune-c/chipper