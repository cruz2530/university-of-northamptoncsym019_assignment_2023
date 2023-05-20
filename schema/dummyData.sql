-- Inserting dummy data into Course table
INSERT INTO Course (title, overview) VALUES
('Course 1', 'Overview of Course 1'),
('Course 2', 'Overview of Course 2');

-- Inserting dummy data into Highlight table
INSERT INTO Highlight (course_id, highlight) VALUES
(1, 'Highlight 1 for Course 1'),
(1, 'Highlight 2 for Course 1'),
(2, 'Highlight 1 for Course 2');

-- Inserting dummy data into CourseContent table
INSERT INTO CourseContent (course_id, course_details) VALUES
(1, 'Course details for Course 1'),
(2, 'Course details for Course 2');

-- Inserting dummy data into Module table
INSERT INTO Module (course_id, module_name) VALUES
(1, 'Module 1 for Course 1'),
(1, 'Module 2 for Course 1'),
(2, 'Module 1 for Course 2');

-- Inserting dummy data into LearningCredit table
INSERT INTO LearningCredit (course_id, credit_scheme) VALUES
(1, 'Learning Credit Scheme for Course 1'),
(2, 'Learning Credit Scheme for Course 2');

-- Inserting dummy data into EntryRequirement table
INSERT INTO EntryRequirement (course_id, requirement) VALUES
(1, 'Entry Requirement for Course 1'),
(2, 'Entry Requirement for Course 2');

-- Inserting dummy data into Fee table
INSERT INTO Fee ( course_id, fee) VALUES
(1, 'Fee for Course 1'),
(2, 'Fee for Course 2');

-- Inserting dummy data into FAQ table
INSERT INTO FAQ (course_id, question, answer) VALUES
(1, 'FAQ 1 for Course 1', 'Answer to FAQ 1'),
(1, 'FAQ 2 for Course 1', 'Answer to FAQ 2'),
(2, 'FAQ 1 for Course 2', 'Answer to FAQ 1');

-- Inserting dummy data into users
INSERT INTO users( username, password ) VALUES 
('admin', 'admin') , 
('richard', 'richard');
