-- PHP Exercises database (Outputs #3, #4 and #5)
-- Import this file in phpMyAdmin (Import tab) or run: mysql -u root < php_exercises.sql

CREATE DATABASE IF NOT EXISTS php_exercises
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_exercises;

-- Outputs #3 and #4: registered persons
CREATE TABLE IF NOT EXISTS registered_persons (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name     VARCHAR(50)  NOT NULL,
  last_name      VARCHAR(50)  NOT NULL,
  age            TINYINT UNSIGNED NOT NULL,
  gender         ENUM('Male','Female','Other') NOT NULL,
  email          VARCHAR(100) NOT NULL,
  address        VARCHAR(255) NOT NULL,
  contact_number VARCHAR(13)  NOT NULL,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_registered_persons_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Output #5: faculty
CREATE TABLE IF NOT EXISTS faculty (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name  VARCHAR(50)  NOT NULL,
  middle_name VARCHAR(50)  NULL DEFAULT NULL,
  last_name   VARCHAR(50)  NOT NULL,
  age         TINYINT UNSIGNED NOT NULL,
  gender      ENUM('Male','Female','Other') NOT NULL,
  address     VARCHAR(255) NOT NULL,
  position    VARCHAR(100) NOT NULL,
  salary      DECIMAL(10,2) NOT NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
