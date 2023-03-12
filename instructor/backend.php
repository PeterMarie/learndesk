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
            case 'get_assigned_courses':
                $return['assigned_courses'] = array();
                $count = 0;
                $get_assigned_courses = $courses->get_instructor_courses($_SESSION['user_id']);
                while($assigned_courses = $get_assigned_courses->fetch_assoc()){
                    $count++;
                    $course = array();
                    $course['name'] = $assigned_courses['name'];
                    $course['code'] = $assigned_courses['course_code'];
                    $course['id'] = $assigned_courses['id'];
                    
                    //$course['description'] = $assigned_courses['description'];
                    $return['assigned_courses'][$count] = $course;
                }
                $return['status'] = "success";
                break;

            case 'get_current_students':
                $return['current_students'] = array();
                $count = 0;
                $get_assigned_courses = $courses->get_instructor_courses($_SESSION['user_id']);
                while($assigned_courses = $get_assigned_courses->fetch_assoc()){
                    $count++;
                    $query = "SELECT user_id FROM course_registration WHERE course_id = ? ";
                    $get_students = $conn->prepare($query);
                    $get_students->bind_param("i", $assigned_courses['id']);
                    $get_students->execute();
                    $result = $get_students->get_result();
                    while($students = $result->fetch_assoc()){
                        $current_students = array();
                        $query = "SELECT username, firstname, lastname, middlename, department_id FROM users WHERE id = ? ";
                        $get_student_details = $conn->prepare($query);
                        $get_student_details->bind_param("i", $students['user_id']);
                        $get_student_details->execute();
                        $result2 = $get_student_details->get_result();

                        $student_details = $result2->fetch_assoc();
                        $query = "SELECT name FROM departments WHERE id = ? ";
                        $get_department = $conn->prepare($query);
                        $get_department->bind_param("i", $student_details['department_id']);
                        $get_department->execute();
                        $result3 = $get_department->get_result();
                        $department = $result3->fetch_assoc();

                        $current_students['name'] = $student_details['lastname'] . " " . $student_details['firstname'] . " " . $student_details['middlename'];
                        $current_students['reg_no'] = $student_details['username'];
                        $current_students['department'] = $department['name'];

                        $return['current_students'][$count] = $current_students;
                    }

                    $return['status'] = "success";
                }
                break;
            
            case 'set_assignment':
                $course_id = $_POST['course_id'];
                $due_date = strtotime($_POST['due_date']);
                $time = time();

                $file_extension = pathinfo(basename($_FILES["assignment_brief"]["name"]), PATHINFO_EXTENSION);
                $type = ($_FILES["assignment_brief"]["type"]); // mime_content_type($_FILES["file"]["tmp_name"]);
                if (($type != "application/pdf") && ($type != "application/doc") && ($type != "application/docx") && ($type != "application/rtf") && ($type != "application/vnd.openxmlformats-officedocument.wordprocessingml.document")){
                    $return['error'] = "Only pdf, doc, docx and rtf file formats allowed!";
                    $return['type'] = $type;
                } elseif($file_extension != "pdf" && $file_extension != "doc" && $file_extension != "docx") {
                    $return['error'] = "Only pdf, doc, docx and rtf file formats allowed!";
                } elseif($_FILES["assignment_brief"]["size"] > 20000000) {
                    $return['error'] = "File uploads must be less than 20MB";
                } else {
                    $assignment_brief_name = $_FILES['assignment_brief']['name'];
                    $file_path = "../assignment_briefs/" . $course_id . "/" . $assignment_brief_name . "_" . $time;
                    if(!file_exists("../assignment_briefs/" . $course_id)){
                        mkdir("../assignment_briefs/" . $course_id);
                    }
                    if(move_uploaded_file($_FILES['assignment_brief']['tmp_name'], $file_path)){
                        $assignments->set_assignment($_POST['assignment_title'], $_POST['assignment_description'], $course_id, $due_date, $file_path);
                        $return['status'] = "success";
                    }
                }
                break;

            case 'get_profile_data':
                $get_profile_data = $users->get_user_details($_SESSION['user_id']);
                $return = $get_profile_data->fetch_assoc();
                $return['status'] = "success";
                break;

            case 'get_assignments':
                $return['assignments'] = array();
                $count = 0;
                $get_assigned_courses = $courses->get_instructor_courses($_SESSION['user_id']);
                while($assigned_courses = $get_assigned_courses->fetch_assoc()){
                    $query = "SELECT * FROM assignments WHERE course_id = ? ";
                    $get_assignments = $conn->prepare($query);
                    $get_assignments->bind_param("i", $assigned_courses['id']);
                    $get_assignments->execute();
                    $result = $get_assignments->get_result();
                    while($assignments = $result->fetch_assoc()){
                        $count++;
                        $assignment = array();

                        //Get User reg_no
                        $query = "SELECT username FROM users WHERE id = ? ";
                        $get_student_details = $conn->prepare($query);
                        $get_student_details->bind_param("i", $assignments['user_id']);
                        $get_student_details->execute();
                        $result2 = $get_student_details->get_result();
                        $student_details = $result2->fetch_assoc();

                        //Get course_code
                        $query = "SELECT course_code FROM courses WHERE id = ? ";
                        $get_course_details = $conn->prepare($query);
                        $get_course_details->bind_param("i", $assignments['course_id']);
                        $get_course_details->execute();
                        $result3 = $get_course_details->get_result();
                        $course_details = $result3->fetch_assoc();

                        //Create assignment array
                        $assignment['id'] = $assignments['id'];
                        $assignment['title'] = $assignments['name'];
                        $assignment['course_code'] = $course_details['course_code'];
                        $status = $assignment['status'] = $assignments['status'];
                        $assignment['student'] = $student_details['username'];
                        $assignment['due_date'] = $assignments['due_date'];
                        switch ($status) {
                            case 'graded':
                                $assignment['grade'] = $assignments['grade'];
                            
                            case 'submitted':
                                $assignment['assignment'] = $assignments['filepath'];
                                break;
                            
                            default:
                                # code...
                                break;
                        }

                        //Add array to all assignments array
                        $return['assignments'][$count] = $assignment;
                    }
                }
                $return['status'] = "success";
                break;
            }
            echo json_encode($return);
        }

