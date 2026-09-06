# Laravel Task Manager

A simple task management web application built with Laravel.

This project allows users to create, edit, delete, and reorder tasks using drag and drop. Task priority is automatically updated based on its position in the list.

As a bonus feature, tasks can also be assigned to projects. Users can select a project from a dropdown and view only the tasks related to that project.

---

## Demo Video

A video demonstration of the application is available here:

**Demo Video link (Opens in Google Drive):** 
https://drive.google.com/file/d/1oaLdDt5lcYM8euFP-3YySpl_b3fbzZdR/view?usp=sharing

The video demonstrates:

- Creating a project
- Creating tasks
- Editing a task
- Deleting a task
- Dragging and dropping tasks
- Automatic priority updates
- Filtering tasks by project
- Data being saved in MySQL

---

## Assignment Requirements

### Required Features

- Create a task
- Save task name
- Save task priority
- Save task timestamps
- Edit a task
- Delete a task
- Reorder tasks using drag and drop
- Automatically update priority after reordering
- Priority #1 appears at the top
- Tasks are saved in a MySQL database
- PHP 8.3+
- Laravel 11+

### Bonus Feature

- Create projects
- Assign tasks to projects
- Select a project from a dropdown
- View only tasks belonging to the selected project

---

## Technologies Used

- PHP 8.3
- Laravel 12
- MySQL 8
- Docker
- Docker Compose
- Nginx
- Bootstrap 5
- SortableJS

> Laravel 12 is used because the requirement specifies Laravel version 11 or higher.

---

# Features

## Task Management

Users can:

- Create tasks
- Edit tasks
- Delete tasks
- View task priority
- View task creation date
- Drag and drop tasks to change their order

## Automatic Priority

Task priority is based on its position in the list.

Example:

```text
#1 Complete project documentation
#2 Create database migrations
#3 Test application
```

If task #3 is dragged to the top, the priorities automatically become:

```text
#1 Test application
#2 Complete project documentation
#3 Create database migrations
```

The new priorities are saved in the MySQL database.

## Project Management

Users can:

- Create projects
- Delete projects
- Assign tasks to projects
- Select a project from a dropdown
- View only tasks related to the selected project

Each project has its own task list and priority order.

---

# Project Structure

```text
task-manager-coalition/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ProjectController.php
│   │       └── TaskController.php
│   │
│   └── Models/
│       ├── Project.php
│       └── Task.php
│
├── database/
│   └── migrations/
│       ├── create_projects_table.php
│       └── create_tasks_table.php
│
├── docker/
│   └── nginx/
│       └── default.conf
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       │
│       ├── projects/
│       │   └── index.blade.php
│       │
│       └── tasks/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
│
├── routes/
│   └── web.php
│
├── Dockerfile
├── docker-compose.yml
├── .env.example
└── README.md
```

---

# Database Structure

## Projects Table

| Column | Description |
|---|---|
| id | Project ID |
| name | Project name |
| created_at | Creation timestamp |
| updated_at | Update timestamp |

## Tasks Table

| Column | Description |
|---|---|
| id | Task ID |
| project_id | Related project |
| name | Task name |
| priority | Task priority |
| created_at | Creation timestamp |
| updated_at | Update timestamp |

A task belongs to one project, and a project can have multiple tasks.

---

# Requirements

To run this project using Docker, you need:

- Docker Desktop
- Docker Compose

No local PHP, MySQL, or Nginx installation is required.

---

# Installation and Setup

## 1. Clone or Extract the Project

```bash
git clone <repository-url>
```

Move into the project folder:

```bash
cd task-manager-coalition
```

If you received the project as a ZIP file, extract it and open the terminal inside the project folder.

---

## 2. Create Environment File

Copy the example environment file.

### Windows PowerShell

```powershell
Copy-Item .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

Make sure the database configuration in `.env` is:

```env
APP_NAME="Task Manager"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

## 3. Build and Start Docker Containers

Run:

```bash
docker compose up -d --build
```

The first build may take a few minutes because Docker needs to download and build the required images.

Check that containers are running:

```bash
docker compose ps
```

You should see containers similar to:

```text
task_manager_app
task_manager_db
task_manager_nginx
task_manager_phpmyadmin
```

---

## 4. Install Composer Dependencies

Run:

```bash
docker compose exec app composer install
```

---

## 5. Generate Laravel Application Key

Run:

```bash
docker compose exec app php artisan key:generate
```

---

## 6. Clear Laravel Cache

Run:

