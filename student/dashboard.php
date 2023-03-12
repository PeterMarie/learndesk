<?php
  require_once('../inc/common.php');

  if(check_log_in('oasssess')){
    if($_SESSION['user_role'] != "student"){
      //header("Location: ../login.php");
      //exit();
    }
  } else{
    //header("Location: ../login.php");
    //exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Student Dashboard | Online Assignment Submission System</title>
  <link rel="stylesheet" href="../stylesheets/dashboard.css">
</head>
<body>
  <header>
    <h1>Online Assignment Submission System</h1>
  </header>

  <nav>
    <ul>
      <li class="menu-item"><a href="#pending-assignments">Pending Assignments</a></li>
      <li class="menu-item"><a href="#submit-assignment">Submit Assignment</a></li>
      <li class="menu-item"><a href="#submitted-assignments">Submitted Assignments</a></li>
      <li class="menu-item"><a href="#grades">Grades</a></li>
      <li class="menu-item"><a href="#registered-courses">Registered Courses</a></li>
      <li class="menu-item"><a href="#request-course-registration">Request Course Registration</a></li>
      <li class="menu-item"><a href="#profile">Profile</a></li>
    </ul>
  </nav>
<main>
  <aside class="#side-menu">
    <nav>
      <ul>
        <li class="side-menu-item"><a href="#pending-assignments">Pending Assignments</a></li>
        <li class="side-menu-item"><a href="#submit-assignment">Submit Assignment</a></li>
        <li class="side-menu-item"><a href="#submitted-assignments">Submitted Assignments</a></li>
        <li class="side-menu-item"><a href="#grades">Grades</a></li>
        <li class="side-menu-item"><a href="#registered-courses">Registered Courses</a></li>
        <li class="side-menu-item"><a href="#request-course-registration">Request Course Registration</a></li>
        <li class="side-menu-item"><a href="#profile">Profile</a></li>
      </ul>
    </nav>
  </aside>

  <section id="pending-assignments">
    <h2>Pending Assignments</h2>
    <table id="p-assignments-table">
      <tr>
        <th>No</th>
        <th>Course</th>
        <th>Assignment Title</th>
        <th>Due Date</th>
      </tr>
    </table>
  </section>

  <section id="submit-assignment">
    <h2>Submit Assignment</h2>
    <form action="submit-assignment.php" method="post" enctype="multipart/form-data" id="submit-assignment-form">
      <div>
        <label for="course">Course:</label>
        <select name="course" id="select_course" onchange="find_assignments()">
          <!-- JS will insert data here -->
        </select>
      </div>
      <div>
        <label for="assignment">Assignment:</label>
        <select name="assignment" id="select_assignment">
          <!-- JS will insert data here -->
        </select>
      </div>
      <div>
        <label for="file">File:</label>
        <input type="file" name="assignment_file" id="assignment_file" required>
      </div>
      <button type="submit">Submit</button>
    </form>
  </section>

  <section id="submitted-assignments">
    <h2>Submitted Assignments</h2>
    <table id="s-assignments-table">
      <tr>
        <th>No</th>
        <th>Course</th>
        <th>Assignment Title</th>
        <th>Submission Date</th>
        <th>Grade</th>
      </tr>
    </table>
  </section>

  <!-- View Registered Courses -->
<section id="registered-courses">
  <h3>My Registered Courses</h3>
    <table id="registered-courses-table">
      <tr>
        <th>No</th>
        <th>Code</th>
        <th>Course Title</th>
        <th>Instructor</th>
      </tr>
    </table>
</section>

<!-- View Student Profile -->
<section id="student-profile">
  <h3>My Profile</h3>
  <form id="update-profile-form">
    <div class="form-group">
      <label for="reg-no">Registration Number</label>
      <input type="text" id="reg-no" name="reg-no" disabled>
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