<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Project Overview
نظام إدارة مدرسة مبني بـ Laravel لإدارة البيانات الأساسية للمدرسة مثل الطلاب والمعلمين وأولياء الأمور والفصول والرسوم، مع صلاحيات ولوحات حسب نوع المستخدم.

A Laravel-based school management system for managing core school data with role-based access and dashboards.

## Requirements
- PHP 8.*
- Composer
- MySQL 8+ (or compatible)
- Git

## Installation
1. Clone the repository.
2. Install backend dependencies:
```bash
composer install
```
3. Create environment file:
```bash
cp .env.example .env
```
4. Generate app key:
```bash
php artisan key:generate
```
5. Configure database credentials in `.env`.

## Database Setup and Seeding
Run:
```bash
php artisan migrate:fresh --seed
```

This will create a clean database with demo data including:
- Admin users
- Teachers
- Students
- Guardians
- Grades, classrooms, sections
- Tuition fees

## Run the Project
Start the backend server:
```bash
php artisan serve
```

## Demo Accounts (4 Guards)
Use these credentials after seeding:

- Admin
  - Email: `admin@school.test`
  - Password: `12345678`

- Teacher
  - Email: `teacher@mail.com`
  - Password: `12345678`

- Student
  - Email: `student@mail.com`
  - Password: `12345678`

- Guardian
  - Email: `guardian@mail.com`
  - Password: `12345678`
