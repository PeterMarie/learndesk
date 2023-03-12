<?php
    require_once("inc/db.php");
    require_once("inc/common.php");

    class Users {
        private $conn;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function createUser($user_details) {
            $firstname = $user_details['firstname'];
            $lastname = $user_details['lastname'];
            if(isset($user_details['middlename'])){
                $middlename = $user_details['middlename'];
            }else{
                $middlename = "";
            }
            $username = $user_details['reg_no'];
            $email = $user_details['email'];
            $password = $user_details['password'];

            $password = password_hash($password, PASSWORD_BCRYPT);
            $time = time();
            $query = "INSERT INTO users (firstname, lastname, middlename, username, email, password, time_added) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssssi", $firstname, $lastname, $middlename, $username, $email, $password, $time);
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            } else {
                return false;
            }
        }

        public function userSignin($username, $password) {
            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Log sign in to user_log
                return $user;
            } else {
                return false;
            }
            } else {
            return false;
            }
        }

        public function addInstructor($username, $email, $password) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO instructors (username, email, password) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $username, $email, $password);
            if ($stmt->execute()) {
            return true;
            } else {
            return false;
            }
        }

        public function instructorSignin($username, $password) {
            $query = "SELECT * FROM instructors WHERE username = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
            $instructor = $result->fetch_assoc();
            if (password_verify($password, $instructor['password'])) {
                // Log sign in to user_log
                return $instructor;
            } else {
                return false;
            }
            } else {
            return false;
            }
        }

        public function get_user_details($user_id){
            $query = "SELECT * FROM users WHERE id = ? ";
            $get_user = $this->conn->prepare($query);
            $get_user->bind_param("i", $user_id);
            $get_user->execute();
            $result = $get_user->get_result();
            return $result;
        }
    }
    //END OF CLASS USERS 

    class Courses{
        // database connection and table name
        private $conn;
        private $table_name = "courses";

        // object properties
        public $course_id;
        public $course_name;
        public $instructor_id;
        public $created_at;

        public function __construct($db){
            $this->conn = $db;
        }

        function get_user_courses($user_id){
            // query to get the courses for a user
            $query = "SELECT course_id 
                        FROM course_registration
                        WHERE user_id = ? AND status = 'approved' ";

            // prepare the query
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // return the results
            return $result;
        }

        function get_instructor_courses($user_id){
            // query to get the courses for a user
            $query = "SELECT * FROM courses WHERE instructor_id = ? ";

            // prepare the query
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // return the results
            return $result;
        }

        function applyForCourse($user_id, $course_id){
            // query to apply for a course
            $query = "INSERT INTO " . $this->table_name . " (user_id, course_id)
                        VALUES (:user_id, :course_id)";

            // prepare the query
            $stmt = $this->conn->prepare($query);

            // bind the parameters
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':course_id', $course_id);

            // execute the query
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        function startCourse($user_id, $course_id){
            // query to start a course
            $query = "UPDATE " . $this->table_name . "
                        SET status = 1
                        WHERE user_id = :user_id AND course_id = :course_id";

            // prepare the query
            $stmt = $this->conn->prepare($query);

            // bind the parameters
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':course_id', $course_id);

            // execute the query
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
    }
    //END OF CLASS COURSES 

    class Assignments {
        private $conn;

        function __construct($db) {
            $this->conn = $db;
        }

        public function get_all_pending_assignments() {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM assignments WHERE user_id = ? AND status = 'pending'";
            $get_assignments = $this->conn->prepare($query);
            $get_assignments->bind_param("i", $user_id);
            $get_assignments->execute();
            $result = $get_assignments->get_result();
            return $result;
        }

        public function get_submitted_assignments() {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM assignments WHERE user_id = ? AND status = 'submitted' OR 'graded'";
            $get_assignments = $this->conn->prepare($query);
            $get_assignments->bind_param("i", $user_id);
            $get_assignments->execute();
            $result = $get_assignments->get_result();
            return $result;
        }

        public function getAllGradedAssignments() {
            $userId = $_SESSION['user_id'];
            $query = "SELECT * FROM assignments WHERE user_id = '$userId' AND status = 'graded'";
            $result = mysqli_query($this->conn, $query);
            return $result;
        }

        public function get_all_pending_assignments_in_course($course_id) {
            $userId = $_SESSION['user_id'];
            $query = "SELECT * FROM assignments WHERE user_id = '$userId' AND course_id = '$course_id' AND status = 'pending'";
            $result = mysqli_query($this->conn, $query);
            return $result;
        }

        public function getGradedAssignmentsInCourse($courseId) {
            $userId = $_SESSION['user_id'];
            $query = "SELECT * FROM assignments WHERE user_id = '$userId' AND course_id = '$courseId' AND status = 'graded'";
            $result = mysqli_query($this->conn, $query);
            return $result;
        }

        public function set_assignment($assignmentName, $description, $course_id, $dueDate, $filePath) {
            $query = "SELECT user_id FROM course_registration WHERE course_id = ? ";
            $get_course_takers = $this->conn->prepare($query);
            $get_course_takers->bind_param("i", $course_id);
            $get_course_takers->execute();
            $result = $get_course_takers->get_result();
            while($course_takers = $result->fetch_assoc()){
                $query = "INSERT INTO assignments (course_id, user_id, name, description, due_date, brief, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
                $set_assignment = $this->conn->prepare($query);
                $set_assignment->bind_param("iissis", $course_id, $course_takers['user_id'], $assignmentName, $description, $dueDate, $filePath);
                $set_assignment->execute();
            }
        }

        public function submit_assignment($user_id, $assignmentId, $filePath) {
            $time = time();
            $query = "UPDATE assignments SET file_path = '$filePath', status = 'submitted', date_submitted = $time WHERE assignment_id = '$assignmentId'";
            $result = mysqli_query($this->conn, $query);
            return $result;
        }

        public function viewSubmittedAssignmentsInCourse($courseId) {
            $userId = $_SESSION['user_id'];
            $query = "SELECT * FROM assignments WHERE user_id = '$userId' AND course_id = '$courseId' AND status = 'submitted'";
            $result = mysqli_query($this->conn, $query);
            return $result;
        }
    }


?>