# Adonis Men's Grooming

Adonis Men's Grooming is a Laravel 9 and React 19 web application for a premium men's salon in Dhaka. It includes a public single-page website, appointment booking, service and price-list content, barber profiles, blogs, SMTP notifications, and a protected Laravel admin panel.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Variables](#environment-variables)
- [Database Setup](#database-setup)
- [Running Locally](#running-locally)
- [Admin Panel](#admin-panel)
- [Useful Scripts](#useful-scripts)
- [API Reference](#api-reference)
- [Build and Deployment](#build-and-deployment)
- [Troubleshooting](#troubleshooting)
- [Security Notes](#security-notes)

## Features

- Responsive public salon website built with React, Vite, Tailwind CSS, and Motion.
- Laravel backend with MySQL-backed services, barbers, blogs, appointments, FAQs, carousel images, settings, and price-list data.
- Branch-aware service and price-list browsing for Gulshan and Bashundhara.
- Appointment booking with Bangladesh phone number validation.
- Email notification support through SMTP environment variables or admin settings.
- Blog content management with SEO title and description fields.
- Admin dashboard for managing appointments, services, barbers, blogs, FAQs, price list, carousel images, and site settings.
- Asset library for salon photos, barber portraits, service visuals, and brand imagery.
- Vite development proxy from `localhost:3000` to the Laravel API on `localhost:8000`.

## Tech Stack

### Backend

- PHP `^8.0.2`
- Laravel `^9.19`
- Laravel Sanctum
- MySQL
- PHPUnit

### Frontend

- React `^19.0.1`
- TypeScript `~5.8.2`
- Vite `^6.2.3`
- Tailwind CSS `^4.1.14`
- Lucide React icons
- Motion animations

## Project Structure

```text
app/                    Laravel models, controllers, middleware, notifications
bootstrap/              Laravel bootstrap files
config/                 Laravel configuration
database/               Migrations, seeders, SQL dump
public/                 Public entry point and static assets
resources/              Blade views and Laravel frontend resources
routes/                 Web, API, console, and channel routes
src/                    React application source
tests/                  PHPUnit tests
dev.js                  Starts Vite and Laravel concurrently
vite.config.ts          Vite build and dev proxy configuration
```

## Requirements

- PHP 8.0.2 or newer
- Composer
- Node.js 18 or newer
- npm
- MySQL or MariaDB
- XAMPP is supported on Windows and is the current local setup used by this project

## Installation

Clone the repository:

```bash
git clone <repository-url>
cd adonis
```

Install PHP dependencies:

```bash
composer install
```

If Composer is not globally available but `composer.phar` is present:

```bash
php composer.phar install
```

Install JavaScript dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

## Environment Variables

The main runtime settings are stored in `.env`.

```env
APP_NAME="Adonis"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_USER=root
MYSQL_PASSWORD=
MYSQL_DATABASE=adonis_cms

SMTP_HOST=
SMTP_PORT=587
SMTP_SECURE=false
SMTP_USER=
SMTP_PASS=
SMTP_FROM_EMAIL=noreply@adonis.com.bd
SMTP_ADMIN_EMAILS=admin@adonis.com.bd
```

Notes:

- `.env` is intentionally ignored by Git.
- `.env.example` should stay in the repository as the shareable template.
- The application reads custom MySQL variables named `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_USER`, `MYSQL_PASSWORD`, and `MYSQL_DATABASE`.
- SMTP values are used for booking and appointment notification email.

## Database Setup

Create a MySQL database:

```sql
CREATE DATABASE adonis_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Run migrations and seed the default salon data:

```bash
php artisan migrate --seed
```

The seeder adds default services, barbers, blog content, and branch price-list data.

If you prefer importing a snapshot, the repository also includes:

```text
database.sql
```

Import it into the same database only when you want to restore that SQL dump instead of using migrations and seeders.

## Running Locally

Start both the React dev server and the Laravel server:

```bash
npm run dev
```

This runs:

- Vite frontend: `http://localhost:3000`
- Laravel backend: `http://127.0.0.1:8000`

During development, use the Vite URL:

```text
http://localhost:3000
```

The Vite server proxies `/api` and `/uploads` requests to Laravel.

You can also run each server separately:

```bash
npm run vite
php artisan serve --host=127.0.0.1 --port=8000
```

On the current Windows/XAMPP setup, `npm start` uses:

```text
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

## Admin Panel

Admin login:

```text
/admin/login
```

Protected admin pages include:

- `/admin/dashboard`
- `/admin/appointments`
- `/admin/faqs`
- `/admin/carousel`
- `/admin/price-list`
- `/admin/barbers`
- `/admin/blogs`
- `/admin/settings`

Admin access requires a user record with `role = admin`. If the database does not already contain one, create an admin user with Laravel Tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('change-this-password'),
    'role' => 'admin',
]);
```

Change the email and password before using this in any shared or production environment.

## Useful Scripts

```bash
npm run dev
```

Starts Vite and Laravel together through `dev.js`.

```bash
npm run vite
```

Starts only the Vite dev server on port `3000`.

```bash
npm run build
```

Builds the React app into `public/build`.

```bash
npm run preview
```

Runs Vite preview for the compiled frontend.

```bash
npm run lint
```

Runs TypeScript checking with `tsc --noEmit`.

```bash
php artisan test
```

Runs the Laravel/PHPUnit test suite.

## API Reference

### Public Laravel Web API

These routes are defined in `routes/web.php` and are consumed by the React frontend:

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/services` | List services. Supports `branch_id=all`, `gulshan`, or `bashundhara`. |
| GET | `/api/barbers` | List barber profiles. |
| GET | `/api/blogs` | List published blog posts. |
| GET | `/api/price-list/{branch?}` | List grouped price-list items for all branches or one branch. |

### Laravel API Routes

These routes are defined in `routes/api.php`:

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/data` | Combined CMS payload for frontend data. |
| PUT | `/api/settings` | Update site settings. |
| PUT | `/api/smtp` | Update SMTP configuration stored in CMS metadata. |
| GET | `/api/smtp/status` | Check current SMTP configuration status. |
| POST | `/api/smtp/test` | Send a test email. |
| POST | `/api/services` | Create or update a service. |
| PUT | `/api/services/{id}` | Update a service. |
| DELETE | `/api/services/{id}` | Delete a service. |
| POST | `/api/barbers` | Create or update a barber. |
| PUT | `/api/barbers/{id}` | Update a barber. |
| DELETE | `/api/barbers/{id}` | Delete a barber. |
| GET | `/api/blogs` | List blog posts. |
| POST | `/api/blogs` | Create a blog post. |
| PUT | `/api/blogs/{id}` | Update a blog post. |
| DELETE | `/api/blogs/{id}` | Delete a blog post. |
| POST | `/api/upload` | Upload a portrait/image file. |
| POST | `/api/bookings` | Store a booking from the React booking flow. |
| GET | `/api/bookings` | List stored bookings. |
| POST | `/api/appointments` | Store a public appointment request. |

## Frontend Routes

The React app handles client-side navigation for:

- `/`
- `/services`
- `/services/gulshan`
- `/services/bashundhara`
- `/about`
- Blog pages and other SPA paths handled by the catch-all Laravel view

Laravel serves the SPA catch-all through:

```php
Route::view('/{path?}', 'app')->where('path', '.*');
```

## Build and Deployment

Create a production frontend build:

```bash
npm run build
```

Optimize Laravel for production:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Typical deployment checklist:

1. Upload application files excluding local-only folders such as `node_modules`.
2. Install Composer dependencies on the server with `composer install --no-dev --optimize-autoloader`.
3. Set production `.env` values.
4. Run `php artisan key:generate` if `APP_KEY` is empty.
5. Run `php artisan migrate --seed` or import `database.sql`.
6. Run `npm install` and `npm run build`, or build locally and deploy `public/build`.
7. Make sure `storage/` and `bootstrap/cache/` are writable by the web server.
8. Point the web server document root to `public/`.
9. Configure SMTP before enabling live booking emails.

For Apache, the document root should point at:

```text
public/
```

## Troubleshooting

### Vite Loads but API Calls Fail

Make sure Laravel is running on:

```text
http://127.0.0.1:8000
```

The Vite proxy in `vite.config.ts` sends `/api` and `/uploads` to `http://localhost:8000`.

### Database Tables Are Missing

Run:

```bash
php artisan migrate --seed
```

Then confirm the `.env` database values match your MySQL database.

### Admin Login Fails

Confirm that the user exists in the `users` table and has:

```text
role = admin
```

### Booking Emails Are Not Sent

Check these values:

- `SMTP_HOST`
- `SMTP_PORT`
- `SMTP_SECURE`
- `SMTP_USER`
- `SMTP_PASS`
- `SMTP_FROM_EMAIL`
- `SMTP_ADMIN_EMAILS`

Also check Laravel logs in:

```text
storage/logs/
```

### Assets Do Not Display

Confirm that images exist in:

```text
public/assets/
public/assets/images/
```

If uploaded files are used, verify that the upload path is publicly served by the web server.

## Security Notes

- Never commit `.env`, SMTP passwords, database passwords, or production credentials.
- Replace any temporary admin password immediately.
- Use `APP_DEBUG=false` in production.
- Use HTTPS in production.
- Restrict admin panel access where possible.
- Keep Composer and npm dependencies updated.

## License

This project currently uses the Laravel application skeleton license metadata from `composer.json` (`MIT`). Confirm the final business/project license before publishing publicly.
