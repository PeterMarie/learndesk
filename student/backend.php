<?php
    require_once("../backend.php");
    
    // Check if user is logged in 
    if(check_log_in('oasssess')){
        //Do Nothing
    } else{
        header("Location: ../login.php");
        exit();
    }

    if(isset($_POST['task'])){
        $users = new Users($conn);
        $courses = new Courses($conn);
        $assignments = new Assignments($conn);
        $return = array();

        switch ($_POST['task']) {
            case 'get_pending_assignments':
                $return['assignments'] = array();
                $count = 0;
                $get_pending_assignments = $assignments->get_all_pending_assignments();
                while($pending_assignments = $get_pending_assignments->fetch_assoc()){
                    $count++;
                    $assignment = array();
                    $assignment['name'] = $pending_assignments['name'];
                    $assignment['description'] = $pending_assignments['description'];
                    $assignment['id'] = $pending_assignments['id'];

                    //Format due date
                    $due_date = date("i/M/Y", $pending_assignments['due_date']);
                    $assignment['due_date'] = $due_date;

                    //Get Course code
                    $query = "SELECT course_code from courses WHERE id = ?";
                    $get_course_code = $conn->prepare($query);
                    $get_course_code->bind_param("i", $pending_assignments['course']);
                    $get_course_code->execute();
                    $result = $get_course_code->get_result();
                    $course_code = $result->fetch_assoc();
                    $assignment['course'] = $course_code['course_code'];

                    $return['assignments'][$count] = $assignment;
                }
                $return['status'] = "success";
                break;
            
            case 'get_pending_assignments_in_course':
                $return['assignments'] = array();
                $count = 0;
                $get_pending_assignments = $assignments->get_all_pending_assignments_in_course($_POST['course_id']);
                while($pending_assignments = $get_pending_assignments->fetch_assoc()){
                    $count++;
                    $assignment = array();
                    $assignment['name'] = $pending_assignments['name'];
                    $assignment['description'] = $pending_assignments['description'];
                    $assignment['id'] = $pending_assignments['id'];
                    
                    //Format due date
                    $due_date = date("i/M/Y", $pending_assignments['due_date']);
                    $assignment['due_date'] = $due_date;

                    $return['assignments'][$count] = $assignment;
                }
                $return['status'] = "success";
                break;
            
            case 'get_registered_courses':
                $return['registered_courses'] = array();
                $count = 0;
                $get_registered_courses = $courses->get_user_courses($_SESSION['user_id']);
                while($registered_courses = $get_registered_courses->fetch_assoc()){
                    $count++;

                    //Get course details
                    $query = "SELECT * FROM courses WHERE id = ? ";
                    $get_course_details = $conn->prepare($query);
                    $get_course_details->bind_param('i', $registered_courses['course_id']);
                    $get_course_details->execute();
                    $result = $get_course_details->get_result();
                    $course_details = $result->fetch_assoc();

                    $course = array();
                    $course['name'] = $course_details['name'];
                    $course['code'] = $course_details['course_code'];
                    $course['id'] = $course_details['id'];
                    
                    //Get Instructor
                    $query = "SELECT firstname, lastname from users WHERE id = ?";
                    $get_instructor = $conn->prepare($query);
                    $get_instructor->bind_param("i", $course_details['instructor_id']);
                    $get_instructor->execute();
                    $result = $get_instructor->get_result();
                    $instructor = $result->fetch_assoc();
                    $course['instructor'] = $instructor['lastname'] . " " . $instructor['firstname'];

                    //$course['description'] = $registered_courses['description'];
                    $return['registered_courses'][$count] = $course;
                }
                $return['status'] = "success";
                break;
            
            case 'get_submitted_assignments':
                $return['assignments'] = array();
                $get_submitted_assignments = $assignments->get_submitted_assignments($_SESSION['user_id']);
                while($submitted_assignments = $get_submitted_assignments->fetch_assoc()){
                    $assignment = array();
                    $assignment['name'] = $submitted_assignments['name'];
                    $assignment['description'] = $submitted_assignments['description'];

                    //Format date submitted
                    $date_submitted = date("i/M/Y", $submitted_assignments['date_submitted']);
                    $assignment['date_submitted'] = $date_submitted;

                    //Get Grade if Graded
                    if(!empty($submitted_assignments['grade'])){
                        $assignment['grade'] = $submitted_assignments['grade'];
                    } else {
                        $assignment['grade'] = "Pending";
                    }

                    //Get Course code
                    $query = "SELECT course_code from courses WHERE id = ?";
                    $get_course_code = $conn->prepare($query);
                    $get_course_code->bind_param("i", $submitted_assignments['course']);
                    $get_course_code->execute();
                    $result = $get_course_code->get_result();
                    $course_code = $result->fetch_assoc();
                    $assignment['course'] = $course_code;

                    $return['assignments'][$pending_assignments['id']] = $assignment;
                }
                $return['status'] = "success";
                break;
            
            case 'submit_assignment':
                $assignment_id = $_POST['assignment_id'];
                $time = time();
                $month_year = date('MY', $time);

                $file_extension = pathinfo(basename($_FILES["assignment_file"]["name"]), PATHINFO_EXTENSION);
                $type = ($_FILES["assignment_file"]["type"]); // mime_content_type($_FILES["file"]["tmp_name"]);
                if (($type != "application/pdf") && ($type != "application/doc") && ($type != "application/docx") && ($type != "application/rtf") && ($type != "application/vnd.openxmlformats-officedocument.wordprocessingml.document")){
                    $return['error'] = "Only pdf, doc, docx and rtf file formats allowed!";
                    $return['type'] = $type;
                } elseif($file_extension != "pdf" && $file_extension != "doc" && $file_extension != "docx") {
                    $return['error'] = "Only pdf, doc, docx and rtf file formats allowed!";
                } elseif($_FILES["assignment_file"]["size"] > 20000000) {
                    $return['error'] = "File uploads must be less than 20MB";
                } else {
                    $assignment_file_name = $_FILES['assignment_file']['name'];
                    $file_path = "../assignments/" . $month_year . "/" . $assignment_id . "_" . $assignment_file_name;
                    if(!file_exists("../assignments/" . $month_year)){
                        mkdir("../assignments/" . $month_year);
                    }
                    if(move_uploaded_file($_FILES['assignment_file']['tmp_name'], $file_path)){
                        $return = $assignments->submit_assignment($_SESSION['user_id'], $assignment_id, $file_path);
                        $return['status'] = "success";
                    }
                }
                break;
            
            case 'update_profile':
                # code...
                break;
            
            case 'request_course_registration':
                if (isset($_POST['register_course'])) {
                    $course_id = $_POST['course_id'];
                    $courses->apply_for_course($_SESSION['user_id'], $course_id);
                    header("Location: dashboard.php");
                    exit();
                }
                break;

            case 'get_profile_data':
                $get_profile_data = $users->get_user_details($_SESSION['user_id']);
                $return = $get_profile_data->fetch_assoc();
                $return['status'] = "success";
                break;

            default:
                # code...
                break;
        }

        echo json_encode($return);
    }
    

?>
