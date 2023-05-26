<!DOCTYPE html>
<html>
    <?php

        include($_SERVER['DOCUMENT_ROOT'].'/task2/include/head.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/task2/php/course/courses.php');

        if(isset($_GET['course_ids']) && $_GET['course_ids'] !== ''){

            // Create an object of the Course class
            $course = new Course();

            $selectedCourseIds = explode(',', $_GET['course_ids']);

            // Call the index method to retrieve the course data
            $report = $course->selectedCourse($selectedCourseIds);

            // Convert the module data to JavaScript format
            $moduleData = [];
            foreach ($report as $course) {
                $modules = json_decode($course->modules);
                foreach ($modules as $module) {
                    $moduleData[] = [
                        'id' => $module->id,
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'code' => $module->code,
                        'title' => $module->title,
                        'credits' => $module->credits,
                        'status' => $module->status
                    ];
                }
            }

            // Convert the module data to JSON format
            $moduleDataJson = json_encode($moduleData);

        }else{
            exit(header('Location: ../'));
        }

    ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <body>
        <?php
            include($_SERVER['DOCUMENT_ROOT'].'/task2/include/header.php');
        ?>
        <main>
            <?php include($_SERVER['DOCUMENT_ROOT'].'/task2/include/nav.php');  ?>

            <div id="chartsContainer"></div>

            <script>
                

                var moduleData = <?php echo $moduleDataJson; ?>;

                var courses = [...new Set(moduleData.map(module => module.course_id))];


                var moduleCredits = {};
                courses.forEach(course => {
                    moduleCredits[course] = {};
                });


                moduleData.forEach(module => {
                    moduleCredits[module.course_id][module.title] = module.credits;
                });

                var chartsContainer = document.getElementById('chartsContainer');


                courses.forEach(course => {
                    var courseContainer = document.createElement('div');
                    courseContainer.classList.add('chartContainer');

                    var title = document.createElement('h2');
                    title.textContent = moduleData.find(module => module.course_id === course).course_title + ' Information';

                    var table = document.createElement('table');
                    var thead = document.createElement('thead');
                    var tbody = document.createElement('tbody');

                    var tableHeaderRow = document.createElement('tr');
                    var tableHeaders = ['Module Title', 'Code', 'Credits', 'Status'];

                    tableHeaders.forEach(headerText => {
                        var th = document.createElement('th');
                        th.textContent = headerText;
                        tableHeaderRow.appendChild(th);
                    });

                    thead.appendChild(tableHeaderRow);
                    table.appendChild(thead);

                    moduleData.forEach(module => {
                        if (module.course_id === course) {
                            var row = document.createElement('tr');
                            var moduleData = [module.title, module.code, module.credits, module.status];

                            moduleData.forEach(data => {
                                var td = document.createElement('td');
                                td.textContent = data;
                                row.appendChild(td);
                            });

                            tbody.appendChild(row);
                        }
                    });

                    table.appendChild(tbody);

                    var pieContainer = document.createElement('div');
                    pieContainer.classList.add('pieContainer')


                    var coursesContainer = document.createElement('div')
                    coursesContainer.classList.add('coursesContainer')


                    var canvas = document.createElement('canvas');
                    canvas.id = 'pieChart' + course;
                    canvas.classList.add('canvasContainer');

                    courseContainer.appendChild(title);
                    courseContainer.appendChild(table);

                    pieContainer.appendChild(canvas);

                    coursesContainer.appendChild(courseContainer);
                    coursesContainer.appendChild(pieContainer)

                    chartsContainer.append(coursesContainer)

                    var ctxPie = document.getElementById('pieChart' + course).getContext('2d');
                    var pieChart = new Chart(ctxPie, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(moduleCredits[course]),
                            datasets: [{
                                data: Object.values(moduleCredits[course]),
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966CC', '#FF8C00']
                            }]
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Module Credits Distribution (' + moduleData.find(module => module.course_id === course).course_title + ')'
                            }
                        }
                    });
                });

                if (courses.length > 1) {
                    var chartColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966CC', '#FF8C00'];
                    var chartData = [];

                    courses.forEach((courseId, index) => {
                        var data = Object.keys(moduleCredits[courseId]).map((moduleTitle, i) => ({
                        label: moduleTitle,
                        data: courses.map(course => moduleCredits[course][moduleTitle]),
                        backgroundColor: chartColors[(index + i) % chartColors.length] // Rotate colors for each bar
                        }));

                        chartData.push(...data.filter(Boolean));
                    });


                    var barChartContainer = document.createElement('div');
                    barChartContainer.classList.add('chartContainer');

                    var canvas = document.createElement('canvas');
                    canvas.id = 'barChart';

                    var title = document.createElement('h2');
                    title.textContent = 'Courses Module Credits Comparison';

                    barChartContainer.appendChild(title);
                    barChartContainer.appendChild(canvas);
                    chartsContainer.appendChild(barChartContainer);

                    var ctxBar = document.getElementById('barChart').getContext('2d');
                    var barChart = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                        labels: courses.map(course => moduleData.find(module => module.course_id === course).course_title),
                        datasets: chartData
                        },
                        options: {
                        responsive: true,
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Courses Module Credits Comparison'
                        }
                        }
                    });
                }
            </script>
        </main>
        <footer style="position:sticky;bottom:0px; width:100%">&copy; CSYM019 2023</footer>
    </body>
</html>