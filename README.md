# Student Management Information System (SIMS)

A role-based PHP + MySQL web application for managing students, lecturers, units, attendance, and results.

## Overview

SIMS provides three portals:
- `admin`: manage users, academic years/semesters, and units.
- `lecturer`: view assigned units, mark attendance, and upload results.
- `student`: register units, view attendance/results, and update profile.

## Features

### Admin
- Dashboard with totals for students, lecturers, and units.
- Manage students (create, edit, delete with registration checks).
- Manage lecturers (create, edit, delete with unit-assignment checks).
- Manage units (code, name, lecturer assignment, semester assignment).
- Manage academic setup (academic years and semesters).
- View basic system reports.

### Lecturer
- Dashboard showing assigned units count.
- View own units.
- Mark student attendance by unit and date.
- Upload student marks (0-100) with automatic grade mapping (`A/B/C/D/F`).
- Attendance summary per unit.

### Student
- Dashboard with registered units count.
- Register units.
- View registered units.
- View personal attendance history.
- View published results and average score.
- Update profile name.

## Tech Stack

- PHP (procedural, MySQLi)
- MySQL / MariaDB
- Bootstrap 5 (CDN)
- Custom CSS/JS assets

## Project Structure

```text
Student-Management-Information-System/
├── admin/                  # Admin pages
├── lecturer/               # Lecturer pages
├── student/                # Student pages
├── auth/                   # Login, logout, admin bootstrap registration
├── config/
│   └── db.php              # Database connection
├── includes/               # Shared layout and auth guard
├── assets/
│   ├── css/style.css
│   └── js/script.js
└── index.php
```

## Prerequisites

- PHP 8.x (or compatible PHP 7.4+)
- MySQL/MariaDB
- Apache/Nginx (XAMPP/WAMP/LAMP works)

## Setup

1. Clone or copy this project into your web root.
2. Create a database named `sims`.
3. Update database credentials in `config/db.php` if needed:
   - `servername`
   - `username`
   - `password`
   - `dbname`
4. Create required tables (see schema section below).
5. Open `auth/register_admin.php` once to create your first admin account.
6. Open `auth/login.php` and sign in.

## Inferred Database Schema

No SQL migration file is currently included, so this schema is inferred from application code.

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','lecturer','student') NOT NULL
);

CREATE TABLE academic_years (
  id INT AUTO_INCREMENT PRIMARY KEY,
  year_name VARCHAR(30) NOT NULL
);

CREATE TABLE semesters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  semester_name VARCHAR(50) NOT NULL,
  academic_year_id INT NOT NULL,
  FOREIGN KEY (academic_year_id) REFERENCES academic_years(id)
    ON DELETE CASCADE
);

CREATE TABLE units (
  id INT AUTO_INCREMENT PRIMARY KEY,
  unit_code VARCHAR(20) NOT NULL,
  unit_name VARCHAR(120) NOT NULL,
  lecturer_id INT NULL,
  semester_id INT NULL,
  FOREIGN KEY (lecturer_id) REFERENCES users(id)
    ON DELETE SET NULL,
  FOREIGN KEY (semester_id) REFERENCES semesters(id)
    ON DELETE SET NULL
);

CREATE TABLE unit_registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  unit_id INT NOT NULL,
  FOREIGN KEY (student_id) REFERENCES users(id)
    ON DELETE CASCADE,
  FOREIGN KEY (unit_id) REFERENCES units(id)
    ON DELETE CASCADE,
  UNIQUE KEY uniq_student_unit (student_id, unit_id)
);

CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  unit_id INT NOT NULL,
  date DATE NOT NULL,
  status ENUM('Present','Absent') NOT NULL,
  FOREIGN KEY (student_id) REFERENCES users(id)
    ON DELETE CASCADE,
  FOREIGN KEY (unit_id) REFERENCES units(id)
    ON DELETE CASCADE
);

CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  unit_id INT NOT NULL,
  marks INT NOT NULL,
  grade VARCHAR(2) NOT NULL,
  published TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (student_id) REFERENCES users(id)
    ON DELETE CASCADE,
  FOREIGN KEY (unit_id) REFERENCES units(id)
    ON DELETE CASCADE,
  UNIQUE KEY uniq_student_unit_result (student_id, unit_id)
);
```

## Authentication and Roles

- Login is handled in `auth/login.php`.
- Sessions are enforced via `includes/auth_check.php`.
- Sidebar navigation is role-aware in `includes/sidebar.php`.

## Default Entry Points

- Login: `auth/login.php`
- First admin creation: `auth/register_admin.php`
- Admin dashboard: `admin/dashboard.php`
- Lecturer dashboard: `lecturer/dashboard.php`
- Student dashboard: `student/dashboard.php`

## Notes

- Passwords are securely stored with `password_hash` and verified via `password_verify`.
- Bootstrap is loaded via CDN.
- `config/db.php` currently defaults to local XAMPP/WAMP-style credentials.
