This project appears to be an **Exam Planning System** designed for managing courses, exams, schedules, and assistant assignments within a university or educational institution. It includes role-based access control, allowing different users (e.g., Admin, Dean, Head of Department, Secretary, Assistant) to perform specific tasks based on their roles.

Below is a **README** file for this project:

---

# Exam Planning System

The **Exam Planning System** is a web-based application designed to manage courses, exams, schedules, and assistant assignments within a university or educational institution. It provides role-based access control, allowing different users to perform specific tasks based on their roles.

## Features

### Role-Based Access Control
- **Admin**: Manages employees (e.g., adding new employees, assigning roles).
- **Dean**: Views and manages exams for their faculty.
- **Head of Department**: Manages department exams and assistant workloads.
- **Head of Secretary**: Manages faculty-wide exams, courses, and schedules.
- **Secretary**: Manages department-level exams, courses, and schedules.
- **Assistant**: Views and manages their assigned course schedules and exams.

### Key Functionalities
- **Course Management**: Add, view, and manage courses.
- **Exam Management**: Schedule exams, assign assistants, and manage exam details.
- **Schedule Management**: Add and manage course schedules.
- **Assistant Management**: Assign assistants to exams and courses, track assistant workloads.
- **Workload Tracking**: Monitor assistant workloads and scores.

## Files Overview

### 1. **`admin.php`**
   - Allows the admin to add new employees and assign roles (e.g., Dean, Head of Department, Secretary, Assistant).
   - Handles department and faculty assignments for employees.

### 2. **`assistant.php`**
   - Allows assistants to view and manage their assigned course schedules and exams.
   - Displays a weekly plan for the assistant.

### 3. **`config.php`**
   - Database configuration file. Connects to the MySQL database.

### 4. **`dashboard.php`**
   - The main dashboard that redirects users to their respective role-based pages.

### 5. **`dean.php`**
   - Allows the Dean to view and manage exams for their faculty.
   - Displays exam schedules for selected departments.

### 6. **`head_department.php`**
   - Allows the Head of Department to view department exams and assistant workloads.
   - Displays exam schedules and assistant scores.

### 7. **`head_secretary.php`**
   - Allows the Head of Secretary to manage faculty-wide exams, courses, and schedules.
   - Handles adding new courses, exams, and schedules.

### 8. **`header.php`**
   - Displays the header with user information (e.g., name, role, department, faculty).
   - Includes a logout button.

### 9. **`login.php`**
   - Handles user authentication. Users can log in with their username and password.
   - Redirects users to the dashboard after successful login.

### 10. **`logout.php`**
   - Logs out the user by destroying the session and redirecting to the login page.

### 11. **`secretary.php`**
   - Allows the Secretary to manage department-level exams, courses, and schedules.
   - Handles adding new courses, exams, and schedules.

## Database Schema

The system uses a MySQL database with the following key tables:

- **Employee**: Stores employee details (e.g., name, role, username, password, department ID).
- **Department**: Stores department details (e.g., department name, faculty ID, head ID).
- **Faculty**: Stores faculty details (e.g., faculty name, dean ID).
- **Course**: Stores course details (e.g., course code, name, department ID).
- **CourseSchedule**: Stores course schedules (e.g., course ID, date, start time, end time).
- **Exam**: Stores exam details (e.g., course ID, exam date, start time, end time, assistants needed).
- **Assignment**: Links assistants to exams or course schedules.

## How to Run the Project

1. **Database Setup**:
   - Create a MySQL database named `ExamPlanningSystem`.
   - Import the database schema and tables (you can use a `.sql` file for this).

2. **Configuration**:
   - Update the `config.php` file with your database credentials (e.g., `servername`, `username`, `password`).

3. **Run the Application**:
   - Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP or `www` for WAMP).
   - Access the application via a web browser (e.g., `http://localhost/ExamPlanningSystem`).

4. **Login**:
   - Use the `login.php` page to log in with valid credentials.
   - The system will redirect you to the appropriate dashboard based on your role.

## Dependencies

- **PHP**: The backend is built using PHP.
- **MySQL**: The database is managed using MySQL.
- **HTML/CSS**: The frontend is built using HTML and basic CSS.

## Developer

- **Ömer Dikyol**
- **Student Number**: 20200702002