```bash
docker compose exec app php artisan optimize:clear
```

---

## 7. Run Database Migrations

Run:

```bash
docker compose exec app php artisan migrate
```

For a completely fresh database:

```bash
docker compose exec app php artisan migrate:fresh
```

> Note: `migrate:fresh` deletes all existing tables and data.

---

# Running the Application

After Docker containers are running, open:

## Application

http://localhost:8000

## phpMyAdmin

http://localhost:8090

---

# phpMyAdmin Login

Use the following credentials:

```text
Server: db
Username: laravel
Password: secret
```

Database name:

```text
task_manager
```

---

# Useful Docker Commands

## Start Containers

```bash
docker compose up -d
```

## Stop Containers

```bash
docker compose down
```

## Rebuild Containers

```bash
docker compose up -d --build
```

## Check Container Status

```bash
docker compose ps
```

## View Logs

```bash
docker compose logs
```

## View Laravel Application Logs

```bash
docker compose logs app
```

## View Nginx Logs

```bash
docker compose logs nginx
```

---

# Running Laravel Commands

Because this project runs inside Docker, Laravel Artisan commands should be executed inside the Docker application container.

Example:

```bash
docker compose exec app php artisan migrate
```

Other useful commands:

## Check Laravel Version

```bash
docker compose exec app php artisan --version
```

## Check PHP Version

```bash
docker compose exec app php -v
```

## View Routes

```bash
docker compose exec app php artisan route:list
```

## Clear Cache

```bash
docker compose exec app php artisan optimize:clear
```

---

# Deployment

This project can be deployed to any server that supports Docker and Docker Compose.

Basic deployment steps:

1. Upload or clone the project to the server.
2. Create the `.env` file.
3. Update environment variables for production.
4. Build and start Docker containers:

```bash
docker compose up -d --build
```

5. Install dependencies:

```bash
docker compose exec app composer install --no-dev --optimize-autoloader
```

6. Generate the application key if needed:

```bash
docker compose exec app php artisan key:generate
```

7. Run migrations:

```bash
docker compose exec app php artisan migrate --force
```

8. Cache configuration:

```bash
docker compose exec app php artisan config:cache
```

For production, `APP_DEBUG` should be set to:

```env
APP_DEBUG=false
```

---

# Implementation Notes

The application follows standard Laravel practices:

- Eloquent models are used for database operations.
- Laravel migrations are used for database tables.
- Eloquent relationships are used between Projects and Tasks.
- Route model binding is used for task and project operations.
- Laravel validation is used for user input.
- Blade templates are used for the frontend.
- CSRF protection is enabled for forms.
- Database transactions are used when updating task priorities.
- Foreign keys maintain the relationship between projects and tasks.
- Cascade deletion removes related tasks when a project is deleted.

---

# Task Priority Logic

Tasks are ordered by priority in ascending order:

```text
Priority 1 → Top
Priority 2 → Second
Priority 3 → Third
```

When a user drags a task to a different position:

1. The browser detects the new task order.
2. The task IDs are sent to Laravel.
3. Laravel updates priorities in the database.
4. The first task receives priority `1`.
5. The second task receives priority `2`.
6. The process continues for all tasks.

This ensures that priorities always match the visual order.

---

# How to Test

## Project Test

1. Go to **Projects**.
2. Create a project.
3. Create another project.
4. Select a project from the Tasks page.
5. Confirm that only tasks from the selected project are displayed.

## Task Test

1. Select a project.
2. Create three tasks.
3. Confirm priorities are `#1`, `#2`, and `#3`.
4. Edit a task.
5. Delete a task.

## Drag and Drop Test

Create three tasks:

```text
#1 Task One
#2 Task Two
#3 Task Three
```

Drag `Task Three` to the top.

The new order should become:

```text
#1 Task Three
#2 Task One
#3 Task Two
```

Refresh the page.

The order should remain the same, confirming that the new priorities were saved to MySQL.

---

# Assignment Requirements Checklist

- [x] Laravel web application
- [x] Create task
- [x] Task name
- [x] Task priority
- [x] Task timestamps
- [x] Edit task
- [x] Delete task
- [x] Drag and drop reordering
- [x] Automatic priority updates
- [x] Priority #1 at the top
- [x] MySQL database
- [x] PHP 8.3+
- [x] Laravel 11+
- [x] Project functionality
- [x] Project dropdown filtering
- [x] Docker setup instructions
- [x] Deployment instructions

---

# Author

Created as a Laravel Task Management coding assignment.
