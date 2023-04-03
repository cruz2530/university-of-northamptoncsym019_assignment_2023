const fs = require('fs');

// Read the data from the file
const coursesData = JSON.parse(fs.readFileSync('course.json'));

// Define the HTML table
let tableHtml = '<table>';
tableHtml += '<tr><th>Course Name</th><th>School</th><th>Level</th><th>Duration</th><th>Starting</th><th>Entry Requirements</th><th>UK Full Time Fees</th><th>International Full Time Fees</th></tr>';

// Loop through the courses data and add them to the table
for (const course of coursesData.courses) {
  tableHtml += '<tr>';
  tableHtml += `<td>${course.course_name}</td>`;
  tableHtml += `<td>${course.school}</td>`;
  tableHtml += `<td>${course.level}</td>`;
  tableHtml += `<td>${course.duration}</td>`;
  tableHtml += `<td>${course.Starting}</td>`;
  tableHtml += `<td>${course.entry_requirements.join('<br>')}</td>`;
  tableHtml += `<td>${course.fees.UK_FULL_TIME.annual_fee}</td>`;
  tableHtml += `<td>${course.fees.international_FULL_TIME.annual_fee}</td>`;
  tableHtml += '</tr>';
}
tableHtml += '</table>';

// Write the HTML table to the file
fs.writeFileSync('course.html', tableHtml);

// Update the data every 5 seconds
setInterval(() => {
  // Read the data from the file
  const coursesData = JSON.parse(fs.readFileSync('course.json'));

  // Define the HTML table
  let tableHtml = '<table>';
  tableHtml += '<tr><th>Course Name</th><th>School</th><th>Level</th><th>Duration</th><th>Starting</th><th>Entry Requirements</th><th>UK Full Time Fees</th><th>International Full Time Fees</th></tr>';

  // Loop through the courses data and add them to the table
  for (const course of coursesData.courses) {
    tableHtml += '<tr>';
    tableHtml += `<td>${course.course_name}</td>`;
    tableHtml += `<td>${course.school}</td>`;
    tableHtml += `<td>${course.level}</td>`;
    tableHtml += `<td>${course.duration}</td>`;
    tableHtml += `<td>${course.Starting}</td>`;
    tableHtml += `<td>${course.entry_requirements.join('<br>')}</td>`;
    tableHtml += `<td>${course.fees.UK_FULL_TIME.annual_fee}</td>`;
    tableHtml += `<td>${course.fees.international_FULL_TIME.annual_fee}</td>`;
    tableHtml += '</tr>';
  }
  tableHtml += '</table>';

  // Write the HTML table to the file
  fs.writeFileSync('course.html', tableHtml);
}, 5000); // Update every 5 seconds
