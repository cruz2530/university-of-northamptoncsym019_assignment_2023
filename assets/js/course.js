$(document).ready(function () {
	var highlightCounter = 0;
	var contentCounter = 0;
	var moduleCounter = 0;
	var faqCounter = 0;

	// Add button click event
	$(document).on("click", ".addMore", function (e) {
		e.preventDefault();
		var fieldType = $(this).siblings("span").attr("name");
		var fieldWrapper = $(this).closest(".field-wrapper");

		switch (fieldType) {
			case "highlights":
				highlightCounter++;
				var highlightField = $(
					'<div class="field"><input required class="inputFields" type="text" name="highlights[]" id="highlight' +
					highlightCounter +
					'"><button class="remove">-</button></div>'
				);
				fieldWrapper.append(highlightField);
				break;
			case "contents":
				contentCounter++;
				var contentField = $(
					'<div class="field"><input required class="inputFields" type="text" name="contents[]" id="content' +
					contentCounter +
					'" ><button class="remove">-</button></div>'
				);
				fieldWrapper.append(contentField);
				break;
			case "modules":
				moduleCounter++;
				var moduleField = $(
					'<div class="field1"><div class="module-input"><input required class="inputFields2" type="text" name="modules[' +
					moduleCounter +
					'][code]" placeholder="Code"><input required class="inputFields2" type="text" name="modules[' +
					moduleCounter +
					'][title]" placeholder="Title"><input required class="inputFields2" type="text" name="modules[' +
					moduleCounter +
					'][credits]" placeholder="Credits"><select class="inputFields2" name="modules[' +
					moduleCounter +
					'][status]" aria-placeholder="status"><option value="optional">Optional</option><option value="designated">Designated</option></select></div><button class="remove">-</button></div>'
				);
				fieldWrapper.append(moduleField);
				break;

			case "faq":
				faqCounter++;
				var faqField = $(
					'<div class="field2"><div class="faqFieldContainer"><span class="formFields"><label id="fieldLabels" for="question' +
					faqCounter +
					'">Question:</label><input required class="inputFields" type="text" name="faqs[' +
					faqCounter +
					'][question]" id="question' +
					faqCounter +
					'"></span><span class="formFields"><label id="fieldLabels" for="answer' +
					faqCounter +
					'">Answer:</label><input required class="inputFields" type="text" name="faqs[' +
					faqCounter +
					'][answer]" id="answer' +
					faqCounter +
					'"></span>  <button class="remove">-</button></div></div>'
				);
				fieldWrapper.append(faqField);
				break;
		}
	});

	// Remove button click event
	$(document).on("click", ".remove", function (e) {
		e.preventDefault();
		$(this).closest(".field").remove();
		$(this).closest(".field1").remove();
		$(this).closest(".field2").remove();
	});

	// add course
	$(document).on("click",'.addCourse', function (e) {
		e.preventDefault(); // Prevent the default form submission

		// Perform form validation
		if (!validateForm()) {
			return alert("Form validation failed");
		}

		// Get form input values
		var title = $("#title").val();
		var overview = $("#overview").val();
		var highlights = $.map($('input[name="highlights[]"]'), function (input) {return $(input).val();});
		var contents = $.map($('input[name="contents[]"]'), function (input) {return $(input).val();});
		var creditScheme = $("#credit_scheme").val();
		var requirement = $("#requirement").val();
		var fee = $("#fee").val();

		var modules = [];
		$(".field1").each(function (index) {
			var module = {};
			module["code"] = $(this)
				.find('input[name="modules[' + index + '][code]"]')
				.val();
			module["title"] = $(this)
				.find('input[name="modules[' + index + '][title]"]')
				.val();
			module["credits"] = $(this)
				.find('input[name="modules[' + index + '][credits]"]')
				.val();
			module["status"] = $(this)
				.find('select[name="modules[' + index + '][status]"]')
				.val();
			modules.push(module);
		});

		var faqs = [];

		$(".field2").each(function (index) {
			var faq = {};
			faq["question"] = $(this)
				.find('input[name="faqs[' + index + '][question]"]')
				.val();
			faq["answer"] = $(this)
				.find('input[name="faqs[' + index + '][answer]"]')
				.val();
			faqs.push(faq);
		});

		// Create an object with form data
		var data = {
			title: title,
			overview: overview,
			highlights: highlights,
			contents: contents,
			modules: modules,
			creditScheme: creditScheme,
			requirement: requirement,
			fee: fee,
			faqs: faqs,
		};

		// Send the form data to the server
		$.ajax({
			url: "/task2/php/course/addCourse.php",
			type: "POST",
			dataType: "json",
			data: JSON.stringify(data),
			contentType: "application/json",
			success: function (resp) {
				// Handle the response from the server
			  
				if (resp.status) {
				  alert("Form submitted successfully!");
				  // Redirect to the home page
				  window.location.href = "/task2/index.php";
				} else {
				  alert("Form submission failed");
				}
			  
				console.log(resp);
			  },
			error: function (error) {
				// Handle any errors that occur during the form submission
				console.error("Form submission failed: ");
			},
		});
	});


	// update course
	$(document).on('click','.updateCourse', function(e){
		e.preventDefault();

		if(!validateForm()){
			return alert("Form validation failed");
		}


		// Get form input values
		var courseId = $("#courseId").val();
		var title = $("#title").val();
		var overview = $("#overview").val();
		var creditScheme = $("#credit_scheme").val();
		var requirement = $("#requirement").val();
		var fee = $("#fee").val();

		var highlights = $.map($('input[name="highlights[]"]'), function (input) {
			var id = $(input).data('highlight-id'); // Retrieve the data attribute 'highlight-id'
			var highlight = $(input).val();
			return { id: id, highlight: highlight }; // Return an object with 'id' and 'highlight' properties
		});

		var contents = $.map($('input[name="contents[]"]'), function(input){
			var id = $(input).data('content-id');
			var content = $(input).val();

			return { id: id, content: content}
		})

		var modules = $.map($('.field1'), function (field) {
			var module = {};
			module.code = $(field).find('input[name^="modules"][name$="[code]"]').val();
			module.title = $(field).find('input[name^="modules"][name$="[title]"]').val();
			module.credits = $(field).find('input[name^="modules"][name$="[credits]"]').val();
			module.status = $(field).find('select[name^="modules"][name$="[status]"]').val();
			module.id = $(field).data('module-id'); // Retrieve the data attribute 'module-id'
			return module;
		});

		var faqs = $.map($('.field2'), function (field) {
			var faq = {};
			faq.question = $(field).find('input[name^="faqs"][name$="[question]"]').val();
			faq.answer = $(field).find('input[name^="faqs"][name$="[answer]"]').val();
			faq.id = $(field).data('faq-id');
			return faq;
		});
		

		// Create an object with form data
		var data = {
			courseId: courseId,
			title: title,
			overview: overview,
			highlights: highlights,
			contents: contents,
			modules: modules,
			creditScheme: creditScheme,
			requirement: requirement,
			fee: fee,
			faqs: faqs,
		};

		console.log(data)

		// Send the form data to the server
		$.ajax({
			url: "/task2/php/course/updateCourse.php",
			type: "POST",
			dataType: "json",
			data: JSON.stringify(data),
			contentType: "application/json",
			success: function (resp) {
				// Handle the response from the server

				if (resp.status) {
				  alert("Course updated successfully!");
				  // Redirect to the home page
					location.reload()
				} else {
				  alert("Course update failed");
				}
				
			  },
			error: function (error) {
				// Handle any errors that occur during the form submission
				console.error("Form submission failed: ");
			},
		});


	});


	// delete course
	$(document).on('click','.deleteCourse',function(e) {
		e.preventDefault();
		
		var courseId = $(this).data('course-id');
		
		if (confirm('Are you sure you want to delete this course?')) {
			// Perform the delete operation using AJAX or submit a form
			// Example AJAX request:
			$.ajax({
				url: '/task2/php/course/deleteCourse.php',
				type: 'GET',
				data: { course_id: courseId },
				success: function() {
					// Handle the response from the server
					alert("Course Deleted successfully!");
					// Redirect to the home page
					window.location.href = "/index.php";
				},
				error: function() {
					// Handle the error response
					console.error('Error deleting course:');
				}
			});
		}
	});

	

	// Get the checkbox in the table header
	const headerCheckbox = $('table thead input[type="checkbox"]');

	// Get all the checkboxes in the table rows
	const rowCheckboxes = $('table tbody input[type="checkbox"]');

	// Add an event listener to the header checkbox
	headerCheckbox.on('change', function() {
		// Set the checked state of all row checkboxes to match the header checkbox
		rowCheckboxes.prop('checked', headerCheckbox.prop('checked'));
	});

});

// Function to perform form validation
function validateForm() {
	var isValid = true;

	// Validate required fields
	$("input[required], textarea[required]").each(function () {
		if ($(this).val().trim() === "") {
			$(this).addClass("invalid");
			isValid = false;
		} else {
			$(this).removeClass("invalid");
		}
	});

	return isValid;
}

function confirmAndRemoveHighlight(id) {
	if (confirm('Are you sure you want to remove this field?')) {
		window.location.href = '/php/reference/removeHighlight.php?id=' + id;
	}
}


function confirmAndRemoveContent(id) {
	if (confirm('Are you sure you want to remove this field?')) {
		window.location.href = '/php/reference/removeContent.php?id=' + id;
	}
}

function confirmAndRemoveModule(id) {
	if (confirm('Are you sure you want to remove this field?')) {
		window.location.href = '/php/reference/removeModule.php?id=' + id;
	}
}

function confirmAndRemoveFAQ(id) {
	if (confirm('Are you sure you want to remove this field?')) {
		window.location.href = '/php/reference/removeFAQ.php?id=' + id;
	}
}
