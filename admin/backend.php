<?php
    require_once('backend.php');
    
    start_session('oasssess');

    if(isset($_POST['task'])){
        $return = array();
        $user = new Users($conn);

        switch ($_POST['task']) {
            case 'signin':
                $user_details = $user->usersignin($_POST['reg_no'], $_POST['password']);
                if($user_details){
                    $return['status'] = "success";
                    $_SESSION['user_id'] = $user_details['id'];
                    $_SESSION['user_role'] = $user_details['role'];

                } else {
                    $return['status'] = "failed";
                    $return['error'] = "Invalid email or password";
                }
                break;

            case 'signup':
                $user_id = $user->createUser($_POST);
                if($user_id){
                    $return['status'] = "success";
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_role'] = 'student';

                } else {
                    $return['status'] = "failed";
                    $return['error'] = "An error occurred, please try again";
                }
                break;
            
            default:
                $return['status'] = "failed";
                $return['error'] = "An error occurred, please try again later";
                break;
        }

        echo json_encode($return);
    }

?>