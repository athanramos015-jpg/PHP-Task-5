# PHP Basics - Hands-on Exercises (IT/IS WEB)

Requirements: XAMPP (or any Apache + PHP 7.4+ + MySQL/MariaDB stack).

## Setup
1. Copy this whole folder into `C:\xampp\htdocs\` (for example `C:\xampp\htdocs\php_exercises`).
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open phpMyAdmin (http://localhost/phpmyadmin) > **Import** > choose `database/php_exercises.sql` > Go.
   This creates the `php_exercises` database with the `registered_persons` and `faculty` tables.
4. If your MySQL user/password is not `root` / (empty), edit `output3_4/config.php`
   and `output5/config/database.php`.

## Open each output
| Output | URL |
|---|---|
| #1 Form + validation + CSS | http://localhost/php_exercises/output1/ |
| #2 Home, Register, Login, Forgot Password (include/require) | http://localhost/php_exercises/output2/ |
| #3 and #4 Database form + list of registered persons | http://localhost/php_exercises/output3_4/ |
| #5 Faculty CRUD (MVC) | http://localhost/php_exercises/output5/ |

## What each output covers
- **#1** Fields: first name, last name, age, gender, email, address, contact number.
  HTML5 input types (number, radio, email, tel, textarea) plus server-side validation in `includes/validation.php`.
- **#2** `index.php` builds every page with `require` (functions, header, nav, page file, footer).
  Register/Login/Forgot use PHP sessions (no database), passwords are hashed with `password_hash`.
- **#3 and #4** Same fields as #1, saved to MySQL with PDO prepared statements.
  The list of registered persons appears below the form and has search, pagination, edit and delete.
  Email is unique; duplicates show an error.
- **#5** MVC layout:
  `index.php` (front controller) > `app/controllers/FacultyController.php` >
  `app/models/Faculty.php` > `app/views/...`.
  Table `faculty` has `id` as AUTO_INCREMENT PRIMARY KEY.
  Fields: first, middle (optional), last name, age, gender, address, position, salary.

## Before you submit (deliverables)
1. Screen recording while developing.
2. Screenshots of every output (including validation errors and the list).
3. The project folders.
4. Export your own database: phpMyAdmin > `php_exercises` > Export > SQL > save as `.sql`
   (do this after adding some records so your data is included).
Put them in one Google Drive folder, set access to "Anyone with the link", and submit the link only.
