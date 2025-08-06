📚 Quiz Application (PHP + MySQL)
This is a simple and secure Quiz Application built with PHP, MySQL, and JavaScript. Authenticated users can take quizzes, submit answers, and view their scores. Admins or content creators can manage quiz content via the database.

🧠 Features
User authentication system (login/logout)

Quiz listing and selection

Dynamic quiz loading via AJAX (fetch_quiz.php)

Real-time answer selection using JavaScript

Score calculation and display

Input validation and error handling

Clean UI with modular code structure

📂 Project Structure
bash
Copy
Edit
/quiz-app
│
├── index.php                # Home or quiz list
├── take_quiz.php           # Main quiz-taking interface
├── fetch_quiz.php          # AJAX handler to get quiz questions
├── submit_quiz.php         # Handles quiz submission and scoring
│
├── src/
│   ├── auth.php            # Authentication functions
│   ├── db.php              # PDO database connection
│
├── css/
│   └── quiz.css            # Optional: external styling
│
└── quizzes.sql             # Sample SQL for quiz & questions table


🛠️ Requirements
PHP 7.4 or later

MySQL or MariaDB

Apache or Nginx (XAMPP/LAMP recommended)

Modern web browser

🗃️ Database Setup
Create the database:

sql
Copy
Edit
CREATE DATABASE quiz_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
Use the provided tables (example schema):

sql
Copy
Edit
USE quiz_app;

CREATE TABLE quizzes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT
);

CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  question_text TEXT NOT NULL,
  option_a TEXT,
  option_b TEXT,
  option_c TEXT,
  option_d TEXT,
  correct_option CHAR(1) NOT NULL,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);
Insert sample quiz and questions (optional).

🔐 Authentication
Make sure to include a login system before take_quiz.php is accessed. Example in auth.php:

php
Copy
Edit
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
🚀 How to Run
Clone or download the project.

Configure your db.php with database credentials:

php
Copy
Edit
$pdo = new PDO("mysql:host=localhost;dbname=quiz_app", "root", "");
Serve the app using Apache/Nginx or open http://localhost/quiz-app/take_quiz.php?quiz_id=1 in your browser.
