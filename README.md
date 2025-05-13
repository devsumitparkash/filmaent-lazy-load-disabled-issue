# Order Management System

This is a Laravel-based Order Management System. Follow the steps below to set it up locally and reproduce the issue with the **Update Status** button.

## Requirements

- PHP 8.4
- Composer
- Node.js and NPM
- MySQL or compatible database

## Installation

1. Clone the repository

   git clone https://github.com/your-username/your-repo.git  
   cd your-repo

2. Install PHP dependencies

   composer install

3. Install Node dependencies and build frontend assets

   npm install  
   npm run build

4. Environment setup

   Copy the `.env.example` file and generate the app key:

   cp .env.example .env  
   php artisan key:generate

   Update `.env` with your database credentials.

5. Run migrations

   php artisan migrate

6. Seed the database

   php artisan db:seed

## Access the Admin Panel

Visit: http://localhost/admin/login

Login credentials:

Email: test@example.com  
Password: password

## Reproduce the Issue

1. Log in to the admin panel.
2. Go to the **Orders** resource.
3. Click **View** on any order.
4. Click the **Update Status** button.
5. The issue will appear.

---
