# JuiceBox Dev Test 

Simple IMPL Laravel 13, Sanctum Auth, Post API, OpenWeatherMap Service API and Queue Job 

---

## Getting Started

### Prerequisites
Ensure you have the following installed on your system:
* **PHP**  >= 8.3
* **Composer** 2.x 
* **MySQL** 8.0+

### Installation Steps

1. **Clone the repository** and navigate to the project directory:
   ```bash
   git clone https://github.com/ikhwanudin/juicebox_dev_test
   cd <project-folder>
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Environment Setup**:
   Copy the example environment file and generate your application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**:
   Open your `.env` file and update your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

---

## Database Migrations & Seeding

To set up your database schema and populate it with initial or dummy data, run the combined migration and seed command:

```bash
php artisan migrate --seed
```

### Useful Commands:
* **Run migrations only**: `php artisan migrate`
* **Fresh reinstall** (drops all tables and re-runs all migrations + seeds):
  ```bash
  php artisan migrate:fresh --seed
  ```

---

## Development Configuration (Queues, Cache, & Email)

For ease of local development, this project utilizes local database tables and log files instead of requiring external services like Redis or Mailgun.

Ensure your `.env` file reflects the following settings:

```env
QUEUE_CONNECTION=database
CACHE_STORE=database
MAIL_MAILER=log
OWM_KEY=your_api_key_here
```

### 1. Queue Configuration (`database`)
* **How it works:** Instead of sending asynchronous jobs (like sending emails or processing files) to a third-party service, Laravel stores the jobs inside a `jobs` table in your MySQL database.
* **Why use it:** Eliminates the need to install Redis or Beanstalkd locally.

### 2. Cache Configuration (`database`)
* **How it works:** Application cache data and session data are stored directly inside the `cache` and `cache_locks` tables in your database.
* **Why use it:** Highly visible, easy to clear, and requires zero external infrastructure tools to test caching logic.

### 3. Email Driver (`log`)
* **How it works:** The application will **not** send real emails to recipients. Instead, the full content of any outgoing email (including headers and HTML body) is written directly into your local log file.
* **Where to find them:** Open `storage/logs/laravel.log` to view your simulated emails.

### 4. OpenWeatherMap Setup

This application connects to the OpenWeatherMap API for weather data.

#### Get API Key
1. Register an account at [OpenWeatherMap Sign Up](https://home.openweathermap.org/users/sign_up).
2. Go to your [API Keys Dashboard](https://home.openweathermap.org/api_keys).
3. Copy your generated API Key *(Note: New keys can take up to 2 hours to activate)*.

---

## Running the Application

### 1. Start the Local Server
```bash
php artisan serve
```

### 2. Run the Queue Worker
Because the queue is set to `database`, jobs will sit in the database until a worker processes them. Run this command in a separate terminal window to process background jobs:

```bash
php artisan queue:work
```

### 3. Access API Documentation
This project uses **Dedoc Scramble** to automatically generate OpenAPI documentation for your API endpoints.

Once your local server is running, you can view the interactive documentation by visiting: `/docs/api`

### example user

```text
email: test@example.com
pass: Test123!
```

---

## Utility Commands

* **Clear Application Cache**: `php artisan cache:clear`
* **Clear Config Cache**: `php artisan config:clear`
* **Clear Route Cache**: `php artisan route:clear`
