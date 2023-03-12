<?php

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheets/dashboard.css">
    <title>Dashboard</title>
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav>
      <a href="#pending-assignments">Courses</a>
      <a href="#register-for-courses">Instuctors</a>
      <a href="#submit-assignment">Reports</a>
    </nav>

    <!-- Main Content -->
    <main>
      <!-- Pending Assignments -->
      <section id="pending-assignments">
        <h2>Add Instructor</h2>
        <!-- List of assignments goes here -->
      </section>

      <!-- Register for Courses -->
      <section id="register-for-courses">
        <h2>Add Student</h2>
            <div id="signup-form" style="display: none;">
            <h2 class="login-header">Sign Up</h2>
            <form class="form" id="signup_form">
                <input id="signup-lastname" type="text" placeholder="Last Name" required>
                <input id="signup-firstname" type="text" placeholder="First Name" required>
                <input id="signup-othername" type="text" placeholder="Middle Name"><br/>
                <input id="signup-regno" type="text" placeholder="Reg Number" required>
                <input id="signup-email" type="email" placeholder="Email" required>
                <input id="signup-password" type="password" placeholder="Password" required>
                <input id="signup-confirmpassword" type="password" placeholder="Confirm Password" required>
                <!--<input id="signup" type="text" value="1" hidden></input>-->
                <input class="btn" id="signup-submit" type="submit" value="Sign Up">
            </form>
    </div>
      </section>

      <!-- Submit Assignment -->
      <section id="submit-assignment">
        <h2>Add Courses</h2>
        <!-- Assignment submission form goes here -->
      </section>
    </main>

    <script src="../dashboard.js"></script>
  </body>
</html>
