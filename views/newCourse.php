<!DOCTYPE html>
<html>

    <?php
        include($_SERVER['DOCUMENT_ROOT'].'/task2/include/head.php')
    ?>

<body>
    <div>
        <?php
            include($_SERVER['DOCUMENT_ROOT'].'/task2/include/header.php');
            
        ?>
        <main>
            <?php include($_SERVER['DOCUMENT_ROOT'].'/task2/include/nav.php');  ?>
            <div class="parentContainer">
                <h3 id="formTitle">Sample New Course Entery Form</h3>
                <form method="POST" class="formParentContainer">
                    <div class="formFieldContainer">
                        <span class="formFields">
                            <h2>Title</h2>
                            <input class="inputFields" type="text" name="title" id="title" required><br>
                        </span>
                    </div>

                    <div class="field-wrapper">
                        <h2>Overview</h2>
                        <div class="field">
                            <textarea name="overview" id="overview" required></textarea><br>
                        </div>
                    </div>

                    <div class="field-wrapper">
                        <h2>Highlights</h2>
                        <div class="field">
                            <input class="inputFields" type="text" name="highlights[]" id="highlight1" required>
                            <span hidden name="highlights"></span>
                            <button class="addMore">+</button>
                        </div>
                    </div>

                    <div class="field-wrapper">
                        <h2>Contents</h2>
                        <div class="field">
                            <input class="inputFields" type="text" name="contents[]" id="content1" required>
                            <span name="contents"></span>
                            <button class="addMore">+</button>
                        </div>
                    </div>

                    <div class="field-wrapper">
                        <h2>Modules</h2>
                        <div class="field1">
                            
                            <input class="inputFields2" type="text" name="modules[0][code]" id="module1" placeholder="Code" required>
                            <input class="inputFields2" type="text" name="modules[0][title]" id="module2" placeholder="Title" required>
                            <input class="inputFields2" type="text" name="modules[0][credits]" id="module3" placeholder="Credits" required>
                            <select class="inputFields2" name="modules[0][status]" aria-placeholder="status" required>
                                <option value="optional">Optional</option>
                                <option value="designated">Designated</option>
                            </select>
                            <span name="modules"></span>
                            <button class="addMore">+</button>
                        </div>
                    </div>
                    <div class="formFields">
                        <h2>Enhanced Learning Credit Scheme</h2>
                        <label id="fieldLabels" for="credit_scheme">Credit Scheme:</label>
                        <input class="inputFields" type="text" name="credit_scheme" id="credit_scheme" required><br>
                    </div>
                    <div class="formFields">
                        <h2>Entry Requirement</h2>
                        <label id="fieldLabels" for="requirement">Requirement:</label>
                        <input class="inputFields" type="text" name="requirement" id="requirement" required><br>
                    </div>
                    <div class="formFields">
                        <h2>Fees and Funding</h2>
                        <label id="fieldLabels" for="fee">Fee:</label>
                        <input class="inputFields" type="text" name="fee" id="fee" required><br>
                    </div>

                    <div class="field-wrapper">
                        <h2>FAQs</h2>
                        <div class="field2">
                            <div class="faqFieldContainer">
                                <span class="formFields" name='faq'>
                                    <label id="fieldLabels" for="question1" name="question">Question:</label>
                                    <input class="inputFields" type="text" name="faqs[0][question]" id="question1">
                                </span>
                                <span class="formFields">
                                    <label id="fieldLabels" for="answer1" name="answer" id="answer1"
                                        question>Answer:</label>
                                    <input class="inputFields" type="text" name="faqs[0][answer]" id="answer1">
                                </span>
                                <button class="addMore">+</button>
                            </div>
                        </div>
                    </div>
                    <button class="addCourse"> Add Course</button>
                </form>
            </div>
        </main>
    </div>

    <footer>&copy; CSYM019 2023</footer>
    <script src="../assets/js/course.js"></script>
</body>

</html> 