<!DOCTYPE html>
<html>
    <?php
        include( $_SERVER['DOCUMENT_ROOT'].'/task2/include/head.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/task2/php/course/courses.php');

        // Create an object of the Course class
        $course = new Course();

        // Call the index method to retrieve the course data
        $courses = $course->index();
    ?>
    <body style="display:flex;flex-direction:column;align-items:space-between">
        <?php
            include($_SERVER['DOCUMENT_ROOT'].'/task2/include/header.php');
        ?>
        <main>
            <div class="courseList">
                <?php include($_SERVER['DOCUMENT_ROOT'].'/task2/include/nav.php');  ?>
            </div>
            <div class='mainContainer'>
                <h3>Sample Course Selection Form</h3>
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="row2"></th>
                            <th>Title</th>
                            <th>Overview</th>
                            <th>Highlights</th>
                            <th>Contents</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><input type="checkbox" class="rowCheckbox" name="row1" value="<?php echo $course->id?>"></td>
                                <td><?php echo $course->title; ?></td>
                                <td><?php echo $course->overview; ?></td>
                                <td>
                                    <ul>
                                        <?php
                                        $highlights = explode(', ', $course->highlights);
                                        foreach ($highlights as $highlight):
                                        ?>
                                            <li><?php echo $highlight; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
                                        $contents = explode(', ', $course->contents);
                                        foreach ($contents as $content):
                                        ?>
                                            <li><?php echo $content; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td>
                                    <a href="/task2/views/editCourse.php?course_id=<?php echo $course->id; ?>" title="Edit" class="action-button">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" data-course-id="<?php echo $course->id; ?>" title="Delete" class="action-button deleteCourse">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <form  class="addmore" id="createReportForm">
                    <p class="blueNote">You can click of the button below to view a sketch of the report you are expected to develop.</p>
                    <input type="submit" value="Create Course Report" />
                    <br><br>
                    <!--input type="reset" value="Cancel" /-->                
                </form>
            </div>
        </main>
        <footer>&copy; CSYM019 2023</footer>
        <script src='./assets/js/course.js'></script>
        <script>
            document.getElementById('createReportForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const checkboxes = document.querySelectorAll('.rowCheckbox:checked');
                const selectedIds = Array.from(checkboxes).map(checkbox => checkbox.value);
                
                // Redirect to reportChart.php with the selected course IDs as a query parameter
                const redirectUrl = `./views/reportChart.php?course_ids=${selectedIds.join(',')}`;
                window.location.href = redirectUrl;
            });
        </script>
    </body>
</html>