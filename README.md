# Hikmah College: online tryout system

This folder is an **overlay**: it holds only the files that are new or replaced. Copy it over a fresh Laravel 12 project.

## Setup

1. Create the project (choose "none" for the starter kit, then MySQL or PostgreSQL):

   ```bash
   composer create-project laravel/laravel tryoutku
   ```

2. Copy this overlay into it. Overwrites `bootstrap/app.php`, `config/auth.php`, `routes/web.php`, `resources/css/app.css`, `resources/js/app.js`:

   ```bash
   cp -R path/to/this/folder/* tryoutku/
   cd tryoutku
   ```

3. Install the packages:

   ```bash
   composer require maatwebsite/excel
   npm install alpinejs chart.js lucide
   ```

4. Edit `.env`:

   ```
   APP_TIMEZONE=Asia/Jakarta
   DB_CONNECTION=mysql        # or pgsql
   DB_DATABASE=tryoutku
   DB_USERNAME=...
   DB_PASSWORD=...
   MAIL_MAILER=log            # registration mails land in storage/logs/laravel.log while developing
   ```

5. Create the tables and sample data:

   ```bash
   php artisan migrate --seed
   ```

6. Run it:

   ```bash
   composer run dev           # or: php artisan serve + npm run dev
   ```

Student area: `/login` and `/dashboard`  |  Admin area: `/admin` (admin@tryoutku.test / password, **change it**).

## What is where

| Area | Files |
|---|---|
| Routes | `routes/web.php` |
| Guards (student and admin) | `config/auth.php`, `bootstrap/app.php` |
| Schema | `database/migrations/*` |
| Exam engine | `app/Http/Controllers/Student/TryoutController.php`, `TryoutSession.php`, `resources/js/components.js` (`exam`) |
| Admin | `app/Http/Controllers/Admin/*`, `resources/views/admin/*` |
| Sample subjects and questions | `database/seeders/SubjectSeeder.php` |

## Current flows

- Registration collects student and parent details, a user-created password, and a Free or Paid (Rp 29.000) package choice.
- Free registrations must upload exactly five WhatsApp-sharing screenshots; Paid registrations must upload one transfer screenshot. Files are stored on Laravel's private local disk and only admins can view them.
- Every new account starts as `pending`. Admins review proofs under `/admin/students/pending` and approve or reject the registration. Only approved accounts can log in.
- Students sign in with their generated Student ID or parent email and their registration password. Approved students land on the dashboard and start a tryout directly from a subject card; no exam token is used.
- Admins manage subject-linked questions from `/admin/questions` and view registration counts from `/admin`.
- The server stores each tryout's start time and calculates its deadline. Answers are saved on each change, and answer keys are only shown on the result page.
- `students.proof_files` stores private image paths as JSON. `questions.subject_id` links each question to its subject. The legacy `tokens` table is dropped by the migration.
