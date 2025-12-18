# MCL200 Remote Monitoring Platform

[![Version](https://img.shields.io/github/v/release/OWNER/REPO?style=flat-square)](https://github.com/OWNER/REPO/releases)
[![License](https://img.shields.io/github/license/OWNER/REPO?style=flat-square)](./LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/OWNER/REPO/ci.yml?branch=main&style=flat-square)](https://github.com/OWNER/REPO/actions)

The MCL200 Remote Monitoring Platform is a Laravel-based web application for configuring, monitoring, and managing MCL200 marine lanterns in the field.  
It provides operators with real-time status, historical data, and remote control capabilities to simplify deployment and maintenance.  
The platform is designed to be secure, scalable, and easy to integrate with existing marine infrastructure.

---

## Features

- Remote supervision of MCL200 marine lanterns via GSM, VHF, or satellite
- GPS-based synchronization of lanterns
- Device configuration management (range, intensity, timing, etc.)
- Real-time status dashboard and alerts
- Historical logs and reporting
- Role-based access control for operators and administrators
- RESTful API for integrations with external systems

---

## Screenshots

| ![Screenshot 1](screenshots/screen1.png) | ![Screenshot 2](screenshots/screen2.png) | ![Screenshot 3](screenshots/screen3.png) |
|:---:|:---:|:---:|
| *Dashboard overview* | *Device detail view* | *Configuration editor* |

> Note: Replace the screenshot images in `screenshots/` with actual application screenshots.

---

## Installation

### Prerequisites

- PHP >= 8.0
- Composer
- Node.js & npm
- A database server (e.g. MySQL / MariaDB / PostgreSQL)
- Git

### Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/OWNER/REPO.git
   cd REPO
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install frontend dependencies**

   ```bash
   npm install
   ```

4. **Copy and configure the environment file**

   ```bash
   cp .env.example .env
   ```

   Then open `.env` and update:

   - `APP_NAME`
   - `APP_URL`
   - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - Any other environment-specific configuration

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Run database migrations (and seed if needed)**

   ```bash
   php artisan migrate
   # Optional
   php artisan db:seed
   ```

7. **Build frontend assets**

   ```bash
   npm run dev    # For development
   # or
   npm run prod   # For production
   ```

8. **Start the development server**

   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000` by default.

---

## Usage

### Accessing the Dashboard

Once the application is running:

- Open `http://localhost:8000` in your browser.
- Log in with the credentials created during seeding or registration (depending on how authentication is set up).
- Navigate to the dashboard to view lantern status and statistics.

### Example: Managing Lanterns via Artisan Command

```bash
php artisan lanterns:sync-status
```

This example command (replace with an actual command from the project if different) could be used to synchronize device status from remote units.

### Example: Consuming the API

If the application exposes an API, a typical request might look like:

```bash
curl -X GET "http://localhost:8000/api/lanterns" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_API_TOKEN"
```

---

## Project Structure

A brief overview of important files and directories:

- `app/` – Application core (models, controllers, services, jobs, etc.)
- `routes/` – Route definitions (`web.php`, `api.php`, etc.)
- `resources/views/` – Blade templates for the UI
- `resources/js/` & `resources/sass/` – Frontend JavaScript and styles
- `config/` – Application configuration files
- `database/migrations/` – Database schema definitions
- `tests/` – Automated tests
- `public/` – Web server document root
- `webpack.mix.js` – Laravel Mix asset compilation configuration
- `DOCS/` – Additional project documentation

---

## Technologies

Main technologies used in this project:

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&amp;logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?logo=laravel&amp;logoColor=white)](https://laravel.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?logo=javascript&amp;logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Node.js](https://img.shields.io/badge/Node.js-LTS-339933?logo=node.js&amp;logoColor=white)](https://nodejs.org/)
[![Webpack](https://img.shields.io/badge/Webpack-Mix-8DD6F9?logo=webpack&amp;logoColor=black)](https://webpack.js.org/)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?logo=mysql&amp;logoColor=white)](https://www.mysql.com/)

> Adjust specific versions and technologies above to match the actual stack if it differs.

---

## Contributing

Contributions are welcome and appreciated.

1. Fork the repository.
2. Create a new branch for your feature or fix:

   ```bash
   git checkout -b feature/my-new-feature
   ```

3. Make your changes and add tests where appropriate.
4. Run the test suite to ensure everything passes:

   ```bash
   phpunit
   # or
   php artisan test
   ```

5. Commit your changes with a descriptive message:

   ```bash
   git commit -am "feat: add my new feature"
   ```

6. Push your branch:

   ```bash
   git push origin feature/my-new-feature
   ```

7. Open a Pull Request describing your changes in detail.

Please adhere to any existing code style and conventions used in the project.

---

## License

This project is licensed under the terms of the [LICENSE](./LICENSE) file included in this repository.

If there is no license file yet, consider adding one (e.g. MIT, Apache 2.0) and updating this section accordingly.