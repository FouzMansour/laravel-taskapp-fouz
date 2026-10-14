# Laravel Task Manager

A small Laravel app where a **manager** assigns tasks to **employees**, employees mark them complete, and the manager approves or rejects the result.

Built with Laravel 13, Blade, Breeze (login only), SQLite, and Pest.

## Roles and rules

- **Manager:** sees all tasks, creates and assigns tasks, edits/deletes tasks while `pending`, approves or rejects `completed` tasks (a note is required on reject).
- **Employee:** sees only their own tasks, marks them complete when `pending` or `rejected`.
- Task lifecycle: `pending` -> `completed` -> `approved` (final) or `rejected` -> `completed` again.
- Forbidden actions return a 403 page. All rules live in `app/Policies/TaskPolicy.php`.

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js 20 or newer

## Setup

```bash
git clone <repo-url> laravel-taskapp
cd laravel-taskapp

composer install
npm install
npm run build

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate:fresh --seed
```

## Run

```bash
composer run dev
```

Then open http://localhost:8000 (or your Herd URL, e.g. http://taskapp.test).

## Seeded logins

All passwords are `password`.

| Role     | Email                 |
|----------|-----------------------|
| Manager  | manager@example.com   |
| Employee | ali@example.com       |
| Employee | sara@example.com      |
| Employee | omar@example.com      |

Public registration is disabled. Only the seeder creates users.

## Tests

```bash
php artisan test
```

The feature tests are in `tests/Feature/TaskTest.php`. The registration tests that came with Breeze are skipped because registration is disabled.

## Project structure (main files)

- `app/Enums/TaskStatus.php`: the four task statuses
- `app/Models/Task.php`, `app/Models/User.php`: models and relationships
- `app/Policies/TaskPolicy.php`: all authorization rules
- `app/Http/Controllers/TaskController.php`: CRUD
- `app/Http/Controllers/TaskStatusController.php`: complete, approve, reject
- `app/Http/Requests/`: form validation
- `resources/views/tasks/`: Blade views