<?php
  require_once('../inc/common.php');

  if(check_log_in('oasssess')){
    if($_SESSION['user_role'] != "instructor"){
      header("Location: ../login.php");
      exit();
    }
  } else{
    header("Location: ../login.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Assignment Submission System - Instructor Dashboard</title>
    <link rel="stylesheet" href="../stylesheets/dashboard.css">
  </head>
  <body>
    <header>
      <h1>Online Assignment Submission System</h1>
    </header>
    <aside>
      <nav>
        <ul>
          <li><a href="#assigned-courses">Assigned Courses</a></li>
          <li><a href="#students">Students</a></li>
          <li><a href="#set-assignment">Set Assignment</a></li>
          <li><a href="#submitted-assignments">Submitted Assignments</a></li>
          <li><a href="#grade-assignments">Grade Assignments</a></li>
        </ul>
      </nav>
    </aside>
    <main>
      <section id="assigned-courses">
        <h2>Assigned Courses</h2>
          <table id="assigned-courses-table">
            <tr>
              <th>No</th>
              <th>Course Code</th>
              <th>Title</th>
            </tr>
          </table>
      </section>
      <section id="students">
        <h2>Students</h2>
        <!-- Display students for each course here -->
        <table id="students-table">
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Department</th>
            <th>Reg No</th>
          </tr>
        </table>
      </section>
      <section id="set-assignment">
        <h2>Set Assignment</h2>
        <!-- Set assignment form here -->
        <form action="set-assignment.php" method="post" enctype="multipart/form-data" id="set-assignment-form">
          <div>
            <label for="course">Course:</label>
            <select name="course" id="select_course">
              <!-- JS will insert data here -->
            </select>
          </div>
          <div>
            <label for="assignment_title">Assignment Title:</label>
            <input type="text" name="assignment_title" id="assignment_title" />
          </div>
          <div>
            <label for="assignment_description">Assignment Description</label>
            <textarea name="assignment_description" id="assignment_description" /></textarea>
          </div>
          <div>
            <label for="due_date">Set Due Date</label>
            <input type="date" name="due_date" id="due_date" />
          </div>
          <div>
            <label for="file">Assignment Brief:</label>
            <input type="file" name="assignment_brief" id="assignment_brief" required>
          </div>
          <button type="submit">Submit</button>
        </form>
      </section>
      <section id="submitted-assignments">
        <h2>Assignments</h2>
        <div class= "assignment-filters" >
          <span class= "assignment-filters">View All</span>|<span class= "assignment-filters">Submissions</span>|<span class= "assignment-filters">Pending</span>|<span class= "assignment-filters">Past Due</span>|<span class= "assignment-filters">Extension Requests</span>
        </div>
        <!-- Display submitted assignments here -->
        <table id="assignments-table">
          <tr>
            <th>No</th>
            <th>Reg No</th>
            <th>Assignment Title</th>
            <th>Course Code</th>
            <!--<th>Grades</th>-->
            <th>Action</th> <!--View Grade, Grade Assignment, Close Submission, Grant extension-->
          </tr>
        </table>
      </section>
      
      <!-- View Instructor Profile -->
      <section id="instructor-profile">
        <h3>My Profile</h3>
        <form id="update-profile-form">
          <div class="form-group">
            <label for="staff-no">Staff Number</label>
            <input type="text" id="staff-no" name="staff-no" disabled>
          </div>
          <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name" disabled>
          </div>
          <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name" disabled>
          </div>
          <div class="form-group">
            <label for="middle-name">Middle Name</label>
            <input type="text" id="middle-name" name="middle-name" disabled>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
          </div>
          <div class="form-group">
            <label for="update-password">Password</label>
            <input type="password" id="update-password" name="update-password">
          </div>
          <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password">
          </div>
          <button type="submit" id="update-profile-btn">Update Profile</button>
        </form>
      </section>
    </main>
  <footer>
    <p>&copy; 2023 Assignment Submission Management System</p>
  </footer>
  <script src="script.js" type="text/javascript"></script>
  </body>
</html>
