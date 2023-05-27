<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/task2/php/config/db.php');
    // require_once('../config/db.php');

    class Course {

        private $conn;

        public function __construct()
        {
            $this->conn = Database::getConnection();
        }

        public function index(){
            $query = "
                SELECT c.id, c.title, c.overview, GROUP_CONCAT(DISTINCT h.highlight SEPARATOR ', ') AS highlights, GROUP_CONCAT(DISTINCT cc.course_details SEPARATOR ', ') AS contents
                FROM Course AS c
                LEFT JOIN Highlight AS h ON c.id = h.course_id
                LEFT JOIN CourseContent AS cc ON c.id = cc.course_id
                GROUP BY c.id ORDER BY c.title ASC
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        public function selectedCourse($courseIds){
            $courseIds = implode(',', $courseIds);

            $query = "
              SELECT
                c.id,
                c.title,
                c.overview,
                GROUP_CONCAT(DISTINCT h.highlight SEPARATOR ', ') AS highlights,
                GROUP_CONCAT(DISTINCT cc.course_details SEPARATOR ', ') AS contents,
                CONCAT('[', GROUP_CONCAT(DISTINCT CONCAT('{\"id\":', m.id, ', \"code\":\"', m.Code, '\", \"title\":\"', m.Title, '\", \"credits\":\"', m.Credits, '\", \"status\":\"', m.Status, '\"}') ORDER BY m.id SEPARATOR ', '), ']') AS modules

              FROM
                Course AS c
              LEFT JOIN
                Highlight AS h ON c.id = h.course_id
              LEFT JOIN
                CourseContent AS cc ON c.id = cc.course_id
              LEFT JOIN
                Module AS m ON c.id = m.course_id
              WHERE
                c.id IN ($courseIds)
              GROUP BY
                c.id, c.title, c.overview
              ORDER BY
                c.title ASC
            ";
        
            // Execute the query and fetch the results from the database
            $stmt =  $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        public function viewCourse($courseId){

            $query = "
                SELECT
                    c.id,
                    c.title,
                    c.overview,
                    CONCAT('[', GROUP_CONCAT(DISTINCT CONCAT('{\"id\":', h.id, ', \"highlight\":\"', h.highlight, '\"}') ORDER BY h.id SEPARATOR ', '), ']') AS highlights,
                    CONCAT('[', GROUP_CONCAT(DISTINCT CONCAT('{\"id\":', cc.id, ', \"course_details\":\"', cc.course_details, '\"}') ORDER BY cc.id SEPARATOR ', '), ']') AS contents,
                    CONCAT('[', GROUP_CONCAT(DISTINCT CONCAT('{\"id\":', m.id, ', \"code\":\"', m.Code, '\", \"title\":\"', m.Title, '\", \"credits\":\"', m.Credits, '\", \"status\":\"', m.Status, '\"}') ORDER BY m.id SEPARATOR ', '), ']') AS modules,
                    CONCAT('[', GROUP_CONCAT(DISTINCT CONCAT('{\"id\":', faq.id, ', \"question\":\"', faq.question, '\",\"answer\":\"', faq.answer, '\"}') ORDER BY faq.id SEPARATOR ', '), ']') AS faqs,
                    lc.credit_scheme,
                    er.requirement,
                    f.fee
                FROM
                    Course AS c
                LEFT JOIN
                    Highlight AS h ON c.id = h.course_id
                LEFT JOIN
                    CourseContent AS cc ON c.id = cc.course_id
                LEFT JOIN
                    Module AS m ON c.id = m.course_id
                LEFT JOIN
                    LearningCredit AS lc ON c.id = lc.course_id
                LEFT JOIN
                    EntryRequirement AS er ON c.id = er.course_id
                LEFT JOIN
                    Fee AS f ON c.id = f.course_id
                LEFT JOIN
                    FAQ AS faq ON c.id = faq.course_id
                WHERE
                    c.id = :courseId
                GROUP BY
                    c.id, lc.credit_scheme, er.requirement, f.fee
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":courseId", $courseId);
            $stmt->execute();

            $data =  $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data[0];
        }

        public function addCourse($data){

            try {

                // Begin a transaction
                $this->conn->beginTransaction();

                // Prepare and execute the course insert statement
                $stmt = $this->conn->prepare("INSERT INTO Course (title, overview) VALUES (:title, :overview)");
                $stmt->bindParam(":title", $data['title']);
                $stmt->bindParam(":overview", $data['overview']);
                $stmt->execute();

                // Get the inserted course ID
                $courseId = $this->conn->lastInsertId();

                // Insert highlights
                if (isset($data['highlights'])) {
                    $highlights = $data['highlights'];

                    foreach ($highlights as $highlight) {
                        // Prepare and execute the highlight insert statement
                        $stmt = $this->conn->prepare("INSERT INTO Highlight (course_id, highlight) VALUES (:courseId, :highlight)");
                        $stmt->bindParam(":courseId", $courseId,);
                        $stmt->bindParam(":highlight", $highlight);
                        $stmt->execute();
                    }
                }

                // Insert contents
                if (isset($data['contents'])) {
                    $contents = $data['contents'];

                    
                    foreach ($contents as $content) {
                        // Prepare and execute the content insert statement
                        $stmt = $this->conn->prepare("INSERT INTO CourseContent (course_id, course_details) VALUES (:courseId, :content)");
                        $stmt->bindParam(":courseId", $courseId);
                        $stmt->bindParam(":content", $content);
                        $stmt->execute();
                    }
                }

                // Insert modules
                if (isset($data['modules'])) {
                    $modules = $data['modules'];
                
                    foreach ($modules as $module) {
                        $code = $module['code'];
                        $title = $module['title'];
                        $credits = $module['credits'];
                        $status = $module['status'];


                        // Prepare and execute the module insert statement
                        $stmt = $this->conn->prepare("INSERT INTO Module (course_id, Code, Title, Credits, Status) VALUES (:courseId, :code, :title, :credits, :status)");
                        $stmt->bindParam(":courseId", $courseId);
                        $stmt->bindParam(":code", $code);
                        $stmt->bindParam(":title", $title);
                        $stmt->bindParam(":credits", $credits);
                        $stmt->bindParam(":status",  $status);

                        $stmt->execute();
                    }
                }

                
                // Insert learning credit
                if (isset($data['creditScheme'])) {
                    $creditScheme = $data['creditScheme'];

                    // Prepare and execute the learning credit insert statement
                    $stmt = $this->conn->prepare("INSERT INTO LearningCredit (course_id, credit_scheme) VALUES (:courseId, :creditScheme)");
                    $stmt->bindParam(":courseId", $courseId);
                    $stmt->bindParam(":creditScheme", $creditScheme);
                    $stmt->execute();
                }

                // Insert entry requirement
                if (isset($data['requirement'])) {
                    $requirement = $data['requirement'];

                    // Prepare and execute the entry requirement insert statement
                    $stmt = $this->conn->prepare("INSERT INTO EntryRequirement (course_id, requirement) VALUES (:courseId, :requirement)");
                    $stmt->bindParam(":courseId", $courseId);
                    $stmt->bindParam(":requirement", $requirement);
                    $stmt->execute();
                }

                // Insert fee
                if (isset($data['fee'])) {
                    $fee = $data['fee'];

                    // Prepare and execute the fee insert statement
                    $stmt = $this->conn->prepare("INSERT INTO Fee (course_id, fee) VALUES (:courseId, :fee)");
                    $stmt->bindParam(":courseId", $courseId);
                    $stmt->bindParam(":fee", $fee);
                    $stmt->execute();
                }

                // Insert FAQs
                if (isset($data['faqs'])) {
                    $faqs = $data['faqs'];

                    foreach ($faqs as $faq) {
                        $question = $faq['question'];
                        $answer = $faq['answer'];


                        // Prepare and execute the FAQ insert statement
                        $stmt = $this->conn->prepare("INSERT INTO FAQ (course_id, question, answer) VALUES (:courseId, :question, :answer)");
                        $stmt->bindParam(":courseId", $courseId);
                        $stmt->bindParam(":question", $question);
                        $stmt->bindParam(":answer",  $answer);
                        $stmt->execute();
                    }
                }

                $this->conn->commit();
                return ['status' => true, 'message' => "Course added successfully."];
            } catch (Exception $e) {
                // Rollback the transaction on error
                $this->conn->rollBack();
                return ['status' => false, 'message' => $e->getMessage()];
            }

        }

        public function updateCourse($data) {
            try {
                // Begin a transaction
                $this->conn->beginTransaction();
        
                // Update the course details
                $courseId = $data['courseId']; // Replace with the actual course ID
                $title = $data['title'];
                $overview = $data['overview'];
        
                // Prepare and execute the course update statement
                $stmt = $this->conn->prepare("UPDATE Course SET title = :title, overview = :overview WHERE id = :courseId");
                $stmt->bindParam(":title", $title);
                $stmt->bindParam(":overview", $overview);
                $stmt->bindParam(":courseId", $courseId);
                $stmt->execute();
        
                // Update or insert the highlights
                if (isset($data['highlights'])) {
                    $highlights = $data['highlights'];
        
                    foreach ($highlights as $highlight) {
                        if (isset($highlight['id'])) {
                            // Update the existing highlight
                            $highlightId = $highlight['id'];
                            $highlightText = $highlight['highlight'];
        
                            // Prepare and execute the highlight update statement
                            $stmt = $this->conn->prepare("UPDATE Highlight SET highlight = :highlight WHERE id = :highlightId AND course_id = :courseId");
                            $stmt->bindParam(":highlight", $highlightText);
                            $stmt->bindParam(":highlightId", $highlightId);
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->execute();
                        } else {
                            // Insert a new highlight
                            $highlightText = $highlight['highlight'];
        
                            // Prepare and execute the highlight insert statement
                            $stmt = $this->conn->prepare("INSERT INTO Highlight (course_id, highlight) VALUES (:courseId, :highlight)");
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->bindParam(":highlight", $highlightText);
                            $stmt->execute();
                        }
                    }
                }
        
                // Update or insert the contents
                if (isset($data['contents'])) {
                    $contents = $data['contents'];
        
                    foreach ($contents as $content) {
                        if (isset($content['id'])) {
                            // Update the existing content
                            $contentId = $content['id'];
                            $contentText = $content['content'];
        
                            // Prepare and execute the content update statement
                            $stmt = $this->conn->prepare("UPDATE CourseContent SET course_details = :content WHERE id = :contentId AND course_id = :courseId");
                            $stmt->bindParam(":content", $contentText);
                            $stmt->bindParam(":contentId", $contentId);
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->execute();
                        } else {
                            // Insert a new content
                            $contentText = $content['content'];
        
                            // Prepare and execute the content insert statement
                            $stmt = $this->conn->prepare("INSERT INTO CourseContent (course_id, course_details) VALUES (:courseId, :content)");
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->bindParam(":content", $contentText);
                            $stmt->execute();
                        }
                    }
                }
        
                // Update or insert the modules
                if (isset($data['modules'])) {
                    $modules = $data['modules'];
        
                    foreach ($modules as $module) {
                        if (isset($module['id'])) {
                            // Update the existing module
                            $moduleId = $module['id'];
                            $code = $module['code'];
                            $title = $module['title'];
                            $credits = $module['credits'];
                            $status = $module['status'];
        
                            // Prepare and execute the module update statement
                            $stmt = $this->conn->prepare("UPDATE Module SET Code = :code, Title = :title, Credits = :credits, Status = :status WHERE id = :moduleId AND course_id = :courseId");
                            $stmt->bindParam(":code", $code);
                            $stmt->bindParam(":title", $title);
                            $stmt->bindParam(":credits", $credits);
                            $stmt->bindParam(":status", $status);
                            $stmt->bindParam(":moduleId", $moduleId);
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->execute();
                        } else {
                            // Insert a new module
                            $code = $module['code'];
                            $title = $module['title'];
                            $credits = $module['credits'];
                            $status = $module['status'];
        
                            // Prepare and execute the module insert statement
                            $stmt = $this->conn->prepare("INSERT INTO Module (course_id, Code, Title, Credits, Status) VALUES (:courseId, :code, :title, :credits, :status)");
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->bindParam(":code", $code);
                            $stmt->bindParam(":title", $title);
                            $stmt->bindParam(":credits", $credits);
                            $stmt->bindParam(":status", $status);
                            $stmt->execute();
                        }
                    }
                }

                // Update learning credit
                if (isset($data['creditScheme'])) {
                    $creditScheme = $data['creditScheme'];

                    // Prepare and execute the learning credit update statement
                    $stmt = $this->conn->prepare("UPDATE LearningCredit SET credit_scheme = :creditScheme WHERE course_id = :courseId");
                    $stmt->bindParam(":creditScheme", $creditScheme);
                    $stmt->bindParam(":courseId", $courseId);
                    $stmt->execute();
                }

                // Update entry requirement
                if (isset($data['requirement'])) {
                    $requirement = $data['requirement'];

                    // Prepare and execute the entry requirement update statement
                    $stmt = $this->conn->prepare("UPDATE EntryRequirement SET requirement = :requirement WHERE course_id = :courseId");
                    $stmt->bindParam(":requirement", $requirement);
                    $stmt->bindParam(":courseId", $courseId);
                    $stmt->execute();
                }

                // Update fee
                if (isset($data['fee'])) {
                    $fee = $data['fee'];

                    // Prepare and execute the fee update statement
                    $stmt = $this->conn->prepare("UPDATE Fee SET fee = :fee WHERE course_id = :courseId");
                    $stmt->bindParam(":fee", $fee);
                    $stmt->bindParam(":courseId", $courseId);
                    $stmt->execute();
                }


        
                // Update or insert the FAQs
                if (isset($data['faqs'])) {
                    $faqs = $data['faqs'];
        
                    foreach ($faqs as $faq) {
                        if (isset($faq['id'])) {
                            // Update the existing FAQ
                            $faqId = $faq['id'];
                            $question = $faq['question'];
                            $answer = $faq['answer'];
        
                            // Prepare and execute the FAQ update statement
                            $stmt = $this->conn->prepare("UPDATE FAQ SET question = :question, answer = :answer WHERE id = :faqId AND course_id = :courseId");
                            $stmt->bindParam(":question", $question);
                            $stmt->bindParam(":answer", $answer);
                            $stmt->bindParam(":faqId", $faqId);
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->execute();
                        } else {
                            // Insert a new FAQ
                            $question = $faq['question'];
                            $answer = $faq['answer'];
        
                            // Prepare and execute the FAQ insert statement
                            $stmt = $this->conn->prepare("INSERT INTO FAQ (course_id, question, answer) VALUES (:courseId, :question, :answer)");
                            $stmt->bindParam(":courseId", $courseId);
                            $stmt->bindParam(":question", $question);
                            $stmt->bindParam(":answer", $answer);
                            $stmt->execute();
                        }
                    }
                }
        
                // Commit the transaction
                $this->conn->commit();
                return ['status' => true, 'message' => "Course updated successfully."];
            } catch (Exception $e) {
                // Rollback the transaction on error
                $this->conn->rollBack();
                return ['status' => false, 'message' => $e->getMessage()];
            }
        }
        

        public function deleteCourse($courseId){
                // Prepare the delete statements with cascading constraints
                $deleteCourse = $this->conn->prepare("DELETE FROM Course WHERE id = :course_id");
                $deleteHighlights = $this->conn->prepare("DELETE FROM Highlight WHERE course_id = :course_id");
                $deleteCourseContent = $this->conn->prepare("DELETE FROM CourseContent WHERE course_id = :course_id");
                $deleteModules = $this->conn->prepare("DELETE FROM Module WHERE course_id = :course_id");
                $deleteLearningCredits = $this->conn->prepare("DELETE FROM LearningCredit WHERE course_id = :course_id");
                $deleteEntryRequirements = $this->conn->prepare("DELETE FROM EntryRequirement WHERE course_id = :course_id");
                $deleteFees = $this->conn->prepare("DELETE FROM Fee WHERE course_id = :course_id");
                $deleteFAQs = $this->conn->prepare("DELETE FROM FAQ WHERE course_id = :course_id");
            
                try {
                    // Begin a transaction
                    $this->conn->beginTransaction();
    
            
                    // Delete associated records with cascading constraints
                    $deleteHighlights->bindParam(":course_id", $courseId);
                    $deleteHighlights->execute();
            
                    $deleteCourseContent->bindParam(":course_id", $courseId);
                    $deleteCourseContent->execute();
            
                    $deleteModules->bindParam(":course_id", $courseId);
                    $deleteModules->execute();
            
                    $deleteLearningCredits->bindParam(":course_id", $courseId);
                    $deleteLearningCredits->execute();
            
                    $deleteEntryRequirements->bindParam(":course_id", $courseId);
                    $deleteEntryRequirements->execute();
            
                    $deleteFees->bindParam(":course_id", $courseId);
                    $deleteFees->execute();
            
                    $deleteFAQs->bindParam(":course_id", $courseId);
                    $deleteFAQs->execute();

                    // Delete the course
                    $deleteCourse->bindParam(":course_id", $courseId);
                    $deleteCourse->execute();
            
                    // Commit the transaction
                    $this->conn->commit();
            
                    return ['status' => true, 'message' => "Course and associated references deleted successfully."];
                    
                } catch (Exception $e) {
                    // Rollback the transaction on error
                    $this->conn->rollBack();
                    return ['status' => false, 'message' => $e->getMessage()];

                }
            
        }

        public function deleteHighlight($id){

            $deleteHighlight = $this->conn->prepare("DELETE FROM Highlight WHERE id = :id");
            $deleteHighlight->bindParam(":id", $id);
            $deleteHighlight->execute();
                        
        }

        public function deleteContent($id){
            $deleteContent = $this->conn->prepare("DELETE FROM CourseContent WHERE id = :id");
            $deleteContent->bindParam(":id", $id);
            $deleteContent->execute();
        }

        public function deleteModule($id){
            $deleteModule = $this->conn->prepare("DELETE FROM Module WHERE id = :id");
            $deleteModule->bindParam(":id", $id);
            $deleteModule->execute();
        }

        public function deleteFAQ($id){
            $deleteFAQ = $this->conn->prepare("DELETE FROM FAQ WHERE id = :id");
            $deleteFAQ->bindParam(":id", $id);
            $deleteFAQ->execute();
        }

        public function __destruct()
        {
            $this->conn = Database::releaseConnection($this->conn);
        }

    }
?>