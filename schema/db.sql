

CREATE DATABASE task2;

USE task2;

-- Course table
CREATE TABLE Course (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  overview VARCHAR(255)
);

-- Highlight table
CREATE TABLE Highlight (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  highlight VARCHAR(255),
  FOREIGN KEY (course_id) REFERENCES Course(id)
);

-- CourseContent table
CREATE TABLE CourseContent (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  course_details VARCHAR(255),
  FOREIGN KEY (course_id) REFERENCES Course(id)
);

-- Module table
CREATE TABLE Module (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  Code VARCHAR(100),
  Title VARCHAR(255),
  Credits INT ,
  Status VARCHAR(100),
  FOREIGN KEY (course_id) REFERENCES Course(id)
);

-- LearningCredit table
CREATE TABLE LearningCredit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  credit_scheme VARCHAR(255),
  FOREIGN KEY (course_id) REFERENCES Course(id)
);

-- EntryRequirement table
CREATE TABLE EntryRequirement (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  requirement VARCHAR(255),
  FOREIGN KEY (course_id) REFERENCES Course(id)
);

-- Fee table
CREATE TABLE Fee (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  fee VARCHAR(255),
  FOREIGN KEY (course_id) REFERENCES Course(id)
);

-- FAQ table
CREATE TABLE FAQ (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  question VARCHAR(255),
  answer VARCHAR(255),
  FOREIGN KEY (course_id) REFERENCES Course(id)
);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(100),
    password VARCHAR(255)
)
