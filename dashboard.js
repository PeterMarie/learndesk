// Get the assignments
function getAssignments() {
  // Get the user ID from local storage or other means
  var userId = ...;

  // Make an AJAX request to get the assignments
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "getAssignments.php?userId=" + userId, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      // Parse the response as JSON and update the assignments section
      var assignments = JSON.parse(xhr.responseText);
      updateAssignmentsSection(assignments);
    }
  };
  xhr.send();
}

// Register a course
function registerCourse(courseId) {
  // Get the user ID from local storage or other means
  var userId = ...;

  // Make an AJAX request to register the course
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "registerCourse.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      // Show a success message or handle errors
      //...
    }
  };
  xhr.send("userId=" + userId + "&courseId=" + courseId);
}
