<!DOCTYPE html>
<html>

    <?php
        include($_SERVER['DOCUMENT_ROOT'].'/task2/include/head.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/task2/php/course/courses.php');

        if(isset($_GET['course_id']) && $_GET['course_id'] !== ''){

            // Create an object of the Course class
            $course = new Course();

            $courseId = $_GET['course_id'];

            // Call the index method to retrieve the course data
            $course = $course->viewCourse($courseId);

            extract($course);
            $contentsArray = json_decode($contents, true);
            $highlightsArray = json_decode($highlights, true);
            $faqsArray = json_decode($faqs, true);
            $modulesArray = json_decode($modules, true);

        }else{
            exit(header('Location: ./'));
        }

    ?>

    <body>
    <div>
        <?php
            include($_SERVER['DOCUMENT_ROOT'].'/task2/include/header.php');
            
        ?>
        <main>
            <?php include($_SERVER['DOCUMENT_ROOT'].'/task2/include/nav.php');  ?>
            <div class="parentContainer">
                <h3 id="formTitle">EDIT <?php echo $title; ?> Course</h3>
                <form class="formParentContainer">


                    <div class="formFieldContainer">
                        <span class="formFields">
                            <h2>Title</h2>
                            <input class="inputFields" type="text" value='<?php echo $title  ?>' name="title" id="title" required><br>
                            <input hidden id="courseId" value="<?php echo $courseId ?>">
                        </span>
                    </div>

                    <div class="field-wrapper">
                        <h2>Overview</h2>
                        <div class="field">
                            <textarea name="overview" id="overview" required><?php echo $overview  ?></textarea><br>
                        </div>
                    </div>

                    <div class="field-wrapper">
                        <h2>Highlights</h2>
                        <?php
                            $highlightCount = 0;
                            foreach ($highlightsArray as $highlight) {
                                $highlightId = $highlight['id'];
                                $highlightText = $highlight['highlight'];
                                ?>
                                <div class="field">
                                    <input class="inputFields" type="text" name="highlights[]" id="highlight<?php echo $highlightId; ?>" value="<?php echo $highlightText; ?>" data-highlight-id="<?php echo $highlightId; ?>" required>
                                    <span hidden name="highlights"></span>
                                    <button class="addMore">+</button>
                                    <?php  if($highlightCount >= 1) { ?>
                                        <button class="remove" onclick="confirmAndRemoveHighlight(<?php echo $highlightId; ?>)">-</button>
                                    <?php } ?>
                                </div>
                                <?php ++$highlightCount;
                            }
                            ?>
                    </div>

                    <div class="field-wrapper">
                        <h2>Contents</h2>
                        <?php
                            $contnentCount = 0;
                            foreach ($contentsArray as $content) {
                                $contentId = $content['id'];
                                $contentDetails = $content['course_details'];
                                ?>
                                <div class="field">
                                    <input class="inputFields" type="text" name="contents[]" id="content<?php echo $contentId; ?>" value="<?php echo $contentDetails; ?>" data-content-id="<?php echo $contentId; ?>" required>
                                    <span name="contents"></span>
                                    <button class="addMore">+</button>
                                    <?php  if($contnentCount >= 1) { ?>
                                        <button class="remove" onclick="confirmAndRemoveContent(<?php echo $contentId; ?>)">-</button>
                                    <?php } ?>
                                </div>
                                <?php ++$contnentCount;
                            }
                            ?>
                    </div>
                    
                    <div class="field-wrapper">
                        <h2>Modules</h2>
                        <?php
                        $moduleCount =0;
                        foreach ($modulesArray as $module) {
                            $moduleId = $module['id'];
                            $moduleCode = $module['code'];
                            $moduleTitle = $module['title'];
                            $moduleCredits = $module['credits'];
                            $moduleStatus = $module['status'];
                            ?>
                            <div class="field1" data-module-id="<?php echo $moduleId; ?>">
                                <input class="inputFields2" type="text" name="modules[0][code]" id="module1" value="<?php echo $moduleCode; ?>" placeholder="Code" required>
                                <input class="inputFields2" type="text" name="modules[0][title]" id="module2" value="<?php echo $moduleTitle; ?>" placeholder="Title" required>
                                <input class="inputFields2" type="text" name="modules[0][credits]" id="module3" value="<?php echo $moduleCredits; ?>" placeholder="Credits" required>
                                <select class="inputFields2" name="modules[0][status]" aria-placeholder="status" required>
                                    <option value="optional" <?php if ($moduleStatus === 'optional') echo 'selected'; ?>>Optional</option>
                                    <option value="designated" <?php if ($moduleStatus === 'designated') echo 'selected'; ?>>Designated</option>
                                </select>
                                <span name="modules"></span>
                                <button class="addMore">+</button>
                                <?php  if($moduleCount >= 1) { ?>
                                        <button class="remove" onclick="confirmAndRemoveModule(<?php echo $moduleId; ?>)">-</button>
                                    <?php } ?>
                            </div>
                            <?php ++$moduleCount;
                        }
                        ?>
                    </div>

                    <div class="formFields">
                        <h2>Enhanced Learning Credit Scheme</h2>
                        <label id="fieldLabels" for="credit_scheme">Credit Scheme:</label>
                        <input class="inputFields" type="text" name="credit_scheme" value='<?php echo $credit_scheme; ?>' id="credit_scheme" required><br>
                    </div>

                    <div class="formFields">
                        <h2>Entry Requirement</h2>
                        <label id="fieldLabels" for="requirement">Requirement:</label>
                        <input class="inputFields" type="text" name="requirement" id="requirement" value='<?php echo $requirement ?>' required><br>
                    </div>
                
                    <div class="formFields">
                        <h2>Fees and Funding</h2>
                        <label id="fieldLabels" for="fee">Fee:</label>
                        <input class="inputFields" type="text" name="fee" id="fee" value='<?php echo $fee ?>' required><br>
                    </div>

                    <div class="field-wrapper">
                        <h2>FAQs</h2>
                        <?php
                        $faqCount = 0;
                        $faqsArray = json_decode($faqs, true);
                        foreach ($faqsArray as $faq) {
                            $faqId = $faq['id'];
                            $faqQuestion = $faq['question'];
                            $faqAnswer = $faq['answer'];
                            ?>
                            <div class="field2" data-faq-id="<?php echo $faqId; ?>">
                                <div class="faqFieldContainer">
                                    <span class="formFields" name='faq'>
                                        <label id="fieldLabels" for="question1" name="question">Question:</label>
                                        <input class="inputFields" type="text" name="faqs[0][question]" id="question1" value="<?php echo $faqQuestion; ?>">
                                    </span>
                                    <span class="formFields">
                                        <label id="fieldLabels" for="answer1" name="answer" id="answer1" question>Answer:</label>
                                        <input class="inputFields" type="text" name="faqs[0][answer]" id="answer1" value="<?php echo $faqAnswer; ?>">
                                    </span>
                                    <input type="hidden" name="faq_ids[]" value="<?php echo $faqId; ?>">
                                    <button class="addMore">+</button>
                                    <?php  if($faqCount >= 1) { ?>
                                        <button class="remove" onclick="confirmAndRemoveFAQ(<?php echo $faqId; ?>)">-</button>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php ++$faqCount;
                        }
                        ?>
                    </div>

                    <button class="updateCourse"> Edit Course</button>

                </form>
            </div>
        </main>
    </div>

    <footer>&copy; CSYM019 2023</footer>
    <script src="../assets/js/course.js"></script>
</body>

</html> 
