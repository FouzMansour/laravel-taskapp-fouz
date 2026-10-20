# Laravel Task Manager

A small Laravel app where a **manager** assigns tasks to **employees**, employees mark them complete, and the manager approves or rejects the result.

Built with Laravel 13, Blade, Breeze (login only), SQLite, and Pest.

## Roles and rules

- **Manager:** sees all tasks, creates and assigns tasks, edits/deletes tasks while `pending`, approves or rejects `completed` tasks (a note is required on reject).
- **Employee:** sees only their own tasks, marks them complete when `pending` or `rejected`.
- Task lifecycle: `pending` -> `completed` -> `approved` (final) or `rejected` -> `completed` again.
- Forbidden actions return a 403 page. All rules live in `app/Policies/TaskPolicy.php`.

## Realtime Notifications

Real-time notifications are implemented using Laravel Reverb, Laravel Notifications, and Laravel Echo.

Notifications are delivered without requiring users to refresh the page.

### Notification events

- **Task assigned:** The assigned employee receives a notification when a manager creates and assigns a task.
- **Task completed:** The task creator (manager) receives a notification when an employee completes a task.
- **Task approved:** The assigned employee receives a notification when the manager approves a task.
- **Task rejected:** The assigned employee receives a notification when the manager rejects a task.

### Notification features

- Real-time notifications using WebSockets.
- Database notifications for persistent storage.
- Private user channels to deliver notifications to the intended user.
- Unread notifications displayed in the application.
- Notifications link to the related task.
- Notifications can be marked as read.

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

Configure the database, queue, and Reverb environment variables in `.env` according to your local setup.

## Run

Start the Laravel application:

```bash
composer run dev
```

Start the Reverb WebSocket server in a separate terminal:

```bash
php artisan reverb:start
```

Start the queue worker in another terminal:

```bash
php artisan queue:work
```

Then open http://localhost:8000 (or your Herd URL, e.g. http://taskapp.test).

Keep the Reverb server and queue worker running while testing real-time notifications.

## Seeded logins

All passwords are `password`.

| Role | Email |
|---|---|
| Manager | manager@example.com |
| Employee | ali@example.com |
| Employee | sara@example.com |
| Employee | omar@example.com |

Public registration is disabled. Only the seeder creates users.

## Tests

Run the test suite:

```bash
php artisan test
```

The feature tests are in `tests/Feature/TaskTest.php`. The registration tests that came with Breeze are skipped because registration is disabled.

## Project structure (main files)

- `app/Enums/TaskStatus.php`: the four task statuses
- `app/Models/Task.php`, `app/Models/User.php`: models and relationships
- `app/Policies/TaskPolicy.php`: all authorization rules
- `app/Http/Controllers/TaskController.php`: task CRUD operations
- `app/Http/Controllers/TaskStatusController.php`: complete, approve, and reject actions
- `app/Http/Requests/`: form validation
- `app/Notifications/TaskNotification.php`: database and broadcast notifications
- `routes/channels.php`: private user channel authorization
- `routes/web.php`: application routes and notification read endpoint
- `resources/views/tasks/`: Blade views