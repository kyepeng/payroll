# Project Name
Payroll Management System

## Installation
To get started with the project, follow these steps:

Build the Docker images:
```docker-compose build```

Start the Docker containers:
```docker-compose up -d```

Publish the vendor assets for Spatie Permission:
```php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"```

Run the database migrations:
```php artisan migrate```

Seed the database with initial data:
```php artisan db:seed```

## User Credentials
Admin
- Email: admin@payroll.com
- Password: admin

User
- Email: user@payroll.com
- Password: user

## Feature
Admin Role:-
- Users Management:
    -- View, create, update, and delete users
- Roles Management:
    -- View, create, update, and delete roles and permissions
- Timesheets Management:
    -- View, create, update, and delete all users' timesheets

User Role:-
- Timesheets Management:
-   View, create, update, and delete own timesheets.

Run PHPUnitTest:
```php artisan test```

## Development Guidelines
- **Laravel 7**: A powerful PHP framework for building web applications.
- **Blade Templating Engine**: Laravel's lightweight and intuitive templating engine.
- **Docker**: Containerization platform used for development and deployment.
- **Spatie Permission**: A popular Laravel package for role-based access control.
- **PHPUnit**: The PHP testing framework for unit and feature testing.
- **Yajra Datatable**: A Laravel package for rendering DataTables server-side.