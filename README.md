# EduTask - Project Management System (Backend)

EduTask is a web-based project management system specifically designed for academic environments. This application enables lecturers, students, and research teams to collaborate on projects, manage tasks, and track work progress efficiently.

## Key Features

- **JWT Authentication**: Secure login, registration, and user authorization using JWT tokens with refresh token support.
- **Multi-role User System**: Distinct roles for Admin, Project Manager, and Team Members with granular permissions.
- **Project Management**: Full CRUD operations for projects and tasks.
- **Task Status Tracking**: Lifecycle management for tasks (To Do, In Progress, Review, Done).
- **Collaboration**: Comment system for team discussions on specific tasks.
- **Dashboard**: Real-time statistics and progress visualization using Chart.js.
- **Progress Visualization**: Interactive charts to monitor project health.
- **File Upload**: System for attaching files to tasks and projects.
- **Presentation Scheduling**: Feature for scheduling presentations with notifications.

## Technology Stack

- **Framework**: Laravel (Backend API)
- **Authentication**: JWT Authentication
- **Authorization**: Spatie Permission for role and permission management
- **Database**: MySQL/SQLite
- **Architecture**: REST API for seamless frontend integration

## Entity Relationship Diagram (ERD)

```
+---------------+       +---------------+       +---------------+
|     Users     |       |    Projects   |       |     Tasks     |
+---------------+       +---------------+       +---------------+
| id            |       | id            |       | id            |
| name          |       | name          |       | title         |
| email         |       | description   |       | description   |
| password      |       | created_by    |------>| project_id    |----+
| position      |<------| manager_id    |       | assigned_to   |<---+
| department    |       | start_date    |       | created_by    |<---+
| avatar        |       | end_date      |       | status        |
| bio           |       | status        |       | priority      |
| phone         |       | priority      |       | due_date      |
| roles         |       | code          |       | start_date    |
+-------+-------+       | is_archived   |       | estimated_hrs |
        |               | progress      |       | actual_hours  |
        |               +-------+-------+       | completed_at  |
        |                       |               | task_code     |
        |               +-------v-------+       +-------+-------+
        |               | project_user  |               |
        |               +---------------+               |
        +-------------->| project_id    |               |
                        | user_id       |               |
                        | role          |               |
                        | joined_at     |<--------------+
                        +---------------+
```

## Installation & Setup

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/edutask-backend.git
   cd edutask-backend
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   Copy the example environment file and configure your database settings.
   ```bash
   cp .env.example .env
   ```
   Update the `.env` file with your database credentials:
   ```env
   DB_DATABASE=edutask
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key & JWT Secret**
   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Run the Server**
   ```bash
   php artisan serve
   ```
   The API will be available at `http://localhost:8000`.

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Login and retrieve token
- `POST /api/auth/logout` - Logout user
- `POST /api/auth/refresh` - Refresh JWT token
- `GET /api/auth/me` - Get current user profile

### Projects
- `GET /api/projects` - List all projects
- `POST /api/projects` - Create a new project
- `GET /api/projects/{id}` - Get project details
- `PUT /api/projects/{id}` - Update project
- `DELETE /api/projects/{id}` - Delete project

### Tasks
- `GET /api/projects/{id}/tasks` - List tasks for a project
- `POST /api/tasks` - Create a new task
- `PUT /api/tasks/{id}` - Update task status/details

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


